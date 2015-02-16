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
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
<% if (strpos($action, 'add') === false): %>
            <div class="item">
                <i class="remove icon"></i>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $<%= $singularVar %>-><%= $primaryKey[0] %>],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $<%= $singularVar %>-><%= $primaryKey[0] %>)]
                )
                ?>
            </div>
<% endif; %>
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__('List {0}', __('<%= $pluralHumanName %>')), ['action' => 'index']) ?>
            </div>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="tasks icon"></i>Actions
                <div class="menu">
<%
                    $done = [];
                    foreach ($associations as $type => $data) {
                        foreach ($data as $alias => $details) {
                            if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
%>
                    <div class="item">
                        <i class="list icon"></i>
                        <?= $this->Html->link(__('List {0}', __('<%= $this->_pluralHumanName($alias) %>')), ['controller' => '<%= $details['controller'] %>', 'action' => 'index']) %>
                    </div>
                    <div class="item">
                        <i class="add icon"></i>
                        <?= $this->Html->link(__('New {0}', __('<%= $this->_singularHumanName($alias) %>')), ['controller' => '<%= $details['controller'] %>', 'action' => 'add']) %>
                    </div>
<%
                                $done[] = $details['controller'];
                            }
                        }
                    }
%>
<% if (empty($associations)) { %>
                    <div class="item">No Actions</div>
<% } %>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="<%= $pluralVar %> ui form">
    <h2><?= __('<%= Inflector::humanize($action) %> {0}', __('<%= $singularHumanName %>')) ?></h2>
    <?= $this->Form->create($<%= $singularVar %>); ?>
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
        if (!in_array($field, ['created', 'modified', 'updated'])) {
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
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
