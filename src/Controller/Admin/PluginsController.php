<?php

namespace Backend\Controller\Admin;

use Banana\Banana;
use Cake\Core\App;
use Cake\Core\Plugin;

class PluginsController extends AppController
{
    /**
     * Displays information about loaded Cake plugins
     */
    public function index()
    {
        $plugins = [];
        foreach (Plugin::loaded() as $pluginName) {
            $plugins[$pluginName] = Banana::pluginInfo($pluginName);
        }

        // find available plugins from known plugin folders
        $installed = [];
        foreach (App::path('Plugin') as $path) {
            //debug($path);
            $files = scandir($path);
            //debug($files);
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
                $installed[$f] = Banana::pluginInfo($f);
                */
            }
        }

        $this->set('plugins', $plugins);
        $this->set('installed', $installed);
        $this->set('_serialize', $plugins);
    }

    public function enable($pluginName)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include($pluginsFile);
        }

        $plugins['Plugin'][$pluginName] = ['bootstrap' => true, 'routes' => true];

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName enabled");
        $this->redirect(['action' => 'index']);
    }

    public function disable($pluginName)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include($pluginsFile);
        }

        $plugins['Plugin'][$pluginName] = ['bootstrap' => false, 'routes' => false];

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName disabled");
        $this->redirect(['action' => 'index']);
    }

    public function uninstall($pluginName)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include($pluginsFile);
        }

        if (isset($plugins['Plugin'][$pluginName])) {
            unset($plugins['Plugin'][$pluginName]);
        }

        file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");

        $this->Flash->success("Plugin $pluginName disabled");
        $this->redirect(['action' => 'index']);
    }
}
