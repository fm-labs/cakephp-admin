<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $this->fetch('title') ?></title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <!-- styles -->
    <?= $this->Html->css('/backend/css/adminlte/bootstrap/css/bootstrap.min.css'); ?>
    <?= $this->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css'); ?>
    <?= $this->Html->css('/backend/libs/ionicons/css/ionicons.min.css'); ?>
    <?= $this->Html->css('/backend/css/adminlte/AdminLTE.min.css'); ?>
    <?= $this->Html->css('/backend/css/adminlte/skins/skin-blue.min.css'); ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('Backend.layout/default'); ?>
    <?= $this->Html->css('Backend.backend'); // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views ?>

    <!-- scripts -->
    <!--[if lt IE 9]>
    <?= $this->Html->script('/backend/libs/html5shiv/html5shiv.min.js'); ?>
    <?= $this->Html->script('/backend/libs/respond/respond.min.js'); ?>
    <![endif]-->
    <?= $this->Html->script('/backend/libs/jquery/jquery.min.js'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-collapse">
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->fetch('heading', $this->fetch('title')); ?>
                <?php if ($this->fetch('titleDesc')): ?>
                <small><?= $this->fetch('titleDesc'); ?></small>
                <?php endif; ?>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <?= $this->fetch('content'); ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<?= $this->fetch('script'); ?>

<script>
    $(document).ready(function() {
        Backend.Renderer.onReady();
    });
    $(window).on('unload', function() {
        Backend.Renderer.onUnload();
    })
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
