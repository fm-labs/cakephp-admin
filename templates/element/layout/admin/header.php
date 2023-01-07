<?php
/**
 *
 */
?>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <?= $this->Html->link(
            __('Administration'),
            '/admin',
            ['class' => 'navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6']); ?>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100 rounded-0 border-0" type="text" placeholder="Search" aria-label="Search">

    <div class="navbar-nav flex-row pe-3">

        <div class="nav-item text-nowrap">
            <?= $this->Html->link(
                    __d('admin', 'Frontend'),
                    '/',
                    ['target' => '_blank', 'class' => 'nav-link px-3']); ?>
        </div>

        <?= $this->fetch('header_panels_left'); ?>
        <?= $this->fetch('header_panels_right'); ?>

    </div>
</header>
