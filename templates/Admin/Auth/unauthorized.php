<?php $this->assign('title', __d('admin', 'Unauthorized')); ?>
<div class="index container">
    <div class="text-center">
        <h2 class="text-danger">
            <i class="fa fa-lock fa-2x"></i>
            <br />
            Forbidden
        </h2>
        <p>
            Sorry, you are not authorized to access the requested resource.
        </p>
        <hr />
        <div>
            <?= $this->Html->link(__d('admin', 'Go to login page'), [
                    'action' => 'login',
                    '?' => ['redirect' => $this->request->getQuery('unauthorizedUrl')],
            ]); ?><br />
            <?= $this->Html->link(__d('admin', 'Go to logout page'), ['action' => 'logout']); ?><br />
            <?= $this->Html->link(__d('admin', 'Go to main page'), '/'); ?><br />
            <?= $this->Html->link(__d('admin', 'Go to back'), 'javascript:history.go(-1)'); ?><br />
        </div>
        <hr />
    </div>
</div>
