<?php
/**
 * @var \Cupcake\Health\HealthStatus[] $health
 */
$this->loadHelper('Bootstrap.Icon');

$healthClass = function ($status) {
    $map = [
        \Cupcake\Health\HealthStatus::UNKNOWN => 'unknown',
        \Cupcake\Health\HealthStatus::OK => 'success',
        \Cupcake\Health\HealthStatus::WARN => 'warning',
        \Cupcake\Health\HealthStatus::CRIT => 'danger',
    ];

    return $map[$status] ?? '';
};
$healthIcon = function ($status) use ($healthClass) {
    $map = [
        \Cupcake\Health\HealthStatus::UNKNOWN => 'question',
        \Cupcake\Health\HealthStatus::OK => 'check',
        \Cupcake\Health\HealthStatus::WARN => 'exclamation-triangle',
        \Cupcake\Health\HealthStatus::CRIT => 'times',
    ];

    $icon = $map[$status] ?? '';
    if ($icon) {
        return $this->Icon->fontawesome($icon, [
            'class' => 'text-' . $healthClass($status),
        ]);
    }
};
?>
<div class="index">
    <h2><?= __('Health Status'); ?></h2>

    <?php if ($health) : ?>
    <table class="table table-striped">
        <tr>
            <th><?= __('Check'); ?></th>
            <th><?= __('Status'); ?></th>
            <th><?= __('Message'); ?></th>
        </tr>
        <?php foreach ($health as $checkName => $status) : ?>
            <tr class="<?= $healthClass($status->getStatus()); ?>">
                <td><?= h($checkName); ?></td>
                <td><?= $healthIcon($status->getStatus()); ?></td>
                <td><?= nl2br($status->getMessage()); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>