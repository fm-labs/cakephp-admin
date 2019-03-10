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
            $plugins[$pluginName] = Banana::pluginInfo($pluginName);
        }

        $this->set('plugins', $plugins);
        $this->set('_serialize', $plugins);
    }
}
