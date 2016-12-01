<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?> [iframe]</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">

    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Backend.font-awesome'); ?>
    <?= $this->Html->css('Bootstrap.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.layout.default'); ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<div id="page">
    <div id="flash">
        <?= $this->Flash->render('auth') ?>
        <?= $this->Flash->render('backend') ?>
    </div>
    <main id="main" class="container-fluid">
        <?= $this->fetch('content'); ?>
    </main>
    <div id="loader" class="loader">
        <?= $this->Html->image('/backend/img/ring-alt.svg'); ?>
    </div>
    <div id="modal-container"></div>

</div> <!-- #page -->

<?= $this->Html->script('Bootstrap.bootstrap.min'); ?>
<?= $this->Html->script('Backend.underscore-min'); ?>
<?= $this->Html->script('Backend.backend/backend'); ?>
<?= $this->fetch('scriptBottom'); ?>

<script>
    $(document).ready(function() {
        Backend.Renderer.onReady();
    })
</script>
</body>
</html>