<?php
use Admin\Admin;
use Cupcake\Cupcake;
use Cake\Core\Configure;

$this->loadHelper('Bootstrap.Tabs');
$this->loadHelper('Admin.Box');
?>
<?php $this->Breadcrumbs->add(__d('admin','Systeminfo')); ?>
<div class="index">

    <?php $this->Tabs->create(); ?>
    <?php $this->Tabs->add(__d('admin', 'System')); ?>
    <div class="view container">
        <?php $this->Box->create("System overview", ['class' => 'box-solid']); ?>
        <table class="table">
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">System</td>
            </tr>
            <tr>
                <td>Cake Version</td>
                <td><?= Configure::version(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Admin Version</td>
                <td><?= Admin::version(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Debug Mode</td>
                <td><?= Configure::read('debug'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>DebugKit loaded</td>
                <td><?= \Cake\Core\Plugin::isLoaded('DebugKit'); ?> &nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">PHP</td>
            </tr>
            <tr>
                <td>PHP Version</td>
                <td><?= phpversion(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Memory limit</td>
                <td><?= ini_get('memory_limit'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>Max execution time</td>
                <td><?= ini_get('max_execution_time'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Max input time</td>
                <td><?= ini_get('max_input_time'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Max input vars</td>
                <td><?= ini_get('max_input_vars'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Post max size</td>
                <td><?= ini_get('post_max_size'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Safe mode</td>
                <td><?= ini_get('safe_mode'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Allow url fopen</td>
                <td><?= ini_get('allow_url_fopen'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Allow url include</td>
                <td><?= ini_get('allow_url_include'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>File uploads</td>
                <td><?= ini_get('file_uploads'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Upload max filesize</td>
                <td><?= ini_get('upload_max_filesize'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Log errors</td>
                <td><?= ini_get('log_errors'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>PHP error log file</td>
                <td><?= ini_get('error_log'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">Date & Time</td>
            </tr>
            <tr>
                <td>Default Timezone</td>
                <td><?= date_default_timezone_get(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Timezone</td>
                <td><?= ini_get('date.timezone'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Server Time</td>
                <td><?= date(DATE_RSS); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Server Time (i18n)</td>
                <td><?=
                    $this->Time->format(
                        new \DateTime(),
                        \IntlDateFormatter::FULL,
                        false,
                        null
                    ); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">Locale</td>
            </tr>
            <tr>
                <td>Default Locale</td>
                <td><?= \Cake\I18n\I18n::getDefaultLocale(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Intl Default Locale</td>
                <td><?= ini_get('intl.default_locale'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Display Locale</td>
                <td><?= h($this->request->getQuery('lang')); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Data Locale</td>
                <td><?= h($this->request->getQuery('locale')); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Available Languages (Multilang.Locales)</td>
                <td><?= join(', ', (array)Configure::read('Multilang.Locales')); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">User</td>
            </tr>
            <tr>
                <td>User Locale</td>
                <td><?= $this->request->getSession()->read('Admin.User.locale'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>User Currency</td>
                <td><?= $this->request->getSession()->read('Admin.User.currency'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>User Timezone</td>
                <td><?= $this->request->getSession()->read('Admin.User.timezone'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>User Time</td>
                <td><?=
                    $this->Time->format(
                        new \DateTime(),
                        \IntlDateFormatter::FULL,
                        false,
                        $this->request->getSession()->read('Admin.User.timezone')
                    ); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-weight: bold;">Client</td>
            </tr>
            <tr>
                <td>User Agent</td>
                <td><?= env('HTTP_USER_AGENT'); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Client IP</td>
                <td><?= $this->request->clientIp(); ?>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <?php echo $this->Box->render(); ?>
    </div>

    <?php $this->Tabs->add(__d('admin', 'Config'), ['url' => ['action' => 'config']]); ?>
    <?php $this->Tabs->add(__d('admin', 'Plugins'), ['url' => ['action' => 'plugins']]); ?>
    <?php $this->Tabs->add(__d('admin', 'Routes'), ['url' => ['action' => 'routes']]); ?>
    <?php $this->Tabs->add(__d('admin', 'Globals'), ['url' => ['action' => 'globals']]); ?>
    <?php $this->Tabs->add(__d('admin', 'Session'), ['url' => ['action' => 'session']]); ?>
    <?php $this->Tabs->add(__d('admin', 'PHP Info'), ['url' => ['action' => 'php']]); ?>
    <?php echo $this->Tabs->render(); ?>
</div>