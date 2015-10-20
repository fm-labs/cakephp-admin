<?php $this->assign('title', __('Login')); ?>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui icon header">
            <!--
            <img src="assets/images/logo.png" class="image">
            -->
            <div class="content">
                <i class="white lock icon"></i>Log-in
            </div>
        </h2>
        <?= $this->Form->create(null, ['class' => 'ui large form']); ?>
            <div class="ui stacked segment">
                <?= $this->Form->input('username', [
                    'label' => false,
                    'placeholder' => __('Username')
                ]); ?>
                <?= $this->Form->input('password', [
                    'type' => 'password',
                    'label' => false,
                    'placeholder' => __('Password')
                ]); ?>
                <?= $this->Form->button(__('Login'), [
                    'class' => 'fluid large primary submit'
                ]); ?>
            </div>
        <?= $this->Form->end(); ?>

        <!--
        <div class="ui message">
            New to us? <a href="#">Sign Up</a>
        </div>
        -->
    </div>
</div>