<div class="container-fluid text-center">

    <div class="alert alert-warning">
        <?= __d('admin', 'Your session has expired. Please login again to continue.'); ?>
    </div>

    <h2 class="form-signin-heading">
        <i class="fa fa-cubes fa-3x"></i>
    </h2>
    <?= $this->Form->create(null, [
        'class' => 'form-signin',
        'horizontal' => false,
        'url' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login']
    ]); ?>
    <?= $this->Form->control('username', [
        'label' => false,
        'placeholder' => __d('admin', 'Username')
    ]); ?>
    <?= $this->Form->control('password', [
        'type' => 'password',
        'label' => false,
        'placeholder' => __d('admin', 'Password')
    ]); ?>
    <?= $this->Form->button(__d('admin', 'Login'), [
        'class' => 'btn btn-lg btn-primary btn-block'
    ]); ?>
    <?= $this->Form->end(); ?>

</div> <!-- /container -->