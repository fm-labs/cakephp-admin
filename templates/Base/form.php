<?php

$this->loadHelper('Bootstrap.Tabs');
?>
<div class="entity-view view">
    <div class="row">
        <div class="col-md-9">
            <?php echo $this->fetch('content'); ?>
        </div>
        <div class="col-md-3">
            <div class="actionbar">
                <nav class="actionbar-nav">
                    <?php //echo $this->Toolbar->render(['class' => 'actionbar-menu']); ?>
                </nav>
            </div>
        </div>
    </div>

    <?php if (\Cake\Core\Configure::read('debug')) : ?>
        <small><?php echo h(__FILE__); ?></small>
    <?php endif; ?>
</div>
