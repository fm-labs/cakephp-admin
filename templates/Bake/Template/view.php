<%
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->getColumnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->getColumnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
    })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<?php $this->Breadcrumbs->add(__d('backend','<%= $pluralHumanName %>'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add($<%= $singularVar %>-><%= $displayField %>); ?>
<?php $this->Toolbar->addLink(
    __d('backend','Edit {0}', __d('backend','<%= $singularHumanName %>')),
    ['action' => 'edit', <%= $pk %>],
    ['data-icon' => 'edit']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend','Delete {0}', __d('backend','<%= $singularHumanName %>')),
    ['action' => 'delete', <%= $pk %>],
    ['data-icon' => 'trash', 'confirm' => __d('backend','Are you sure you want to delete # {0}?', <%= $pk %>)]) ?>

<?php $this->Toolbar->addLink(
    __d('backend','List {0}', __d('backend','<%= $pluralHumanName %>')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend','New {0}', __d('backend','<%= $singularHumanName %>')),
    ['action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<?php $this->Toolbar->startGroup(__d('backend','More')); ?>
<%
$done = [];
foreach ($associations as $type => $data) {
    foreach ($data as $alias => $details) {
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
%>
<?php $this->Toolbar->addLink(
    __d('backend','List {0}', __d('backend','<%= $this->_pluralHumanName($alias) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('backend','New {0}', __d('backend','<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<%
            $done[] = $details['controller'];
        }
    }
}
%>
<?php $this->Toolbar->endGroup(); ?>
<div class="<%= $pluralVar %> view">
    <h2 class="ui header">
        <?= h($<%= $singularVar %>-><%= $displayField %>) ?>
    </h2>

    <?php
    echo $this->cell('Backend.EntityView', [ $<%= $singularVar %> ], [
        'title' => $<%= $singularVar %>->title,
        'model' => '<%= $modelClass %>',
    ]);
    ?>

<!--
    <table class="ui attached celled striped table">

<% if ($groupedFields['string']) : %>

<% foreach ($groupedFields['string'] as $field) : %>
<% if (isset($associationFields[$field])) :
            $details = $associationFields[$field];
%>
        <tr>
            <td><?= __d('backend','<%= Inflector::humanize($details['property']) %>') ?></td>
            <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
        </tr>
<% else : %>
        <tr>
            <td><?= __d('backend','<%= Inflector::humanize($field) %>') ?></td>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endif; %>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['number']) : %>

<% foreach ($groupedFields['number'] as $field) : %>
        <tr>
            <td><?= __d('backend','<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['date']) : %>

<% foreach ($groupedFields['date'] as $field) : %>
        <tr class="date">
            <td><%= "<%= __d('backend','" . Inflector::humanize($field) . "') %>" %></td>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['boolean']) : %>
<% foreach ($groupedFields['boolean'] as $field) : %>
        <tr class="boolean">
            <td><?= __d('backend','<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $<%= $singularVar %>-><%= $field %> ? __d('backend','Yes') : __d('backend','No'); ?></td>
        </tr>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['text']) : %>
<% foreach ($groupedFields['text'] as $field) : %>
        <tr class="text">
            <td><?= __d('backend','<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?></td>
        </tr>
<% endforeach; %>
<% endif; %>
    </table>
</div>
-->
<%
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize($details['controller']);
    %>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __d('backend','Related {0}', __d('backend','<%= $otherPluralHumanName %>')) ?></h4>
    <?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
    <table class="ui table">
        <tr>
<% foreach ($details['fields'] as $field): %>
            <th><?= __d('backend','<%= Inflector::humanize($field) %>') ?></th>
<% endforeach; %>
            <th class="actions"><?= __d('backend','Actions') ?></th>
        </tr>
        <?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
        <tr>
            <%- foreach ($details['fields'] as $field): %>
            <td><?= h($<%= $otherSingularVar %>-><%= $field %>) ?></td>
            <%- endforeach; %>

            <%- $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
            <td class="actions">
                <?= $this->Html->link(__d('backend','View'), ['controller' => '<%= $details['controller'] %>', 'action' => 'view', <%= $otherPk %>]) %>
                <?= $this->Html->link(__d('backend','Edit'), ['controller' => '<%= $details['controller'] %>', 'action' => 'edit', <%= $otherPk %>]) %>
                <?= $this->Form->postLink(__d('backend','Delete'), ['controller' => '<%= $details['controller'] %>', 'action' => 'delete', <%= $otherPk %>], ['confirm' => __d('backend','Are you sure you want to delete # {0}?', <%= $otherPk %>)]) %>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<% endforeach; %>



<% /**
<pre>
    <% debug(get_defined_vars()); %>
    <% debug($groupedFields); %>
    <% debug($associations); %>
</pre>
**/ %>