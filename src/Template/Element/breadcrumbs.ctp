<?php
use Cake\Core\Configure;
?>
<div class="ui breadcrumb">
    <?=
    $this->Html->getCrumbs(
        '<i class="right chevron icon divider"></i>',
        [
            'text' => Configure::read('Backend.title'),
            'url' => Configure::read('Backend.dashboardUrl'),
        ]
    );
    ?>
</div>