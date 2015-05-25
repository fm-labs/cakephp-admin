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


    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,300&subset=latin,vietnamese' rel='stylesheet' type='text/css'>
    <?= $this->Html->css('SemanticUi.semantic.min'); ?>
    <?= $this->Html->css('Backend.login'); ?>

    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <style>
        #container {
            width: 600px;
            margin: 10em auto;
        }
    </style>
</head>
<body>
    <header>
    </header>
    <div id="container">
        <div id="flash">
            <?= $this->Flash->render() ?>
            <?= $this->Flash->render('auth') ?>
        </div>
        <div id="content">
            <?= $this->fetch('content') ?>
        </div>
        <footer>
        </footer>
    </div>

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
