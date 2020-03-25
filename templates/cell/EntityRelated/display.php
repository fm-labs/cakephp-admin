<?php $this->loadHelper('Backend.DataTable'); ?>
<?php foreach ($elements as $element) : ?>
    <div class="related">
        <h3><?= h($element['title']); ?></h3>
        <?php if ($element['render'] == 'cell') : ?>
            <?php $this->cell($element['cell'], $element['cellParams'], $element['cellOptions'])->render($element['cellTemplate']); ?>
        <?php elseif ($element['render'] == 'table') : ?>
            <?= $this->DataTable->create($element['tableOptions'])->render(); ?>
        <?php elseif ($element['render'] == 'content') : ?>
            <?= h($element['content']); ?>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
