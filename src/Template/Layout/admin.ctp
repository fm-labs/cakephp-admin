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

</head>
<body>

<div id="top">
    <div id="headerbar">
        <?= $this->element('Backend.headerbar'); ?>
    </div>
    <div id="toolbar">
        <?= $this->fetch('toolbar'); ?>
    </div>

    <div id="flash">
        <?= $this->Flash->render('auth') ?>
        <?= $this->Flash->render('backend') ?>
    </div>
</div>


<div id="sidebar">
    <?= $this->element('Backend.sidebar'); ?>
</div>

<div id="page">
    <div id="page-top">
    </div>

    <div id="page-main">
        <div id="page-content">
            <?= $this->fetch('content'); ?>
        </div>
        <div id="page-right">
            <?= $this->fetch('right'); ?>
        </div>
    </div>

    <div id="page-footer">
        <?= $this->element('Backend.footer'); ?>
    </div>

</div> <!-- #page -->

<?= $this->fetch('scriptBackend'); ?>
<?= $this->fetch('scriptBottom'); ?>

</body>
</html>