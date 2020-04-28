<%
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->getColumnType($field) !== 'binary';
    });
%>
<?php $this->Breadcrumbs->add(__d('admin','<%= $pluralHumanName %>'), ['action' => 'index']); ?>
<% if (strpos($action, 'add') === false): %>
<?php $this->Breadcrumbs->add(__d('admin','Edit {0}', __d('admin','<%= $singularHumanName %>'))); ?>
<% else: %>
<?php $this->Breadcrumbs->add(__d('admin','New {0}', __d('admin','<%= $singularHumanName %>'))); ?>
<% endif; %>
<% if (strpos($action, 'add') === false): %>
<?php $this->Toolbar->addPostLink(
    __d('admin','Delete'),
    ['action' => 'delete', $<%= $singularVar %>-><%= $primaryKey[0] %>],
    ['data-icon' => 'trash', 'confirm' => __d('admin','Are you sure you want to delete # {0}?', $<%= $singularVar %>-><%= $primaryKey[0] %>)]
)
?>
<% endif; %>
<?php $this->Toolbar->addLink(
    __d('admin','List {0}', __d('admin','<%= $pluralHumanName %>')),
    ['action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
<%
$done = [];
foreach ($associations as $type => $data) {
    foreach ($data as $alias => $details) {
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
%>
<?php $this->Toolbar->addLink(
    __d('admin','List {0}', __d('admin','<%= $this->_pluralHumanName($alias) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'index'],
    ['data-icon' => 'list']
) %>

<?php $this->Toolbar->addLink(
    __d('admin','New {0}', __d('admin','<%= $this->_singularHumanName($alias) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'add'],
    ['data-icon' => 'plus']
) %>
<%
            $done[] = $details['controller'];
        }
    }
}
%>
<?php $this->Toolbar->endGroup(); ?>
<div class="form">
    <h2 class="ui header">
        <?= __d('admin','<%= Inflector::humanize($action) %> {0}', __d('admin','<%= $singularHumanName %>')) ?>
    </h2>
    <?= $this->Form->create($<%= $singularVar %>, ['class' => 'no-ajax']); ?>
        <div class="ui form">
        <?php
<%
        foreach ($fields as $field) {
            if (in_array($field, $primaryKey)) {
                continue;
            }
            if (isset($keyFields[$field])) {
                $fieldData = $schema->column($field);
                if (!empty($fieldData['null'])) {
%>
                    echo $this->Form->control('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]);
<%
                } else {
%>
                    echo $this->Form->control('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]);
<%
                }
                continue;
            }

            if (in_array($field, ['created', 'modified', 'updated'])) {
                continue;
            } elseif ($schema->getColumnType($field) == "datetime" || in_array($field, ['password'])) {
%>
                //echo $this->Form->control('<%= $field %>');
<%
            } else {
%>
                echo $this->Form->control('<%= $field %>');
<%
            }
        }
        if (!empty($associations['BelongsToMany'])) {
            foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
%>
                echo $this->Form->control('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
<%
            }
        }
%>
        ?>
        </div>

    <?= $this->Form->button(__d('admin','Submit')) ?>
    <?= $this->Form->end() ?>

</div>