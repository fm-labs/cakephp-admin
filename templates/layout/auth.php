<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <title><?= $this->fetch('title') ?></title>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('Admin.layout/auth.min.css'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<body>
    <header>
    </header>
    <div id="page">
        <div id="flash" class="container">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('admin'); ?>
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
