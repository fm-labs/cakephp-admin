<div class="view container">
    <?= $this->Box->create(__d('admin', "Plugins"), ['class' => 'box-solid']); ?>
    <?= $this->element('Admin.array_to_tablelist', ['data' => $this->get('plugins')]); ?>
    <?= $this->Box->render(); ?>
</div>