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
    <?= $this->Html->css('Backend.layout/admin'); ?>
    <?= $this->fetch('css') ?>
    <!-- scripts -->
    <!--[if lt IE 9]>
    <?= $this->Html->script('/backend/libs/html5shiv/html5shiv.min.js'); ?>
    <?= $this->Html->script('/backend/libs/respond/respond.min.js'); ?>
    <![endif]-->
    <?= $this->Html->script('/backend/libs/jquery/jquery.min.js'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<body class="iframe">
<div class="wrapper">

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
            <?= $this->fetch('content'); ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<?= $this->fetch('script'); ?>
<script>
    $(document).ready(function() {
        Backend.Renderer.onReady();
    });
    $(window).on('unload', function() {
        Backend.Renderer.onUnload();
    })
</script>
</body>
</html>
