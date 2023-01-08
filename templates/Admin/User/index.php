<?php
//$this->extend('base');
// breadcrumbs
$this->loadHelper('User.Auth');
$this->loadHelper('Admin.Breadcrumb');
$this->Breadcrumb->add(__d('user', 'My Account'));

// no robots
$this->Html->meta('robots', 'noindex,nofollow', ['block' => true]);

$this->assign('title', __d('user', 'My Account'));
$this->assign('heading', '');

//$user = $this->getRequest()->getSession()->read('Auth');
$user = $this->Auth->getUser();
$admin = $this->getRequest()->getSession()->read('Admin');
?>
<div id="user-profile">
    <div class="user-image" style="text-align: center">
        <i class="fa fa-5x fa-user"></i>
    </div>
    <h2 style="text-align: center;"><?= h($admin->username); ?></h2>
    <hr />
    <div class="actions" style="text-align: center;">
        <?= $this->Html->link(__d('user', 'Change password'), ['_name' => 'user:passwordchange']); ?><br />
        <?= $this->Html->link(__d('user', 'Logout'), ['action' => 'logout']); ?>
    </div>
</div>
