<?php
declare(strict_types=1);

namespace Admin;

use Admin\Core\AdminPluginCollection;
use Admin\Core\AdminPluginInterface;
use Admin\Service\ServiceRegistry;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cupcake\Exception\ClassNotFoundException;
use Cupcake\Menu\Menu;

/**
 * Class Admin
 *
 * @package Admin
 */
class Admin
{
    use InstanceConfigTrait;

    public static $urlPrefix = 'admin';

    /**
     * The service services.
     *
     * @var \Admin\Service\ServiceRegistry
     */
    protected $services;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'services' => [
            'Admin.Crud' => true,
            'Admin.Publish' => false,
            'Admin.Tree' => true,
        ],
    ];

    /**
     * @var string
     */
    protected static $_version = null;

    protected static $_listeners = [];

    /**
     * @var array Object storage
     */
    protected static $_objects = [];

    /**
     * @var array Hook callback storage
     */
    protected static $_hooks = [];

    /**
     * @var \Admin\Core\AdminPluginCollection
     */
    protected static $plugins;

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

    public static function addFilter($name, $cb)
    {
        //@TODO Implement Me
    }

    public static function applyFilter($name)
    {
        //@TODO Implement Me
    }

    public static function addHook($name, $cb)
    {
        //@TODO Implement Me
    }

    public static function applyHook($name)
    {
        //@TODO Implement Me
    }

    public static function getMailer()
    {
        //@TODO Implement Me
    }

    /**
     * @return \Cupcake\Menu\Menu|array
     */
    public static function getMenu($menuId)
    {
        //@TODO Cache
        $menu = new Menu();
        $event = EventManager::instance()->dispatch(new Event('Admin.Menu.build.' . $menuId, null, ['menu' => $menu]));

        return $event->getData('menu');
        //$event = EventManager::instance()->dispatch(new Event('Admin.Menu.init', null, ['menus' => [], 'menuId' => $menuId]));
        //return (isset($event->getData('menus')[$menuId])) ? $event->getData('menus')[$menuId] : [];
    }

    /**
     * @return string Admin version number
     */
    public static function version()
    {
        if (!isset(static::$_version)) {
            //phpcs::disable
            static::$_version = @file_get_contents(Plugin::path('Admin') . DS . 'VERSION.txt');
            //phpcs::enable
        }

        return static::$_version;
    }

    /**
     * @deprecated
     * @return void
     */
    public static function registerListener($type, $listenerClass)
    {
        if (!isset(static::$_listeners[$type])) {
            static::$_listeners[$type] = [];
        }

        if (!class_exists($listenerClass)) {
            throw new ClassNotFoundException($listenerClass);
        }

        static::$_listeners[$type][] = $listenerClass;
    }

    /**
     * @deprecated
     * @return array
     */
    public static function getListeners($type)
    {
        if (!isset(static::$_listeners[$type])) {
            return [];
        }

        return static::$_listeners[$type];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new ServiceRegistry(EventManager::instance());
    }

    public function register($name, $object)
    {
        static::$_objects[$name] = $object;
    }

    public function get($name)
    {
        if (isset(static::$_objects[$name])) {
            return static::$_objects[$name];
        }

        return null;
    }

    public function hook($name, callable $callback)
    {
        static::$_hooks[$name][] = $callback;
    }

    public function run()
    {
        //$this->loadRoutes();
        $this->loadServices();
        $this->initializeServices();
    }

    /**
     * Fetch the ServiceRegistry
     *
     * @return \Admin\Service\ServiceRegistry
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * @deprecated Use services()->loaded() instead
     */
    public function loadedServices()
    {
        return $this->services->loaded();
    }

    /**
     * @deprecated Use service()->get()
     */
    public function service($name)
    {
        return $this->services->get($name);
    }

    /**
     * @TODO Make method protected
     */
    public function loadServices()
    {
        foreach ($this->getConfig('services') as $service => $enabled) {
            [$service, $enabled] = is_numeric($service) ? [$enabled, true] : [$service, $enabled];
            if ($enabled) {
                $this->services->load($service);
            }
        }
    }

    /**
     * Call the initialize method on all the loaded services.
     *
     * @return void
     * @TODO Make method protected
     */
    public function initializeServices()
    {
        foreach ($this->services->loaded() as $service) {
            $this->services->{$service}->initialize();
        }
    }
}
