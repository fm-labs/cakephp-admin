<?php $this->Html->addCrumb(__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index']); ?>
<?php $this->Toolbar->addLink(__d('banana','Refresh'), ['action' => 'index'], ['icon' => 'refresh']); ?>
<div id="backend-user-dashboard" class="backend dashboard index">
    <h1 class="ui header"><i class="rocket icon"></i>Howdy!</h1>

    <p>
        Yeah, It works!
        <br /><br />
        This is the default template for your personal dashboard page.
        <br /><br />
        If you want to customize this page,<br />
        create <span style="font-style: italic;">src/Template/Plugin/Backend/Dashboard/index.ctp</span>
        <br /><br />
        Or set the 'Backend.Dashboard.url' value in <span style="font-style: italic;">config/backend.php</span>
        to a custom controller action
    </p>

</div>
<?php $this->append('backend-sidebar'); ?>
<p class="item">
    If you want to customize this sidebar element,
    create
    <br /><br />
    <span style="font-style: italic;">src/Template/Plugin/Backend/Element/sidebar.ctp</span>
    <br /><br />
    Or use 'backend-sidebar' view blocks to add sidebar menu items
</p>
<?php $this->end(); ?>
