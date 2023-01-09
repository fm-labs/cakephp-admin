<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
<head>
    <title><?= $this->fetch('title') ?></title>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->Html->css('Admin.layout/layout.auth.min.css'); ?>
    <?= $this->fetch('headjs') ?>
</head>
<body>
    <header>
    </header>
    <div id="page" class="user-wrapper text-center">
        <!--
        <div id="flash" class="container">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('admin'); ?>
            <?= $this->Flash->render('auth'); ?>
            <?= $this->fetch('flash') ?>
        </div>
        -->
        <main id="content" class="user-container text-center">
            <div class="user-view view form-user w-100 m-auto">
                <?= $this->Flash->render('auth'); ?>
                <h1 class="h3 mb-3 fw-normal"><?= $this->fetch('heading', $this->fetch('title')); ?></h1>
                <?= $this->fetch('content'); ?>
            </div>

        </main>

        <footer>
        </footer>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
