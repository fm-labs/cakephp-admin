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

<?= $this->fetch('scriptBackend'); ?>
<?= $this->fetch('scriptBottom'); ?>


</body>
</html>