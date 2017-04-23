<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $this->fetch('title') ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <!-- Bootstrap 3.3.6 -->
    <?= $this->Html->css('/backend/libs/adminlte/bootstrap/css/bootstrap.min.css'); ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/backend/libs/fontawesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/backend/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <?= $this->Html->css('/backend/libs/adminlte/dist/css/AdminLTE.min.css'); ?>

    <?= ''//$this->Html->css('Backend.global'); ?>
    <?= $this->Html->css('Backend.backend'); ?>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <?= $this->Html->css('/backend/libs/adminlte/dist/css/skins/skin-blue.min.css'); ?>
    <style>
        .wrapper, .content-wrapper { background: transparent !important;}
    </style>
    <?= $this->fetch('css') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="/backend/libs/html5shiv/html5shiv.min.js"></script>
    <script src="/backend/libs/respond/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.2.3 -->
    <?= $this->Html->script('/backend/libs/adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>
    <?= $this->fetch('script') ?>
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
<?= $this->Html->script('/backend/libs/adminlte/bootstrap/js/bootstrap.min.js'); ?>
<?= $this->Html->script('/backend/libs/adminlte/dist/js/app.js'); ?>
<?= $this->Html->script('/backend/libs/underscore/underscore-min.js'); ?>
<?= $this->Html->script('/backend/js/backend.js'); ?>
<?= $this->Html->script('/backend/js/iconify.js'); ?>
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
