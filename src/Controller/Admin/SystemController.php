<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Admin\System\Health;
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
        //$this->set('template', 'index');
    }

    /**
     * Helath method
     *
     * @return void
     */
    public function health(): void
    {
        $health = Health::check();
        $this->set(compact('health'));
    }

    /**
     * Display PHPINFO
     *
     * @see http://www.mainelydesign.com/blog/view/displaying-phpinfo-without-css-styles
     * @param int $what PHP Info option
     * @return void
     */
    public function php($what = INFO_ALL): void
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
    public function globals(): void
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
    public function routes(): void
    {
        $this->set('routes', Router::routes());
        $this->set('_serialize', 'routes');
    }

    /**
     * Displays information about loaded Cake plugins
     *
     * @return void
     */
    public function plugins(): void
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
    public function datetime(): void
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
    public function session(): void
    {
        $this->set('session', $this->request->getSession()->read());
    }

    /**
     * Display current configuration
     *
     * @return void
     */
    public function config(): void
    {
        $this->set('config', Configure::read());
    }
}
