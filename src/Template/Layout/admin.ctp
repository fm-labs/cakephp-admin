<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->fetch('cssBackend') ?>
    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->Html->script('Backend.bootstrap.min'); ?>
    <?= $this->Html->script('Backend.be-ui'); ?>
    <?= $this->fetch('scriptBackend'); ?>

</head>
<body>

<div id="page">
    <header id="top">
        <div id="toolbar">
            <?php
            echo $this->Ui->menu($this->Toolbar->getMenuItems(),
                ['class' => 'nav nav-pills'],
                ['class' => 'dropdown-menu']
            );
            ?>
        </div>

        <div id="flash" class="container-fluid">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="page-main">
        <div id="page-crumbs">
            <?= $this->element('Backend.Layout/admin/breadcrumbs'); ?>
        </div>
        <div id="page-content" class="container-fluid">
            <?= $this->fetch('content'); ?>
        </div>
    </main>

    <footer id="page-footer">
        <?= '' // $this->element('Backend.Layout/admin/footer'); ?>
    </footer>

</div> <!-- #page -->

<?= $this->fetch('scriptBottom'); ?>

</body>
</html>