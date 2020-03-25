<?php $this->extend('base'); ?>
<?php $this->loadHelper('Bootstrap.Tabs'); ?>
<!-- SECTION TABS -->
<div class="section-header">
    Tabs
</div>
<div class="row">
    <div class="col-md-12">
        <?php $this->Tabs->create(); ?>
        <?php $this->Tabs->add("Tab 1"); ?>
        Tab 1
        <?php $this->Tabs->add("Tab 2"); ?>
        Tab 2
        <?php $this->Tabs->add("Tab 3"); ?>
        Tab 3
        <?php echo $this->Tabs->render(); ?>
    </div>
</div>


<div class="section-header">
    Tabs with Ajax content
</div>
<div class="row">
    <div class="col-md-12">
        <?php $this->Tabs->create(); ?>
        <?php $this->Tabs->add("Tab 1", ['url' => ['action' => 'ajaxTest', 1]]); ?>
        <?php $this->Tabs->add("Tab 2", ['url' => ['action' => 'ajaxTest', 2]]); ?>
        <?php $this->Tabs->add("Tab 3", ['url' => ['action' => 'ajaxTest', 3]]); ?>
        <?php echo $this->Tabs->render(); ?>
    </div>
</div>