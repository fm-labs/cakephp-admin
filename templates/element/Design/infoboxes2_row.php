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
        <div class="info-box bg-<?= $boxClass; ?> <?= $params['boxClass']; ?>">
            <span class="info-box-icon <?= $params['boxIconClass']; ?>">
                <i class="fa fa-envelope-o"></i>
            </span>

            <div class="info-box-content <?= $params['contentClass']; ?>">

                <span class="info-box-text">Bookmarks</span>
                <span class="info-box-number">41,410</span>

                <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                </div>
                  <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <?php endforeach; ?>
</div>