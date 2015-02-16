<div style="padding-top: 10em;">
    <div class="ui one column stackable center aligned page grid">
        <div class="row">
            <div class="column six wide">
                <?= $this->Flash->render('backend') ?>
                <?= $this->Flash->render('auth') ?>
            </div>
        </div>
        <div class="row">
            <div class="column six wide">
                <h4 class="ui dividing header">Login</h4>
                <?= $this->Form->create(null, ['class' => 'ui form']); ?>
                <?= $this->Form->input('username'); ?>
                <?= $this->Form->input('password'); ?>
                <?= $this->Form->button('<i class="lock icon"></i>Login', ['escape' => false, 'class' => 'ui basic button fluid']); ?>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>