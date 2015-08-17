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
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->Html->css('Backend.shared'); ?>

    <?= $this->Html->css('admin/admin'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>

</head>
<body>

<div id="headerbar">
    <?= $this->element('Backend.headerbar'); ?>
</div>
<div id="actionbar">
    <?= $this->element('Backend.actionbar'); ?>
</div>


<div id="sidebar">
    <?= $this->element('Backend.sidebar'); ?>
</div>
<div id="page">

    <div id="page-top">
        <div id="page-breadcrumbs">
            <?= $this->element('Backend.breadcrumbs'); ?>
        </div>
    </div>

    <div id="page-main">

        <div id="page-flash">
            <?= $this->Flash->render('auth') ?>
            <?= $this->Flash->render('backend') ?>
        </div>

        <div id="page-content">

            <div id="toolbar">
                <?php // $this->element('Backend.toolbar'); ?>
            </div>
            <?= $this->fetch('content'); ?>
        </div>
        <div id="page-right">
            <?= $this->fetch('right', "RIGHT"); ?>
        </div>
    </div>

    <div id="page-footer">

    </div>

</div> <!-- #page -->
<script>
var _backend = {
    rootUrl: '<?= $this->Url->build('/'); ?>'
}
</script>
<?= $this->Html->script('Backend.jquery/jquery-1.11.2.min'); ?>
<?= $this->Html->script('Backend.jquery/jquery-ui.min'); // no widgets ?>
<?= $this->Html->script('Backend.chosen/chosen.jquery.min'); ?>
<?= $this->Html->script('Backend.tinymce/tinymce.min'); ?>
<?= $this->Html->script('Backend.tinymce/jquery.tinymce.min'); ?>
<?= $this->Html->script('Backend.pickadate/picker'); ?>
<?= $this->Html->script('Backend.pickadate/picker.date'); ?>
<?= $this->Html->script('Backend.pickadate/picker.time'); ?>
<?= $this->Html->script('SemanticUi.semantic.min'); ?>
<?= $this->Html->script('Backend.shared'); ?>
<?= $this->Html->script('Backend.admin'); ?>
<?= $this->Html->script('Backend.admin-sidebar'); ?>
<?= $this->Html->script('Backend.admin-tinymce'); ?>
<?= $this->Html->script('Backend.admin-chosen'); ?>

<?= $this->fetch('script-bottom') ?>

</body>
</html>