<?php
$boxClasses = ['default', 'primary', 'success', 'warning', 'danger', 'info'];
$defaultParams = [
    'boxClass' => null,
    'boxIconClass' => null,
    'contentClass' => null,
    'footer' => false,
];
$params = (isset($params)) ? $params + $defaultParams : $defaultParams;
?>

<div class="row">
    <?php foreach ($boxClasses as $boxClass) : ?>
    <div class="col-md-2">
        <div class="small-box bg-<?= $boxClass; ?> <?= $params['boxClass']; ?>">
            <div class="inner">
                <h3>150</h3>
                <p>New Orders</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <?php if ($params['footer'] !== false) : ?>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>