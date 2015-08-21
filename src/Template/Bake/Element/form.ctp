<%
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    });
%>
<?php $this->Html->addCrumb(__('<%= $pluralHumanName %>'), ['action' => 'index']); ?>
<% if (strpos($action, 'add') === false): %>
<?php $this->Html->addCrumb(__('Edit {0}', __('<%= $singularHumanName %>'))); ?>
<% else: %>
<?php $this->Html->addCrumb(__('New {0}', __('<%= $singularHumanName %>'))); ?>
<% endif; %>
<% if (strpos($action, 'add') === false): %>
<?= $this->Toolbar->addPostLink(
    __('Delete'),
    ['action' => 'delete', $<%= $singularVar %>-><%= $primaryKey[0] %>],
    ['icon' => 'remove', 'confirm' => __('Are you sure you want to delete # {0}?', $<%= $singularVar %>-><%= $primaryKey[0] %>)]
)
?>
<% endif; %>
<?= $this->Toolbar->addLink(
    __('List {0}', __('<%= $pluralHumanName %>')),
    ['action' => 'index'],
    ['icon' => 'list']
) ?>
<?php $this->Toolbar->startGroup('More'); ?>
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
) %>

<?= $this->Toolbar->addLink(
    __('New {0}', __('<%= $this->_singularHumanName($alias) %>')),
    ['controller' => '<%= $details['controller'] %>', 'action' => 'add'],
    ['icon' => 'add']
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
        <?= __('<%= Inflector::humanize($action) %> {0}', __('<%= $singularHumanName %>')) ?>
    </h2>
    <?= $this->Form->create($<%= $singularVar %>); ?>
    <div class="users ui basic segment">
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
                    echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>, 'empty' => true]);
<%
                } else {
%>
                    echo $this->Form->input('<%= $field %>', ['options' => $<%= $keyFields[$field] %>]);
<%
                }
                continue;
            }

            if (in_array($field, ['created', 'modified', 'updated'])) {
                continue;
            } elseif ($schema->columnType($field) == "datetime" || in_array($field, ['password'])) {
%>
                //echo $this->Form->input('<%= $field %>');
<%
            } else {
%>
                echo $this->Form->input('<%= $field %>');
<%
            }
        }
        if (!empty($associations['BelongsToMany'])) {
            foreach ($associations['BelongsToMany'] as $assocName => $assocData) {
%>
                echo $this->Form->input('<%= $assocData['property'] %>._ids', ['options' => $<%= $assocData['variable'] %>]);
<%
            }
        }
%>
        ?>
        </div>
    </div>
    <div class="ui bottom attached segment">
        <?= $this->Form->button(__('Submit')) ?>
    </div>
    <?= $this->Form->end() ?>

</div>