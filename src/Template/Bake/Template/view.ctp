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
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
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
<?php $this->Html->addCrumb(__('<%= $pluralHumanName %>'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb($<%= $singularVar %>-><%= $displayField %>); ?>
<?= $this->Toolbar->addLink(
    __('Edit {0}', __('<%= $singularHumanName %>')),
    ['action' => 'edit', <%= $pk %>],
    ['icon' => 'edit']
) ?>
<?= $this->Toolbar->addLink(
    __('Delete {0}', __('<%= $singularHumanName %>')),
    ['action' => 'delete', <%= $pk %>],
    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]) ?>

<?= $this->Toolbar->addLink(
    __('List {0}', __('<%= $pluralHumanName %>')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('<%= $singularHumanName %>')),
    ['action' => 'add'],
    ['icon' => 'add']
) ?>
<?= $this->Toolbar->startGroup(__('More')); ?>
<%
$done = [];
foreach ($associations as $type => $data) {
    foreach ($data as $alias => $details) {
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
%>
<?= $this->Toolbar->addLink(
    __('List {0}', __('<%= $this->_pluralHumanName($alias) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<%
            $done[] = $details['controller'];
        }
    }
}
%>
<?= $this->Toolbar->endGroup(); ?>
<div class="<%= $pluralVar %> view">
    <h2 class="ui header">
        <?= h($<%= $singularVar %>-><%= $displayField %>) ?>
    </h2>
    <table class="ui attached celled striped table">
        <!--
        <thead>
        <tr>
            <th><?= __('Label'); ?></th>
            <th><?= __('Value'); ?></th>
        </tr>
        </thead>
        -->
<% if ($groupedFields['string']) : %>

<% foreach ($groupedFields['string'] as $field) : %>
<% if (isset($associationFields[$field])) :
            $details = $associationFields[$field];
%>
        <tr>
            <td><?= __('<%= Inflector::humanize($details['property']) %>') ?></td>
            <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
        </tr>
<% else : %>
        <tr>
            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endif; %>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['number']) : %>

<% foreach ($groupedFields['number'] as $field) : %>
        <tr>
            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['date']) : %>

<% foreach ($groupedFields['date'] as $field) : %>
        <tr class="date">
            <td><%= "<%= __('" . Inflector::humanize($field) . "') %>" %></td>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endforeach; %>

<% endif; %>
<% if ($groupedFields['boolean']) : %>
<% foreach ($groupedFields['boolean'] as $field) : %>
        <tr class="boolean">
            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $<%= $singularVar %>-><%= $field %> ? __('Yes') : __('No'); ?></td>
        </tr>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['text']) : %>
<% foreach ($groupedFields['text'] as $field) : %>
        <tr class="text">
            <td><?= __('<%= Inflector::humanize($field) %>') ?></td>
            <td><?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?></td>
        </tr>
<% endforeach; %>
<% endif; %>
    </table>
</div>
<%
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize($details['controller']);
    %>
<div class="related">
    <div class="ui basic segment">
    <h4 class="ui header"><?= __('Related {0}', __('<%= $otherPluralHumanName %>')) ?></h4>
    <?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
    <table class="ui table">
        <tr>
<% foreach ($details['fields'] as $field): %>
            <th><?= __('<%= Inflector::humanize($field) %>') ?></th>
<% endforeach; %>
            <th class="actions"><?= __('Actions') ?></th>
        </tr>
        <?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
        <tr>
            <%- foreach ($details['fields'] as $field): %>
            <td><?= h($<%= $otherSingularVar %>-><%= $field %>) ?></td>
            <%- endforeach; %>

            <%- $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
            <td class="actions">
                <?= $this->Html->link(__('View'), ['controller' => '<%= $details['controller'] %>', 'action' => 'view', <%= $otherPk %>]) %>
                <?= $this->Html->link(__('Edit'), ['controller' => '<%= $details['controller'] %>', 'action' => 'edit', <%= $otherPk %>]) %>
                <?= $this->Form->postLink(__('Delete'), ['controller' => '<%= $details['controller'] %>', 'action' => 'delete', <%= $otherPk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $otherPk %>)]) %>
            </td>
        </tr>

        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    </div>
</div>
<% endforeach; %>
