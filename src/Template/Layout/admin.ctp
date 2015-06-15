<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name="description" content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>


    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default.date'); ?>
    <?= $this->Html->css('Backend.pickadate/themes/default.time'); ?>
    <?= $this->Html->css('Backend.admin'); ?>
    <?= $this->Html->css('Backend.shared'); ?>

    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>

</head>
<body>

<div id="headerbar">
    <?= $this->element('Backend.headerbar'); ?>
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
            <?= $this->fetch('content'); ?>
        </div>
        <div id="page-right">
            <?= $this->fetch('right', "RIGHT"); ?>
        </div>
    </div>

    <div id="page-footer">

    </div>

</div> <!-- #page -->
<?= $this->Html->script('Backend.jquery-1.11.2.min'); ?>
<?= $this->Html->script('SemanticUi.semantic.min'); ?>
<?= $this->Html->script('Backend.tinymce/tinymce.min'); ?>
<?= $this->Html->script('Backend.tinymce/jquery.tinymce.min'); ?>
<?= $this->Html->script('Backend.pickadate/picker'); ?>
<?= $this->Html->script('Backend.pickadate/picker.date'); ?>
<?= $this->Html->script('Backend.pickadate/picker.time'); ?>
<?= $this->Html->script('Backend.backend'); ?>
<?= $this->Html->script('Backend.shared'); ?>

<script>
    $(document).ready(function() {

        // tinymce wysiwyg editor
        $('.tinymce').tinymce({
            plugins: 'image link lists code table media paste wordcount',
            content_css: '<?= $this->Url->build('/'); ?>backend/css/admin.tinymce.css',
            menu : { // this is the complete default configuration
                file   : {title : 'File'  , items : 'newdocument'},
                edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
                insert : {title : 'Insert', items : 'link media | template hr'},
                view   : {title : 'View'  , items : 'visualaid'},
                format : {title : 'Format', items : 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                table  : {title : 'Table' , items : 'inserttable tableprops deletetable | cell row column'},
                tools  : {title : 'Tools' , items : 'spellchecker code'}
            },
            menubar: false,
            toolbar: [
                "formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote | code",
                "undo redo | cut copy paste | link image media | table"
            ]
        });


        $('.tinymce-default').tinymce({
            content_css: '<?= $this->Url->build('/'); ?>backend/css/admin.tinymce.css'
        });
    })
</script>
<?= $this->fetch('script-bottom') ?>

</body>
</html>