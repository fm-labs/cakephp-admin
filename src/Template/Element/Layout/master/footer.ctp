<div class="footer">
    <div class="row">
        <div class="col-md-4">
            <?= __('Logged on as <strong>{0}@{1}</strong>',
                $this->request->session()->read('Backend.User.username'),
                h($this->request->host())); ?> from <?= $this->request->clientIp(); ?>
                (<?= Cake\I18n\I18n::locale(); ?>)
        </div>
        <div class="col-md-4">
            <?= date(DATE_RSS); ?><br />
        </div>
        <div class="col-md-4">
            Backend: <?= Backend\Lib\Backend::version(); ?> /
            CakePHP: <?= Cake\Core\Configure::version(); ?> /
            PHP: <?= phpversion(); ?>
        </div>
    </div>
</div>