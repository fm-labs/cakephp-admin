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
        <main id="content" class="user-container text-center">
            <div class="user-view view form-user w-100 m-auto">
                <h1 class="h3 mb-3 fw-normal"><?= $this->fetch('heading', $this->fetch('title')); ?></h1>

                <div id="flash" class="mb-3">
                    <?= $this->Flash->render(); ?>
                    <?= $this->Flash->render('admin'); ?>
                    <?= $this->Flash->render('auth'); ?>
                </div>
                <?= $this->fetch('content'); ?>
            </div>

        </main>

        <footer>
        </footer>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
