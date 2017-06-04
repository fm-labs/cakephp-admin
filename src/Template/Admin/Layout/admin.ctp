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
    <?= $this->Html->script('/backend/libs/adminlte/plugins/jQuery/jquery-2.2.3.min.js'); ?>
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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <?= $this->element('Backend.Layout/admin/logo'); ?>

        <!-- Header Navbar -->
        <?= $this->element('Backend.Layout/admin/navbar'); ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <?= $this->element('Backend.Layout/admin/sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div id="content" class="content-wrapper">


        <div class="flash-wrapper">
            <!-- Flash Auth -->
            <?= $this->Flash->render('auth') ?>
            <!-- Flash Backend -->
            <?= $this->Flash->render('backend') ?>
            <!-- Flash Default -->
            <?= $this->Flash->render() ?>
        </div>

        <!-- Toolbar wrapper -->
        <div class="toolbar-wrapper">
            <?= $this->element('Backend.Layout/admin/toolbar'); ?>
        </div>


        <!-- Content Header (Page header) -->
        <div class="content-header">
            <?= $this->element('Backend.Layout/admin/header'); ?>
        </div>

        <!-- Left container -->
        <?php if ($this->fetch('left')): ?>
            <aside id="left" class="content-aside content-left">
                <?php echo $this->fetch('left'); ?>
            </aside>
        <?php endif; ?>

        <!-- Right column -->
        <?php if ($this->fetch('right')): ?>
            <aside id="right" class="content-aside content-right">
                <?php echo $this->fetch('right'); ?>
            </aside>
        <?php endif; ?>


        <!-- Main content -->
        <main id="main" class="<?= $this->fetch('contentClass', 'content'); ?>">
            <?= $this->fetch('content'); ?>
        </main>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->


    <!-- Main Footer -->
    <footer id="main-footer" class="main-footer">
        <?= $this->element('Backend.Layout/admin/footer'); ?>
    </footer>

    <!-- Control Sidebar -->
    <?= $this->element('Backend.Layout/admin/control_sidebar'); ?>

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script>
    var AdminLTEOptions = {
        sidebarExpandOnHover: false
    };
</script>
<?= $this->Html->script('/backend/js/adminlte/bootstrap/bootstrap.min.js'); ?>
<?= $this->Html->script('/backend/js/adminlte/app.js'); ?>
<?= $this->Html->script('/backend/libs/underscore/underscore-min.js'); ?>
<?= $this->Html->script('/backend/js/backend.js'); ?>
<?= $this->Html->script('/backend/js/iconify.js'); ?>
<?= $this->fetch('script'); ?>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
<script>
    $(document).ready(function() {
        $(window).on('resize', function(ev) {
            var h = $('#content').outerHeight() - $('#main-footer').outerHeight();
            console.log("Resize to ", h);
            $('.content-aside').height(h);
        }).trigger('resize');

        /*
        if (typeof(Storage) !== "undefined" && window.localStorage) {
            var collapse = Number(localStorage.getItem('sidebar_collapse'));
            if (collapse !== 1) {
                $('body').removeClass('sidebar-collapse');
            }

            $('a[data-toggle="offcanvas"]').click(function() {
                var collapse = $('body').hasClass('sidebar-collapse');
                collapse = (collapse != 1) ? 1 : 0;

                localStorage.setItem("sidebar_collapse", collapse);
            })
        } else {
            // Sorry! No Web Storage support..
        }
        */

    })
</script>
</body>
</html>
