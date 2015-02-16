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

    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->Html->css('Backend.auth'); ?>
    <?= $this->fetch('css') ?>

    <?= $this->fetch('script') ?>

</head>
<body>
    <div id="page">

        <div id="page-content">
            <?= $this->fetch('content'); ?>
        </div>

    </div> <!-- #page -->
    <?= $this->Html->script('Backend.jquery-1.11.2.min.js'); ?>
    <?= $this->Html->script('SemanticUi.semantic.min.js'); ?>
    <script>
    $(document).ready(function() {
        // flash messages
        $('.message .close').on('click', function() {
            $(this).closest('.message').fadeOut();
        });
    });
    </script>
</body>
</html>