<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Backend.font-awesome'); ?>
    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.master'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>
    <?= $this->Html->script('Backend.jquery/jquery-1.11.2.min'); ?>
    <?= $this->Html->script('Backend.bootstrap.min'); ?>
    <?= $this->Html->script('Backend.backend'); ?>
    <?= $this->Html->script('Backend.master'); ?>

</head>
<body>

<div id="page">
    <header id="top">
        <nav id="header-nav" class="navbar navbar-default navbar-inverse">
            <?= $this->element('Backend.Layout/master/header_nav'); ?>
        </nav>

        <div id="flash">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>
    </header>

    <main id="main">
        <?= $this->fetch('content'); ?>
    </main>

    <footer id="footer" class="container-fluid">
        <?= $this->element('Backend.Layout/master/footer'); ?>
    </footer>

    <div id="loader" class="loader"><i class="fa fa-cog fa-spin fa-2x"></i></div>
    <div id="modal-container"></div>

</div> <!-- #page -->
</body>
</html>