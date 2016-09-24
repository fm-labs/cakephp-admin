<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name="description" content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->fetch('cssBackend') ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->fetch('scriptBackend'); ?>

</head>
<body>



<!--
<div id="sidebar">
    <?= '' // $this->element('Backend.sidebar'); ?>
</div>
-->

<div id="page">
    <header id="top">
        <nav id="header-nav">
            <?= $this->element('Backend.Layout/admin/header_nav'); ?>
        </nav>
        <div id="toolbar">
            <?= $this->fetch('toolbar'); ?>
        </div>

        <div id="flash">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="page-main">
        <div id="page-crumbs">
            <?= $this->element('Backend.Layout/admin/breadcrumbs'); ?>
        </div>
        <div id="page-content">
            <?= $this->fetch('content'); ?>
        </div>
        <div id="page-right">
            <?= $this->fetch('right'); ?>
        </div>
    </main>

    <footer id="page-footer">
        <?= $this->element('Backend.Layout/admin/footer'); ?>
    </footer>

</div> <!-- #page -->

<?= $this->fetch('scriptBottom'); ?>

</body>
</html>