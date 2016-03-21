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

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <?= $this->Html->css('Backend.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->Html->css('Backend.auth'); ?>
    <?= $this->fetch('cssBackend') ?>
    <?= $this->fetch('css') ?>

    <?=
    $this->Html->script('Backend.jquery-1.11.2.min');
    $this->Html->script('Backend.bootstrap.min');
    $this->Html->script('Backend.be-ui');
    ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
    </header>
    <div id="page">
        <div id="flash">
            <?= $this->Flash->render('auth') ?>
        </div>
        <div id="content">
            <?= $this->fetch('content') ?>
        </div>
        <footer>
        </footer>
    </div>

    <?= $this->fetch('scriptBackend'); ?>
    <?= $this->fetch('scriptBottom'); ?>
</body>
</html>
