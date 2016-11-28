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
    <?= $this->Html->css('/backend/adminlte/bootstrap/css/bootstrap.min.css'); ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <?= $this->Html->css('/backend/adminlte/dist/css/AdminLTE.min.css'); ?>

    <?= $this->Html->css('Backend.global'); ?>
    <?= $this->Html->css('Backend.backend'); ?>
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <?= $this->Html->css('/backend/adminlte/dist/css/skins/skin-blue.min.css'); ?>
    <?= $this->fetch('css') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.2.3 -->
    <?= $this->Html->script('/backend/adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>
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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <?= $this->element('Backend.AdminLte/logo'); ?>

        <!-- Header Navbar -->
        <?= $this->element('Backend.AdminLte/navbar'); ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <?= $this->element('Backend.AdminLte/sidebar'); ?>

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
            <!-- Bread crumbs -->
            <?= $this->Html->getCrumbList([
                'class' => 'breadcrumb'
            ], ['text' => $this->get('be_title'), 'url' => ['_name' => 'admin:dashboard']]);
            ?>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            <?= $this->fetch('content'); ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <?= $this->element('Backend.AdminLte/footer'); ?>

    <!-- Control Sidebar -->
    <?= $this->element('Backend.AdminLte/control_sidebar'); ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- Bootstrap 3.3.6 -->
<?= $this->Html->script('/backend/adminlte/bootstrap/js/bootstrap.min.js'); ?>
<!-- AdminLTE App -->
<?= $this->Html->script('/backend/adminlte/dist/js/app.min.js'); ?>

<?= $this->Html->script('Backend.underscore-min'); ?>
<?= $this->Html->script('Backend.jquery/jquery-ui.min'); ?>
<?= $this->Html->script('Backend.tinymce/tinymce.min'); ?>
<?= $this->Html->script('Backend.tinymce/jquery.tinymce.min'); ?>
<?= $this->Html->script('Backend.backend/backend'); ?>
<?= $this->Html->script('Backend.backend/htmleditor'); ?>
<?= $this->Html->script('Backend.jstree/jstree'); ?>
<?= $this->fetch('scriptBottom'); ?>

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
