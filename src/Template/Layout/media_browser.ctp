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
    <?= $this->fetch('cssBackend'); ?>
    <?= $this->Html->css('Media.media'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>

</head>
<body>
    <h1>Media Browser</h1>
    <div id="media">
        <?= $this->fetch('content'); ?>
    </div>
    <?= $this->fetch('scriptBackend'); ?>
    <?= $this->fetch('scriptBottom'); ?>
</body>
</html>