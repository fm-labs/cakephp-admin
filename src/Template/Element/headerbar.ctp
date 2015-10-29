<?php if (!$this->request->session()->check('Auth.User')) return false; ?>
<div class="ui large opaque menu navbar grid">

    <?= $this->Ui->link('', '#', ['id' => 'be-sidebar-toggle', 'class' => 'item', 'icon' => 'content']); ?>

    <div class="item" id="headerbar-breadcrumbs">
        <?= $this->element('Backend.breadcrumbs'); ?>
    </div>

    <!--
    <?= $this->Ui->link(
        $this->get('be_title'),
        $this->get('be_dashboard_url'),
        ['class' => 'item', 'icon' => 'home']
    ); ?>

    <?= $this->Ui->link(
        'Backend',
        ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
        ['class' => 'item', 'icon' => 'cubes']
    ); ?>
    -->

    <div class="right menu">

        <!-- Search
        <div class="item">
            <div class="ui icon mini input">
                <input placeholder="Search..." type="text">
                <i class="search link icon"></i>
            </div>
        </div>
        -->

        <!-- Messages
        <?= $this->Ui->link(
            'Messages',
            '/backend/admin/Messages',
            ['class' => 'item', 'icon' => 'comment']
        ); ?>
        -->

        <div class="ui dropdown item">
            <i class="user icon"></i>
            <?= __('{0}', $this->request->session()->read('Auth.User.name')); ?>
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