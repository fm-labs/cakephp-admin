<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cupcake\Cupcake;

/**
 * Class PluginsController
 *
 * @package Admin\Controller\Admin
 */
class PluginsController extends AppController
{
    public $actions = []; //@TODO Disable ActionComponent

    public $modelClass = false;

    /**
     * Displays information about loaded Cake plugins
     *
     * @return void
     * @TODO Refactor with PluginManager
     */
    public function index(): void
    {
        $plugins = [];
        foreach (Plugin::loaded() as $pluginName) {
            $plugins[$pluginName] = Cupcake::pluginInfo($pluginName);
        }

        // find available plugins from known plugin folders
        $installed = [];
        foreach (App::path('plugins') as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $files = scandir($path);
            foreach ($files as $f) {
                $pluginPath = rtrim($path, '/') . '/' . $f;
                if ($f == '.' || $f == '..' || !is_dir($pluginPath)) {
                    continue;
                }
                if (isset($plugins[$f])) {
                    continue;
                }
                $installed[$f] = [
                    'name' => $f,
                    'path' => $pluginPath,
                    'loaded' => Plugin::isLoaded($f),
                    'handler_class' => null,
                    'handler_loaded' => false,
                ];
                /*
                $installed[$f] = Cupcake::pluginInfo($f);
                */
            }
        }

        $configured = Configure::read('plugins');
        foreach ($configured as $pluginName => $pluginPath) {
            if (isset($plugins[$pluginName]) || isset($installed[$pluginName])) {
                continue;
            }
            $installed[$pluginName] = [
                'name' => $pluginName,
                'path' => $pluginPath,
                'loaded' => Plugin::isLoaded($pluginName),
                'handler_class' => null,
                'handler_loaded' => false,
            ];
        }

        ksort($plugins);
        ksort($installed);

        $this->set('plugins', $plugins);
        $this->set('installed', $installed);
        $this->set('_serialize', ['plugins', 'installed']);
    }

    /**
     * @param string $pluginName Plugin name
     * @param bool|null $newState Plugin enabled state
     * @return false|int
     */
    protected function _setPluginState(string $pluginName, ?bool $newState)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include $pluginsFile;
        }

        $plugins['Plugin'][$pluginName] = ['bootstrap' => $newState, 'routes' => $newState];
        if ($newState === null) {
            if (isset($plugins['Plugin'][$pluginName])) {
                unset($plugins['Plugin'][$pluginName]);
            }
        }

        return file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function view(?string $pluginName = null): void
    {
        $pluginInfo = Cupcake::pluginInfo($pluginName);

        $this->set(compact('pluginInfo'));
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function enable(?string $pluginName = null): void
    {
        if ($this->_setPluginState($pluginName, true)) {
            $this->Flash->success(__d('admin', 'Plugin {0} enabled', $pluginName));
        }

        //$this->setAction('view', $pluginName);
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function disable(?string $pluginName = null): void
    {
        if ($this->_setPluginState($pluginName, false)) {
            $this->Flash->success(__d('admin', 'Plugin {0} disabled', $pluginName));
        }

        //$this->setAction('view', $pluginName);
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function uninstall(?string $pluginName = null): void
    {
        if ($this->_setPluginState($pluginName, false)) {
            $this->Flash->success(__d('admin', 'Plugin {0} uninstalled', $pluginName));
        }

        //$this->setAction('view', $pluginName);
        $this->redirect(['action' => 'index']);
    }
}
