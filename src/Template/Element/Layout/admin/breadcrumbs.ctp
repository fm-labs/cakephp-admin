<?php
use Cake\Core\Configure;
?>
<div class="ui breadcrumb">
    <?=
    $this->Html->getCrumbs(
        '<i class="right chevron icon divider"></i>',
        [
            'text' => Configure::read('Backend.Dashboard.title'),
            'url' => ['_name' => 'backend:admin:dashboard'],
        ]
    );
    ?>
</div>