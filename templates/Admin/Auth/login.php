<?php $this->assign('title', __d('admin', 'Login')); ?>
<div class="container">
    <?= $this->Form->create(null, ['class' => 'form-signin', 'horizontal' => false]); ?>
    <h2 class="form-signin-heading">
        <i class="fa fa-cubes fa-3x"></i>
    </h2>

    <?= $this->Form->control('username', [
        'label' => false,
        'placeholder' => __d('admin', 'Username'),
    ]); ?>
    <?= $this->Form->control('password', [
        'type' => 'password',
        'label' => false,
        'placeholder' => __d('admin', 'Password'),
    ]); ?>
    <?= $this->Form->button(__d('admin', 'Login'), [
        'class' => 'btn btn-lg btn-primary btn-block',
    ]); ?>
    <?= $this->Form->end(); ?>
</div> <!-- /container -->
