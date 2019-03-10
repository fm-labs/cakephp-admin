<?php
$boxClasses = ['default', 'primary', 'success', 'warning', 'danger', 'info'];
$defaultParams = [
    'boxClass' => null,
    'headerClass' => null,
    'bodyClass' => null,
    'footerClass' => null,
    'headerHtml' => 'Title',
    'bodyHtml' => 'Hi!',
    'footerHtml' => false,
    'collapse' => false,
    'collapsed' => false,
];
$params = (isset($params)) ? $params + $defaultParams : $defaultParams;
$params['headerHtml'] = ($params['headerHtml']) ?: join('/', [$params['boxClass'], $params['bodyClass'], $params['headerClass']]);
?>

<div class="row">
    <?php foreach ($boxClasses as $boxClass) : ?>
    <div class="col-md-2">
        <div class="box <?= $params['boxClass']; ?> box-<?= $boxClass; ?>">
            <?php if ($params['headerHtml'] !== false) : ?>
                <div class="box-header <?= $params['headerClass']; ?>">
                    <?= $params['headerHtml']; ?>
                    <?php if ($params['collapse']) : ?>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ($params['bodyHtml'] !== false) : ?>
                <div class="box-body <?= $params['bodyClass']; ?>"><?= $params['bodyHtml']; ?></div>
            <?php endif; ?>
            <?php if ($params['footerHtml'] !== false) : ?>
                <div class="box-footer <?= $params['footerClass']; ?>"><?= $params['footerHtml']; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>