<?php
declare(strict_types=1);

namespace Backend;

use Backend\Service\ServiceRegistry;
use Banana\Exception\ClassNotFoundException;
use Banana\Menu\Menu;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventManager;

/**
 * Class Backend
 *
 * @package Backend
 */
class Backend
{
    public static $urlPrefix = 'admin';

    use InstanceConfigTrait;

    /**
     * The service services.
     *
     * @var \Backend\Service\ServiceRegistry
     */
    protected $services;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'services' => [
            'Backend.Crud' => true,
            'Backend.Publishable' => false,
            'Backend.Tree' => true,
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
     * @return \Banana\Menu\Menu|array
     */
    public static function getMenu($menuId)
    {
        //@TODO Cache
        $menu = new Menu();
        $event = EventManager::instance()->dispatch(new Event('Backend.Menu.build.' . $menuId, null, ['menu' => $menu]));

        return $event->getData('menu');
        //$event = EventManager::instance()->dispatch(new Event('Backend.Menu.init', null, ['menus' => [], 'menuId' => $menuId]));
        //return (isset($event->getData('menus')[$menuId])) ? $event->getData('menus')[$menuId] : [];
    }

    /**
     * @return string Backend version number
     */
    public static function version()
    {
        if (!isset(static::$_version)) {
            //phpcs::disable
            static::$_version = @file_get_contents(Plugin::path('Backend') . DS . 'VERSION.txt');
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
     * @return \Backend\Service\ServiceRegistry
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
