<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <title><?= $this->fetch('title') ?></title>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('Backend.layout/auth.min.css'); ?>
    <!-- scripts -->
    <!--[if lt IE 9]>
    <?= $this->Html->script('/backend/libs/html5shiv/html5shiv.min.js'); ?>
    <?= $this->Html->script('/backend/libs/respond/respond.min.js'); ?>
    <![endif]-->
    <?= $this->fetch('headjs') ?>
</head>
<body>
    <header>
    </header>
    <div id="page">
        <div id="flash" class="container">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <?= $this->fetch('flash') ?>
        </div>
        <main id="content">
            <?= $this->fetch('content') ?>
        </main>
        <footer>
        </footer>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
