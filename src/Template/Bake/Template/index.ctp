<%
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return !in_array($schema->columnType($field), ['binary', 'text']);
    })
    ->take(7);
%>
<?php $this->Html->addCrumb(__('<%= $pluralHumanName %>')); ?>

<?php $this->Toolbar->addLink(__('New {0}', __('<%= $singularHumanName %>')), ['action' => 'add'], ['icon' => 'add']); ?>
<%
$done = [];
foreach ($associations as $type => $data):
    foreach ($data as $alias => $details):
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)):
%>
<?= $this->Toolbar->addLink(
    __('List {0}', __('<%= $this->_pluralHumanName($alias) %>')),
    ['controller' => '<%= $details["controller"] %>', 'action' => 'index'],
    ['icon' => 'list']
) ?>
<?= $this->Toolbar->addLink(
    __('New {0}', __('<%= $this->_singularHumanName($alias) %>')),
    ['controller' => '<%= $details["controller"] %>', 'action' => 'add'],
    ['icon' => 'add']
) ?>
<%
            $done[] = $details['controller'];
        endif;
    endforeach;
endforeach;
%>
<div class="<%= $pluralVar %> index">
    <table class="ui compact table striped">
    <thead>
        <tr>
    <% foreach ($fields as $field): %>
        <th><?= $this->Paginator->sort('<%= $field %>') ?></th>
    <% endforeach; %>
        <th class="actions"><?= __('Actions') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
        <tr>
<%        foreach ($fields as $field) {
            $isKey = false;
            if (!empty($associations['BelongsTo'])) {
                foreach ($associations['BelongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
%>
            <td>
                <?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?>
            </td>
<%
                        break;
                    }
                }
            }
            if ($isKey !== true) {
                if (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
%>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
<%
                } else {
%>
            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
<%
                }
            }
        }

        $pk = '$' . $singularVar . '->' . $primaryKey[0];
%>
            <td class="actions">
                <?php
                $menu = new Backend\Lib\Menu\Menu();
                $menu->add(__('View'), ['action' => 'view', <%= $pk %>]);

                $dropdown = $menu->add('Dropdown');
                $dropdown->getChildren()->add(
                    __('Edit'),
                    ['action' => 'edit', <%= $pk %>],
                    ['icon' => 'edit']
                );
                $dropdown->getChildren()->add(
                    __('Delete'),
                    ['action' => 'delete', <%= $pk %>],
                    ['icon' => 'trash', 'confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]
                );
                ?>
                <?= $this->element('Backend.Table/table_row_actions', ['menu' => $menu]); ?>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>
    </table>
    <?= $this->element('Backend.Pagination/default'); ?>
</div>
