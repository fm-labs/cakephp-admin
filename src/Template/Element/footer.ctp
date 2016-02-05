<footer>
    <div class="ui divider"></div>
    <div class="ui three column grid">
        <div class="column">
            Host: <?= $this->request->host(); ?><br />
            Backend Version: <?= Backend\Lib\Backend::version(); ?><br />
            CakePHP Version: <?= Cake\Core\Configure::version(); ?><br />
            PHP Version: <?= phpversion(); ?>
        </div>
        <div class="center aligned column">
            <?= __('Logged on as <strong>{0}</strong>', $this->request->session()->read('Backend.User.username')); ?><br />
            Client IP: <?= $this->request->clientIp(); ?><br />
        </div>
        <div class="right aligned column">
            <?= date(DATE_RSS); ?><br />
            Locale: <?= Cake\I18n\I18n::locale(); ?>
        </div>
    </div>
</footer>