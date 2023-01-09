<?php $this->assign('title', __d('admin', 'Login')); ?>
<div class="form-user w-100 m-auto">
    <?= $this->Form->create(null, ['horizontal' => false]); ?>
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
    <?= $this->Form->submit(__d('admin', 'Login')); ?>
    <?= $this->Form->end(); ?>
</div> <!-- /container -->

