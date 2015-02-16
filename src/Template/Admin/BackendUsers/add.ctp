<?php $this->Html->addCrumb(__('Backend Users'), ['action' => 'index']); ?>
<?php $this->Html->addCrumb(__('New Backend User')); ?>
<div class="actions">
    <div class="ui secondary menu">
        <div class="item"></div>
        <div class="right menu">
            <div class="item">
                <i class="list icon"></i>
                <?= $this->Html->link(__('List Backend Users'), ['action' => 'index']) ?>
            </div>
            <div class="ui dropdown item">
                <i class="dropdown icon"></i>
                <i class="tasks icon"></i>Actions
                <div class="menu">
                    <div class="item">No Actions</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ui divider"></div>

<div class="backendUsers ui form">
    <h2><?= __('Add Backend User') ?></h2>
    <?= $this->Form->create($backendUser); ?>
    <?php
        echo $this->Form->input('first_name');
        echo $this->Form->input('last_name');
        echo $this->Form->input('username');
        echo $this->Form->input('password');
        echo $this->Form->input('email');
        echo $this->Form->input('active');
        echo $this->Form->input('last_login_datetime');
    ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
