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
    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->Html->css('Backend.chosen/chosen.min'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default.date'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default.time'); ?>
    <?= $this->Html->css('Backend.imagepicker/image-picker.css'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->Html->css('Backend.iframe'); ?>
    <?= $this->Html->css('Backend.shared'); ?>

    <?= $this->Html->css('admin/admin'); ?>
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
<script>
    var _backendConf = {
        rootUrl: '<?= $this->Url->build('/'); ?>'
    };
    var _backend = (function (conf) {
        return {
            rootUrl: conf.rootUrl
        }
    })(_backendConf);
</script>

<?= $this->Backend->script('jquery'); ?>
<?= $this->Backend->script('jqueryui'); ?>
<?= $this->Backend->script('tinymce'); ?>
<?= $this->Backend->script('semanticui'); ?>
<?= $this->Backend->script('pickadate'); ?>
<?= $this->Backend->script('imagepicker'); ?>
<?= $this->Backend->script('shared'); ?>
<?= $this->Backend->script('admin'); ?>
<?= $this->Backend->script('admin_sidebar'); ?>
<?= $this->Backend->script('admin_tinymce'); ?>
<?= $this->Backend->script('admin_chosen'); ?>

<?= $this->fetch('script-backend'); ?>
<?= $this->fetch('script-content'); ?>
<?= $this->fetch('script-bottom'); // legacy ?>

</body>
</html>