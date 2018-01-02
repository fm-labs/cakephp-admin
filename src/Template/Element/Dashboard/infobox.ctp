<?php
$class = (isset($class)) ? $class : 'bg-aqua';
$text = (isset($text)) ? $text : null;
$number = (isset($number)) ? $number : null;
$icon = (isset($icon)) ? $icon : null;
?>
<div class="info-box">
    <span class="info-box-icon <?= h($class); ?>">
    <?php if (isset($icon)): ?>
        <i class="fa fa-<?= h($icon); ?>"></i>
    <?php endif; ?>
    </span>

    <div class="info-box-content">
        <?php if (isset($text)): ?>
        <span class="info-box-text"><?= $text; ?></span>
        <?php endif; ?>
        <?php if (isset($number)): ?>
        <span class="info-box-number"><?= $number; ?></span>
        <?php endif; ?>
        <!--
        <div class="progress">
            <div class="progress-bar" style="width: 70%"></div>
        </div>

        <span class="progress-description">
        70% Increase in 30 Days
        </span>
        -->
    </div>
    <!-- /.info-box-content -->
</div>