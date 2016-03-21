<?php
use Cake\Core\Configure;
?>
<ol class="breadcrumbs">
    <?=
    $this->Html->getCrumbs(
        ' / ',
        [
            'text' => Configure::read('Backend.Dashboard.title'),
            'url' => ['_name' => 'backend:admin:dashboard'],
        ]
    );
    ?>
</ol>