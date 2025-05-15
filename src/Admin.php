<?php
declare(strict_types=1);

namespace Admin;

use Admin\Core\AdminPluginCollection;
use Admin\Core\AdminPluginInterface;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cupcake\Cupcake;
use Cupcake\Exception\ClassNotFoundException;
use Cupcake\Menu\MenuItemCollection;

/**
 * Class Admin
 *
 * @package Admin
 */
class Admin
{
    public static $urlPrefix = 'admin';

    /**
     * @var \Admin\Core\AdminPluginCollection
     */
    protected static $plugins;

    /**
     * @var string
     */
    protected static $_version = null;

    /**
     * @var array
     * @deprecated
     */
    protected static $_listeners = [];

    /**
     * Set the admin routing prefix.
     * Defaults to 'admin'.
     *
     * @param string $prefix The URL routing prefix.
     * @return void
     */
    public static function setUrlPrefix(string $prefix): void
    {
        static::$urlPrefix = trim(trim($prefix, '/'));
    }

    /**
     * Get the shared plugin collection.
     *
     * This method should generally not be used during application
     * runtime as plugins should be set during Application startup.
     *
     * @return \Admin\Core\AdminPluginCollection|\Iterator
     */
    public static function getPlugins(): AdminPluginCollection
    {
        if (!isset(static::$plugins)) {
            static::$plugins = new AdminPluginCollection();
        }

        return static::$plugins;
    }

    /**
     * @param \Admin\Core\AdminPluginInterface $plugin The admin plugin.
     * @return void
     */
    public static function addPlugin(AdminPluginInterface $plugin): void
    {
        static::getPlugins()->add($plugin);
    }

    /**
     * @param string $menuName Admin menu name
     * @return \Cupcake\Menu\MenuItemCollection
     */
    public static function getMenu(string $menuName): MenuItemCollection
    {
        //@TODO Cache

        // 1. read from configuration
        $items = (array)Configure::read('Admin.Menu.' . $menuName);
        // 2. cc filter
        $items = Cupcake::doFilter('admin_menu_init', $items, ['name' => $menuName]);
        // 3. build MenuCollection and trigger menu build event
        $menu = new MenuItemCollection($items);
        $event = new Event('Admin.Menu.build.' . $menuName, null, ['menu' => $menu]);
        $event = EventManager::instance()->dispatch($event);

        return $event->getData('menu');
    }

    /**
     * @return string Admin version number
     */
    public static function version()
    {
        $version = Configure::read('Admin.version');
        if ($version !== null) {
            return $version;
        }

        $filePath = Plugin::path('Admin') . DS . 'VERSION.txt';
        if (is_file($filePath)) {
            //phpcs::disable
            $version = @file_get_contents(Plugin::path('Admin') . DS . 'VERSION.txt');
            //phpcs::enable
            Configure::write('Admin.version', $version);
            return $version;
        }

        return 'unknown';
    }

    /**
     * @param string $type Listener type
     * @param string $listenerClass Listener class
     * @return void
     * @deprecated
     */
    public static function registerListener(string $type, string $listenerClass): void
    {
        \Cake\Core\deprecationWarning('Admin::registerListener() is deprecated.');
        if (!isset(static::$_listeners[$type])) {
            static::$_listeners[$type] = [];
        }

        if (!class_exists($listenerClass)) {
            throw new ClassNotFoundException($listenerClass);
        }

        static::$_listeners[$type][] = $listenerClass;
    }

    /**
     * @param string $type Listener type
     * @return array
     * @deprecated
     */
    public static function getListeners(string $type): array
    {
        \Cake\Core\deprecationWarning('Admin::getListeners() is deprecated.');
        if (!isset(static::$_listeners[$type])) {
            return [];
        }

        return static::$_listeners[$type];
    }
}
