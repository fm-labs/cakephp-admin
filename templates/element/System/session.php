<div class="view container">
    <?= $this->Box->create(__d('admin', 'Session Info'), ['class' => 'box-solid']); ?>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('session')]); ?>
    <?= $this->Box->render(); ?>
</div>