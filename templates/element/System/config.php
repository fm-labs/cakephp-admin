<div class="view container">
    <?= $this->Box->create(__("Configuration"), ['class' => 'box-solid']); ?>
    <?php echo $this->element('Admin.array_to_tablelist', ['data' => $this->get('config')]); ?>
    <?= $this->Box->render(); ?>
</div>
