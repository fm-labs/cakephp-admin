<?php

namespace Backend\Controller\Admin;

use Banana\Banana;
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
            $plugins[$pluginName] = Banana::getInstance()->pluginManager()->getInfo($pluginName);
        }

//        foreach (Plugin::loaded() as $pluginName) {
//            /*
//            $plugins[$pluginName] = [
//                'path' => Plugin::path($pluginName),
//                'config' => Plugin::configPath($pluginName),
//                'class' => Plugin::classPath($pluginName)
//            ];
//            */
//            $plugins[$pluginName] = Plugin::path($pluginName);
//        }

        $this->set('plugins', $plugins);
        $this->set('_serialize', $plugins);
    }
}
