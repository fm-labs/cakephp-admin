<?php $this->Breadcrumbs->add(__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index']); ?>
<?php $this->assign('title', "Dashboard"); ?>

<div id="backend-user-dashboard" class="backend dashboard index">

    <div class="row">
        <?php foreach ((array) \Cake\Core\Configure::read('Backend.Dashboard.elements') as $element => $elementConfig): ?>
        <div class="col-md-6">
            <?php
            try {
                echo $this->element($element, $elementConfig);
                echo '<p>' . $element . '</p>';
            } catch (\Exception $ex) {
                echo '<div class="alert alert-danger">' . $ex->getMessage() . '</div>';
            }
            ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>