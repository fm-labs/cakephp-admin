<?php $this->assign('title', __d('backend','Unauthorized')); ?>

<div class="ui middle aligned center aligned grid">
    <div class="column">
        <h2 class="ui icon header">
            <div class="content">
                <i class="white lock icon"></i>Forbidden
            </div>
        </h2>

        <div class="ui divider"></div>

        <div>
            <?= $this->Html->link(__d('backend','Go to dashboard page'), $be_dashboard_url); ?><br />
            <?= $this->Html->link(__d('backend','Go to login page'), ['action' => 'login']); ?><br />
            <?= $this->Html->link(__d('backend','Go to logout page'), ['action' => 'logout']); ?><br />
            <?= $this->Html->link(__d('backend','Go to main page'), '/'); ?><br />
        </div>

        <div class="ui hidden divider"></div>
        <?php debug($this->request->session()->read()); ?>
    </div>
</div>