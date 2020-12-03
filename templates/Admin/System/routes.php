<?php
use Cake\Error\Debugger;
?>
<?php $this->Breadcrumbs->add(__d('admin', 'Admin'), ['controller' => 'Admin', 'action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Systeminfo'), ['action' => 'index']); ?>
<?php $this->Breadcrumbs->add(__d('admin', 'Routes')); ?>
<div class="index">
    <?= $this->Box->create(__("Connected Routes"), ['class' => 'box-solid']); ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Template</th>
            <th>Defaults</th>
            <th>Name</th>
            <th>Ext</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $formatter = function($url) {
            $parts = [];
            krsort($url);
            foreach ($url as $key => $val) {
                if (is_array($val)) {
                    $val = json_encode($val);
                }
                $parts[] = sprintf("<strong>%s:</strong> %s", $key, $val);
            }
            return join("<br>", $parts);
        };
        foreach ($this->get('routes', []) as $route) :
            echo '<tr>';
            printf(
                '<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
                $route->template,
                $formatter($route->defaults),
                $route->options['_name'] ?? '',
                $formatter($route->options['_ext'] ?? []),
                $formatter($route->options)
            );
            echo '</tr>';
        endforeach;
        ?>
        </tbody>
    </table>
    <?= $this->Box->render(); ?>
</div>