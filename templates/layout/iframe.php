<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <title><?= $this->fetch('title') ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex,nofollow">
    <meta name="mobile-web-app-capable" content="yes">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Admin.layout/admin'); ?>
    <?= $this->fetch('css') ?>
    <!-- scripts -->
    <!--[if lt IE 9]>
    <?= $this->Html->script('/admin/libs/html5shiv/html5shiv.min.js'); ?>
    <?= $this->Html->script('/admin/libs/respond/respond.min.js'); ?>
    <![endif]-->
    <?= $this->Html->script('/admin/libs/jquery/jquery.min.js'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<body class="body-iframe <?= $this->get('be_layout_body_class'); ?>">
<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div id="content" class="content-wrapper">

        <section id="main-flash" class="flash-wrapper">
            <!-- Flash Auth -->
            <?php echo $this->Flash->render('auth') ?>
            <!-- Flash Admin -->
            <?php echo $this->Flash->render('admin') ?>
            <!-- Flash Default -->
            <?php echo $this->Flash->render() ?>
            <?php echo $this->fetch('flash'); ?>
        </section>

        <!-- Toolbar -->
        <section id="main-toolbar" class="main-toolbar">
            <?php echo $this->fetch('toolbar'); ?>
        </section>

        <!-- Before -->
        <?php echo $this->fetch('top'); ?>

        <!-- Main content -->
        <main id="main" class="<?= $this->fetch('contentClass', 'content'); ?>">

            <!-- Before -->
            <?php echo $this->fetch('before'); ?>

            <!-- Content -->
            <?php echo $this->fetch('content'); ?>

            <!-- After -->
            <?php echo $this->fetch('after'); ?>
        </main>
        <!-- /.content -->

        <!-- After -->
        <?php echo $this->fetch('bottom'); ?>
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?= $this->fetch('script'); ?>
</body>
</html>
