<?php
$boxClasses = ['default', 'primary', 'success', 'warning', 'danger', 'info'];
$defaultParams = [
    'boxClass' => null,
    'boxIconClass' => null,
    'contentClass' => null,
];
$params = (isset($params)) ? $params + $defaultParams : $defaultParams;
?>

<div class="row">
    <?php foreach ($boxClasses as $boxClass) : ?>
    <div class="col-md-2">
        <div class="info-box <?= $params['boxClass']; ?>">
            <span class="info-box-icon bg-<?= $boxClass; ?> <?= $params['boxIconClass']; ?>">
                <i class="fa fa-envelope-o"></i>
            </span>

            <div class="info-box-content <?= $params['contentClass']; ?>">
                <span class="info-box-text">Messages</span>
                <span class="info-box-number">1,410</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <?php endforeach; ?>
</div>