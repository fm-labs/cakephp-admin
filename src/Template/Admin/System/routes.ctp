<?php
use Cake\Error\Debugger;

?>
<?php $this->Breadcrumbs->add(__d('backend','Backend'), ['controller' => 'Backend', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend','Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('backend', 'Routes')); ?>
<div class="index">
    <h2>Connected Routes</h2>

    <table class="table table-stiped">
        <tr><th>Template</th><th>Defaults</th><th>Options</th></tr>
        <?php
        foreach ($this->get('routes', []) as $route):
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
    </table>
</div>