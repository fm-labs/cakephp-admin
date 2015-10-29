<?php $this->assign('title', __('Unauthorized')); ?>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui icon header">
            <div class="content">
                <i class="white lock icon"></i>Forbidden
            </div>
        </h2>

        <div class="ui divider"></div>

        <div>
            <?= $this->Html->link(__('Go to login page'), ['action' => 'login']); ?><br />
            <?= $this->Html->link(__('Go to logout page'), ['action' => 'logout']); ?><br />
            <?= $this->Html->link(__('Go to main page'), '/'); ?><br />
        </div>

    </div>
</div>