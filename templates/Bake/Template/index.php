<%
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return !in_array($schema->getColumnType($field), ['binary', 'text']);
    })
    ->take(7);
%>
<?php $this->Breadcrumbs->add(__d('admin','<%= $pluralHumanName %>')); ?>

<?php $this->Toolbar->addLink(__d('admin','New {0}', __d('admin','<%= $singularHumanName %>')), ['action' => 'add'], ['data-icon' => 'plus']); ?>
<%
$done = [];
foreach ($associations as $type => $data):
    foreach ($data as $alias => $details):
        if ($details['controller'] != $this->name && !in_array($details['controller'], $done)):
%>
<?php $this->Toolbar->addLink(
    __d('admin','List {0}', __d('admin','<%= $this->_pluralHumanName($alias) %>')),
    ['controller' => '<%= $details["controller"] %>', 'action' => 'index'],
    ['data-icon' => 'list']
) ?>
<?php $this->Toolbar->addLink(
    __d('admin','New {0}', __d('admin','<%= $this->_singularHumanName($alias) %>')),
    ['controller' => '<%= $details["controller"] %>', 'action' => 'add'],
    ['data-icon' => 'plus']
) ?>
<%
            $done[] = $details['controller'];
        endif;
    endforeach;
endforeach;
%>
<div class="<%= $pluralVar %> index">

    <?php $fields = [
    <%
    foreach ($schema->columns() as $column) {
        echo '\'' . $column . '\',';
    }
    %>
    ] ?>
    <?= $this->cell('Admin.DataTable', [[
        'paginate' => true,
        'model' => '<%= $modelClass %>',
        'data' => $<%= $pluralVar %>,
        'fields' => $fields,
        'debug' => true,
        'rowActions' => [
            [__d('admin','View'), ['action' => 'view', ':id'], ['class' => 'view']],
            [__d('admin','Edit'), ['action' => 'edit', ':id'], ['class' => 'edit']],
            [__d('admin','Delete'), ['action' => 'delete', ':id'], ['class' => 'delete', 'confirm' => __d('admin','Are you sure you want to delete # {0}?', ':id')]]
        ]
    ]]);
    ?>

</div>

<% /**
<pre>
    <% debug(get_defined_vars()); %>
    <% debug($fields->toArray()); %>
    <% debug($associations); %>
</pre>
 **/ %>