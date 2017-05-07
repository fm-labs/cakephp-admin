<?php $this->Breadcrumbs->add(__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index']); ?>
<div id="backend-user-dashboard" class="backend dashboard index">

    <div class="row">
        <?php foreach ((array) \Cake\Core\Configure::read('Backend.Dashboard.elements') as $element => $elementConfig): ?>
        <div class="col-md-6">
            <?= $this->element($element, $elementConfig); ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!--
    <p>
        This is the default template for your personal dashboard page.
        <br /><br />
        If you want to customize this page,<br />
        create <span style="font-style: italic;">src/Template/Plugin/Backend/Dashboard/index.ctp</span>
        <br /><br />
        Or set the 'Backend.Dashboard.url' value in <span style="font-style: italic;">config/backend.php</span>
        to a custom controller action
    </p>
    -->

</div>