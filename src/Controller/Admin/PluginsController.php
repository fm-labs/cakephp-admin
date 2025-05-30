<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Collection\Collection;
use Cake\Core\Plugin;
use Cake\Http\Response;
use Cupcake\PluginManager;

/**
 * Class PluginsController
 *
 * @package Admin\Controller\Admin
 */
class PluginsController extends AppController
{
    public array $actions = []; //@TODO Disable ActionComponent

    /**
     * Displays information about loaded Cake plugins
     *
     * @return void
     * @TODO Refactor with PluginManager
     */
    public function index(): void
    {
        // find available plugins from known plugin folders
        $plugins = (new Collection([]))
            ->append(PluginManager::findLocalPlugins())
            ->append(PluginManager::findVendorPlugins())
            ->append(PluginManager::findLoadedPlugins())
            ->reduce(function ($plugins, $plugin) {
                if (!isset($plugins[$plugin['name']])) {
                    $plugins[$plugin['name']] = $plugin;
                }

                return $plugins;
            }, []);
        ksort($plugins);

        $plugins = (new Collection(array_values($plugins)))
            ->map(function ($plugin) {
                $pluginInfo = PluginManager::getPluginInfo($plugin['name']);
                //$plugin['loaded'] = Plugin::isLoaded($plugin['name']);
                //$plugin['composer_name'] = PluginManager::getComposerPackageName($plugin['name']);
                //$plugin['version'] = PluginManager::getInstalledComposerPackageVersion($plugin['name']);
                return array_merge($plugin, $pluginInfo);
            })
            ->sortBy('name')
            ->sortBy('loaded');

        $this->set('plugins', $plugins->toArray());
        $this->set('loaded', Plugin::loaded());
        $this->viewBuilder()->setOption('serialize', ['plugins']);
    }

    /**
     * @param string $pluginName Plugin name
     * @param bool|null $newState Plugin enabled state
     * @return int|false
     */
    protected function _setPluginState(string $pluginName, ?bool $newState)
    {
        $pluginsFile = CONFIG . DS . 'local/plugins.php';

        $plugins = [];
        if (file_exists($pluginsFile)) {
            $plugins = include $pluginsFile;
        }

        if ($newState === null) {
            if (isset($plugins['Plugin'][$pluginName])) {
                unset($plugins['Plugin'][$pluginName]);
            }
        }
//        elseif (!$newState) {
////            if (Configure::check('Plugin.' . $pluginName)) {
////                $this->Flash->error(__("Core plugin {0} can not be disabled", $pluginName));
////                return false;
////            }
//            $plugins['Plugin'][$pluginName] = false;
//        } else {
//            $plugins['Plugin'][$pluginName] = ['bootstrap' => $newState, 'routes' => $newState];
//        }
        else {
            $plugins['Plugin'][$pluginName] = $newState;
        }

        return file_put_contents($pluginsFile, "<?php\n" . 'return ' . var_export($plugins, true) . ';' . "\n");
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return void
     */
    public function view(?string $pluginName = null): void
    {
        $pluginInfo = PluginManager::getPluginInfo($pluginName);
        $readme = PluginManager::getReadme($pluginName);

        $this->set(compact('pluginInfo', 'readme'));
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return \Cake\Http\Response|null
     */
    public function enable(?string $pluginName = null): ?Response
    {
        if ($this->_setPluginState($pluginName, true)) {
            $this->Flash->success(__d('admin', 'Plugin {0} enabled', $pluginName));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return \Cake\Http\Response|null
     */
    public function disable(?string $pluginName = null): ?Response
    {
        if ($this->_setPluginState($pluginName, false)) {
            $this->Flash->success(__d('admin', 'Plugin {0} disabled', $pluginName));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param string|null $pluginName Plugin name
     * @return \Cake\Http\Response|null
     */
    public function uninstall(?string $pluginName = null): ?Response
    {
        if ($this->_setPluginState($pluginName, false)) {
            $this->Flash->success(__d('admin', 'Plugin {0} uninstalled', $pluginName));
        }

        return $this->redirect(['action' => 'index']);
    }
}
