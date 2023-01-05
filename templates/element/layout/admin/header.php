<?php
/**
 * Admin layout header element
 *
 * Subsections:
 * - header_panels_left: Navbar items for left navbar
 * - header_panels_right Navbar items for right navbar
 */
$this->loadHelper('Bootstrap.Navbar');
?>
<nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Administration</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <?= $this->Html->link(__d('admin', 'Frontend'), '/', ['target' => '_blank', 'class' => 'nav-link']); ?>
                </li>

                <?= $this->fetch('header_panels_left'); ?>
                <?= $this->fetch('header_panels_right'); ?>

            </ul>
        </div>
    </div>
</nav>