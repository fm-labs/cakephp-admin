<?php

namespace Backend;

use Backend\Service\ServiceRegistry;
use Banana\Application;
use Banana\Exception\ClassNotFoundException;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Cake\Event\EventManager;

/**
 * Class Backend
 *
 * @package Backend
 */
class Backend
{
    static public $urlPrefix = 'admin';

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
        ]
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
     * @return string Backend version number
     */
    public static function version()
    {
        if (!isset(static::$_version)) {
            static::$_version = @file_get_contents(Plugin::path('Backend') . DS . 'VERSION.txt');
        }

        return static::$_version;
    }

    /**
     * @deprecated
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
        foreach ($this->config('services') as $service => $enabled) {
            list($service, $enabled) = (is_numeric($service)) ? [$enabled, true] : [$service, $enabled];
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
