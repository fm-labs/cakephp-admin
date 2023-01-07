<?php $phpinfo = $this->get('phpinfo'); ?>
<div class="system phpinfo view container">
    <?= $this->Box->create("PHP Info", ['class' => 'box-solid']); ?>
    <?php echo $phpinfo; ?>
    <?= $this->Box->render(); ?>
</div>
<script>
    $('.phpinfo').find('table').addClass('table');
</script>
