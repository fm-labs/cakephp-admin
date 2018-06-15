<?php
use Cake\Core\Configure;
use Cake\Core\Plugin;

/**
 * Automatically load app's backend configuration
 *
 * Copy backend.default.php to your app's config folder,
 * rename to backend.php and adjust contents
 */
Configure::load('Backend.backend');

Plugin::load('Bootstrap');
//Plugin::load('User');

if (!Plugin::loaded('User')) {
    throw new \Cake\Core\Exception\MissingPluginException(['plugin' => 'User']);
}

if (Plugin::loaded('Banana')) {
    //\Banana\Banana::getInstance()->pluginManager()->register('Bootstrap');
    //   oder
    //\Banana\Banana::plugin('Backend', new \Backend\BackendPlugin());
    //   oder
    //\Banana\Plugin\PluginManager::config('Backend', ['classname' => 'Backend.Backend']);
}