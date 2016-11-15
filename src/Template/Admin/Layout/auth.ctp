<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <?= $this->Html->css('Bootstrap.bootstrap.min'); ?>
    <?= $this->Html->css('Backend.font-awesome'); ?>
    <?= $this->Html->css('Backend.global'); ?>
    <?= $this->Html->css('Backend.layout.auth'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->Html->script('Backend.jquery/jquery-1.11.2.min'); ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
    </header>
    <div id="page">
        <div id="flash" class="container">
            <?= $this->Flash->render('auth') ?>
        </div>
        <div id="content">
            <?= $this->fetch('content') ?>
        </div>
        <footer>
        </footer>
    </div>
    <?= ''// $this->Html->script('Bootstrap.bootstrap.min'); ?>
    <?= $this->Html->script('Backend.backend/backend'); ?>
    <?= $this->fetch('scriptBottom'); ?>

    <script>
        $(document).ready(function() {
            Backend.Renderer.onReady();
        })
    </script>
</body>
</html>
