<?php $this->loadHelper('Backend.DataTable'); ?>
<?php $this->loadHelper('Backend.Box'); ?>
<?php foreach ($elements as $element) : ?>
    <div class="related">
        <?php $this->Box->create($element['title']); ?>
            <?php if ($element['render'] == 'cell') : ?>
                <?= $this->cell($element['cell'], $element['cellParams'], $element['cellOptions'])->render($element['cellTemplate']); ?>
            <?php elseif ($element['render'] == 'table') : ?>
                <?= $this->DataTable->create($element['tableOptions'])->render(); ?>
            <?php endif; ?>
        <?php echo $this->Box->render(); ?>
    </div>
<?php endforeach; ?>
