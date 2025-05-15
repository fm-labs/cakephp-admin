<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * Class SystemController
 *
 * @package Admin\Controller
 */
class SystemController extends AppController
{
    public $actions = [];

    /**
     * Index method
     *
     * @return void
     */
    public function index(): void
    {
        // php info
        ob_start();
        phpinfo(INFO_ALL);
        $phpinfo = ob_get_contents();
        ob_end_clean();
        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
        $this->set(compact('phpinfo'));

        // globals
        $globals = [
            'APP', 'APP_DIR', 'CONFIG', 'CACHE', 'CAKE', 'CAKE_CORE_INCLUDE_PATH', 'CORE_PATH',
            'DS', 'LOGS', 'RESOURCES', 'ROOT', 'TESTS', 'TMP', 'WWW_ROOT'
        ];
        sort($globals);
        $this->set(compact('globals'));

        // Session
        $this->set('session', $this->request->getSession()->read());

        // Configuration
        $this->set('config', Configure::read());

        // Routes
        $this->set('routes', Router::routes());

        // Plugins
        $plugins = [];
        foreach (Plugin::loaded() as $pluginName) {
            $plugins[$pluginName] = Plugin::path($pluginName);
        }
        $this->set('plugins', $plugins);

        // Date and Time
        $date = [];
        $date['dateDefaultTimezoneGet'] = date_default_timezone_get();
        $date['dateTimeZoneIniGet'] = ini_get('date.timezone');
        $this->set(compact('date'));


        $this->viewBuilder()->setOption('serialize', ['plugins', 'routes']);
    }
}
