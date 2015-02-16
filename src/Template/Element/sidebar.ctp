<?php
use Cake\Core\Configure;
?>
<div id="backend-admin-sidebar" class="ui left vertical visible overlay sidebar menu">
    <?= $this->Ui->link(
        Configure::read('Backend.Dashboard.title'),
        Configure::read('Backend.Dashboard.url'),
        ['class' => 'item', 'icon' => 'dashboard']
    ); ?>
    <?= $this->Ui->link(
        __('Products'),
        ['plugin' => false, 'controller' => 'Products', 'action' => 'index'],
        ['class' => 'item', 'icon' => '']
    ); ?>
    <?= $this->Ui->link(
        __('News'),
        ['plugin' => false, 'controller' => 'News', 'action' => 'index'],
        ['class' => 'item', 'icon' => '']
    ); ?>
    <!--
    <a class="item">
        <i class="home icon"></i>
        Home
    </a>
    <a class="item">
        <i class="block layout icon"></i>
        Topics
    </a>
    <a class="item">
        <i class="smile icon"></i>
        Friends
    </a>
    <a class="item">
        <i class="calendar icon"></i>
        History
    </a>
    <a class="item">
        <i class="mail icon"></i>
        Messages
    </a>
    <a class="item">
        <i class="chat icon"></i>
        Discussions
    </a>
    <a class="item">
        <i class="trophy icon"></i>
        Achievements
    </a>
    <a class="item">
        <i class="shop icon"></i>
        Store
    </a>
    <a class="item">
        <i class="settings icon"></i>
        Settings
    </a>
    -->
</div>