<?php
use Cake\Error\Debugger;

?>
<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Routes')); ?>
<div class="index">
    <h2>Connected Routes</h2>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Template</th>
            <th>Defaults</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($this->get('routes', []) as $route) :
            echo '<tr>';
            printf(
                '<td>%s</td><td>%s</td><td>%s</td>',
                $route->template,
                Debugger::exportVar($route->defaults),
                Debugger::exportVar($route->options)
            );
            echo '</tr>';
        endforeach;
        ?>
        </tbody>
    </table>
</div>