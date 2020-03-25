<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<div class="view">
    <?php if ($this->get('tabs')) : ?>
        <?php $this->Tabs->create(); ?>
        <?php if ($this->fetch('content')) : ?>
            <?php $this->Tabs->add("Content"); ?>
            asdf
            <?= $this->fetch('content'); ?>
        <?php endif; ?>
        <?php foreach ((array)$this->get('tabs') as $tabId => $tab) : ?>
            <?php $this->Tabs->add($tab['title'], $tab); ?>
        <?php endforeach; ?>
        <?php echo $this->Tabs->render(); ?>
    <?php endif; ?>
</div>