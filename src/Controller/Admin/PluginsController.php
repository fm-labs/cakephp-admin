<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cupcake\Cupcake;

class PluginsController extends AppController
{
    public $actions = []; //@TODO Disable ActionComponent

    public $modelClass = false;

    /**
     * Displays information about loaded Cake plugins
     *
     * @return void
     */
    public function index()
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
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function enable(?string $pluginName = null)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include $pluginsFile;
        }

        $plugins['Plugin'][$pluginName] = ['bootstrap' => true, 'routes' => true];

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName enabled");
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function disable(?string $pluginName = null)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include $pluginsFile;
        }

        $plugins['Plugin'][$pluginName] = ['bootstrap' => false, 'routes' => false];

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName disabled");
        $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function uninstall(?string $pluginName = null)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include $pluginsFile;
        }

        if (isset($plugins['Plugin'][$pluginName])) {
            unset($plugins['Plugin'][$pluginName]);
        }

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName disabled");
        $this->redirect(['action' => 'index']);
    }
}
