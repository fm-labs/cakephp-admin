<?php
/**
 * @var array $globals
 */
?>
<div class="view container">
    <?= $this->Box->create(__d('admin', 'CAKEPHP Globals'), ['class' => 'box-solid']); ?>
    <table class="table table-striped table-hover">
        <?php foreach ($globals as $global) : ?>
            <tr>
                <td style="width: 50%; font-weight: bold;"><?= h($global); ?>&nbsp;</td>
                <td><?= h(constant($global)); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?= $this->Box->render(); ?>


    <?= $this->Box->create(__d('admin', 'PHP constants'), ['class' => 'box-solid']); ?>
    <table class="table table-striped table-hover">
        <?php foreach (get_defined_constants(false) as $global => $value) : ?>
            <tr>
                <td style="width: 50%; font-weight: bold;"><?= h($global); ?>&nbsp;</td>
                <td style="word-break: break-word;"><?= h($value); ?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?= $this->Box->render(); ?>
</div>
