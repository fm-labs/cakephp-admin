<?php
//$this->extend('User./base');

$this->assign('heading', ' ');
$this->assign('title', __('Login'))
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
            'placeholder' => __d('user', 'yourname@example.org'),
        ]); ?>
        <?= $this->Form->label('username', __('Email')); ?>
    </div>
    <div class="form-floating">
        <?= $this->Form->password('password', [
            'class' => 'form-control',
            'type' => 'password',
            'placeholder' => __d('user', 'Type your password here'),
            'label' => __d('user', 'Password')]); ?>
        <?= $this->Form->label('password'); ?>
    </div>

    <!--
    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Remember me
        </label>
    </div>
    -->

    <?= $this->Form->button(__d('user', 'Sign in'), [
        'class' => 'w-100 btn btn-lg btn-primary',
    ]); ?>
    <?= $this->Form->end(); ?>

    <p class="my-2 text-muted">
    </p>

</div>
