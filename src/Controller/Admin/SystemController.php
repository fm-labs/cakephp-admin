<?php
namespace Backend\Controller\Admin;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * Class SystemController
 *
 * @package Backend\Controller
 */
class SystemController extends AppController
{
    public $actions = [];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        //$this->set('template', 'index');
    }

    /**
     * Display PHPINFO
     *
     * @see http://www.mainelydesign.com/blog/view/displaying-phpinfo-without-css-styles
     * @param int $what PHP Info option
     * @return void
     */
    public function php($what = INFO_ALL)
    {
        ob_start();
        phpinfo($what);
        $phpinfo = ob_get_contents();
        ob_end_clean();

        $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);

        $this->set(compact('phpinfo'));
    }

    /**
     * Displays CAKE PHP Global constants
     *
     * @return void
     */
    public function globals()
    {
        $globals = [
            'APP', 'APP_DIR', 'CONFIG', 'CACHE', 'CAKE', 'CAKE_CORE_INCLUDE_PATH', 'CORE_PATH',
            'DS', 'LOGS', 'ROOT', 'TESTS', 'TMP', 'WWW_ROOT',
        ];
        $this->set(compact('globals'));
    }

    /**
     * List connected routes
     *
     * @return void
     */
    public function routes()
    {
        $this->set('routes', Router::routes());
        $this->set('_serialize', 'routes');
    }

    /**
     * Displays information about loaded Cake plugins
     *
     * @return void
     */
    public function plugins()
    {
        $plugins = [];
        foreach (Plugin::loaded() as $pluginName) {
            /*
            $plugins[$pluginName] = [
                'path' => Plugin::path($pluginName),
                'config' => Plugin::configPath($pluginName),
                'class' => Plugin::classPath($pluginName)
            ];
            */
            $plugins[$pluginName] = Plugin::path($pluginName);
        }

        $this->set('plugins', $plugins);
        $this->set('_serialize', $plugins);
    }

    /**
     * Display date and time
     *
     * @return void
     */
    public function datetime()
    {
        $data = [];

        $data['dateDefaultTimezoneGet'] = date_default_timezone_get();
        $data['dateTimeZoneIniGet'] = ini_get('date.timezone');

        $this->set(compact('data'));
    }

    /**
     * Display session data
     *
     * @return void
     */
    public function session()
    {
        $this->set('session', $this->request->getSession()->read());
    }

    /**
     * Display current configuration
     *
     * @return void
     */
    public function config()
    {
        $this->set('config', Configure::read());
    }
}
