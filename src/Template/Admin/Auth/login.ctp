<div id="user-login-form" style="position: relative;">

    <div class="ui one column middle aligned relaxed fitted grid">
        <div class="column">
            <h2 class="ui top attached header">Backend Login</h2>
            <?= $this->Form->create(); ?>
            <div class="ui form attached segment">
                <?= $this->Form->input('username'); ?>
                <?= $this->Form->input('password', ['type' => 'password']); ?>
            </div>
            <div class="ui bottom attached segment">
                <?= $this->Form->button(__('Login'), ['class' => 'ui button primary']); ?>
            </div>
            <?= $this->Form->end(); ?>
        </div>
    </div>

</div>