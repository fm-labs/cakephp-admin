<?php
/**
 * Admin layout header element
 *
 * Subsections:
 * - header_panels_left: Navbar items for left navbar
 * - header_panels_right Navbar items for right navbar
 */
?>
<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only"><?= __('Toggle navigation'); ?></span>
    </a>

    <div class="navbar-menu-left">
        <ul class="nav navbar-nav">
            <li><?= $this->Html->link(__d('admin', 'Frontend'), '/', ['target' => '_blank']); ?></li>
            <?= $this->fetch('header_panels_left'); ?>
        </ul>
    </div>

    <div class="navbar-menu-right navbar-custom-menu">
        <ul class="nav navbar-nav">
            <?= $this->fetch('header_panels_right'); ?>
            <!-- Control Sidebar Toggle Button
            <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>
            -->
        </ul>
    </div>
</nav>
