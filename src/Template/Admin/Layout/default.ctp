<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?> [default]</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Bootstrap.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.font-awesome'); ?>
    <?= $this->Html->css('Backend.global'); ?>
    <?= $this->Html->css('Backend.backend'); ?>
    <?= $this->Html->css('Backend.layout.default'); ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="be_sidebar has-toolbar fixed-nav-top">

<div id="page">
    <header id="top">
        <nav id="header-nav" class="navbar navbar-default navbar-inverse navbar-fixed-top">
            <?= $this->element('Backend.Layout/master/header_nav'); ?>
        </nav>

    </header>

    <div id="page-crumbs">
        <?= $this->element('Backend.Layout/admin/breadcrumbs'); ?>
    </div>
    <div id="page-toolbar">
        <?= $this->element('Backend.Layout/admin/toolbar'); ?>
    </div>
    <div id="flash">
        <?= $this->Flash->render('auth') ?>
        <?= $this->Flash->render('backend') ?>
    </div>

    <div id="container">

        <?php if ($this->fetch('left')): ?>
        <aside id="left">
            <?php echo $this->fetch('left'); ?>
        </aside>
        <?php endif; ?>

        <main id="main" class="container-fluid">
            <?= $this->fetch('content'); ?>
        </main>

        <?php if ($this->fetch('right')): ?>
        <aside id="right">
            <?php echo $this->fetch('right'); ?>
        </aside>
        <?php endif; ?>
    </div>

    <footer id="footer" class="container-fluid">
        <?= $this->element('Backend.Layout/master/footer'); ?>
    </footer>
</div> <!-- #page -->


<div id="loader" class="loader">
    <?= $this->Html->image('/backend/img/ring-alt.svg'); ?>
</div>
<div id="modal-container"></div>

<aside id="sidebar" class="be-sidebar-container">
    <?php echo $this->fetch('sidebar'); ?>
    <?php echo $this->element('Backend.sidebar'); ?>
</aside>

<?= $this->Html->script('Bootstrap.bootstrap.min'); ?>
<?= $this->Html->script('Backend.underscore-min'); ?>
<?= $this->Html->script('Backend.jquery/jquery-ui.min'); ?>
<?= $this->Html->script('Backend.tinymce/tinymce.min'); ?>
<?= $this->Html->script('Backend.tinymce/jquery.tinymce.min'); ?>
<?= $this->Html->script('Backend.backend/backend'); ?>
<?= $this->Html->script('Backend.backend/toolbar'); ?>
<?= $this->Html->script('Backend.backend/htmleditor'); ?>
<?= $this->fetch('scriptBottom'); ?>

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