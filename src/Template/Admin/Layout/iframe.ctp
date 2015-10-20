<?php use Cake\Core\Configure;

//Configure::write('debug', 0);
?>
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
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>

</head>
<body>

<div id="page" style="position: relative">
    <div id="page-top">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <?= $this->Flash->render('backend'); ?>
    </div>

    <div id="page-main">
        <?= $this->fetch('content'); ?>
    </div>

    <div id="page-footer">
    </div>

</div> <!-- #page -->

<?= $this->fetch('scriptBottom'); ?>

</body>
</html>