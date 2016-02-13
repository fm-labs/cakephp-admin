<?php
use Backend\Lib\BackendNav;

if (!$this->request->session()->check('Backend.User')) return false;
$this->Html->css('Backend.navigation', ['block' => true]);
?>
<div class="ui opaque large menu">

    <?= $this->Ui->link(
        $this->get('be_title'),
        $this->get('be_dashboard_url'),
        ['class' => 'item', 'icon' => 'home']
    ); ?>

    <nav class="be-nav">
        <?php
        $backendNavMenu = BackendNav::getMenu();
        echo $this->element('Backend.Navigation/menu', ['menu' => $backendNavMenu]);
        ?>
    </nav>


    <div class="right menu">

        <!-- Search -->
        <div class="item">
            <div class="ui icon mini input">
                <input placeholder="Search..." type="text">
                <i class="search link icon"></i>
            </div>
        </div>


        <!-- Messages -->
        <?= $this->Ui->link(
            'Messages',
            '/backend/admin/Messages',
            ['class' => 'item', 'icon' => 'comment']
        ); ?>

        <div class="ui dropdown item">
            <i class="user icon"></i>
            <?= __('{0}', $this->request->session()->read('Backend.User.name')); ?>
            <i class="dropdown icon"></i>
            <div class="menu">
                <?= $this->Ui->link('Profile',
                    ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'user'],
                    ['class' => 'item']); ?>
                <div class="ui divider"></div>
                <?= $this->Ui->link(
                    __('Logout'),
                    $this->get('be_auth_logout_url'),
                    ['class' => 'item']);
                ?>
            </div>
        </div>
    </div>
</div>