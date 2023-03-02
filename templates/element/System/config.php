<div class="view container">
    <?= $this->Box->create(__d('admin', "Configuration"), ['class' => 'box-solid']); ?>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('config')]); ?>
    <?= $this->Box->render(); ?>
</div>
