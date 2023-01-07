<?php
/**
 * @property \Admin\View\AdminView $this
 */
?>
<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?> | <?= $this->get('be_title'); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('Admin.layout/layout.admin'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<body class="body-screen sidebar-mini sidebar-collapsed <?= $this->get('be_layout_body_class'); ?>">
<div class="wrapper">

    <!-- Header -->
    <?php echo $this->fetch('header'); ?>

    <div class="container-fluid px-0">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <?php echo $this->fetch('sidebar'); ?>
        </nav>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper col-md-9 col-lg-10 ms-sm-auto">

            <div id="main-flash" class="flash-wrapper">
                <?= $this->Flash->render(); ?>
                <?= $this->Flash->render('auth'); ?>
                <?= $this->Flash->render('admin'); ?>
                <?= $this->fetch('flash'); ?>
            </div>

            <!-- Breadcrumbs -->
            <div id="main-breadcrumbs" class="main-breadcrumbs mt-2">
                <div class="container-fluid">
                    <?php echo $this->fetch('breadcrumbs'); ?>
                </div>
            </div>

            <!-- Toolbar -->
            <?php echo $this->fetch('toolbar'); ?>

            <!-- Top -->
            <?php echo $this->fetch('top'); ?>

            <!-- Left container -->
            <?php if ($this->fetch('left')) : ?>
                <aside id="main-left" class="content-aside content-left">
                    <?php //echo $this->fetch('left'); ?>
                </aside>
            <?php endif; ?>

            <!-- Right column -->
            <?php if ($this->fetch('right')) : ?>
                <aside id="main-right" class="content-aside content-right">
                    <?php echo $this->fetch('right'); ?>
                </aside>
            <?php endif; ?>

            <!-- Main content -->
            <main id="main" class="content">

                <!-- Before -->
                <?php echo $this->fetch('before'); ?>

                <!-- Content -->
                <?php echo $this->fetch('content'); ?>

                <!-- After -->
                <?php echo $this->fetch('after'); ?>
            </main>
            <!-- /.content -->

            <!-- Bottom -->
            <?php echo $this->fetch('bottom'); ?>

            <!-- Main Footer -->
            <footer id="main-footer" class="main-footer">
                <?php echo $this->fetch('footer'); ?>
            </footer>

        </div>
        <!-- /.content-wrapper -->
    </div>

    <!-- Control Sidebar -->
    <?php //echo $this->fetch('control_sidebar'); ?>

</div>
<!-- ./wrapper -->

<?= $this->fetch('script'); ?>
</body>
</html>
