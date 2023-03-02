<?php
//$this->extend('User./base');

$this->assign('heading', ' ');
$this->assign('title', __d('admin', 'Login'))
?>
<div class="">

    <h2 class="form-signin-heading mb-4">
        <i class="fa fa-cubes fa-2x"></i>
    </h2>

    <?= $this->Form->create(null, [
        //'type' => 'post',
       // 'url' => ['_name' => 'user:login']
    ]); ?>

    <div class="form-floating">
        <?= $this->Form->text('username', [
            'class' => 'form-control',
            'type' => "text",
            'placeholder' => __d('admin',  'yourname@example.org'),
        ]); ?>
        <?= $this->Form->label('username', __d('admin', 'Email')); ?>
    </div>
    <div class="form-floating">
        <?= $this->Form->password('password', [
            'class' => 'form-control',
            'type' => 'password',
            'placeholder' => __d('admin',  'Type your password here'),
            'label' => __d('admin',  'Password')]); ?>
        <?= $this->Form->label('password'); ?>
    </div>

    <!--
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Remember me
        </label>
    </div>
    -->

    <?= $this->Form->button(__d('admin',  'Sign in'), [
        'class' => 'w-100 btn btn-lg btn-primary',
    ]); ?>
    <?= $this->Form->end(); ?>

    <p class="my-2 text-muted">
    </p>

</div>
