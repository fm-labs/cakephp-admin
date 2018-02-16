<?php

namespace Backend;

use Backend\Service\ServiceRegistry;
use Banana\Exception\ClassNotFoundException;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * Class Backend
 *
 * @package Backend
 */
class Backend implements EventListenerInterface
{

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

    public function implementedEvents()
    {
        return ['Banana.startup' => 'startup'];
    }

    public function startup(Event $event)
    {
        $this->loadRoutes();
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

    public function loadedServices()
    {
        return $this->services->loaded();
    }

    public function service($name)
    {
        return $this->services->get($name);
    }

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
     */
    public function initializeServices()
    {
        foreach ($this->services->loaded() as $service) {
            $this->services->{$service}->initialize();
        }
    }

    /**
     * Load backend routes
     */
    public function loadRoutes()
    {
        Router::routes(); // <-- required workaround. need to call routes() first, otherwise all existing routes are vanished
        Router::scope('/admin', ['prefix' => 'admin'], function(RouteBuilder $routes) {
            EventManager::instance()->dispatch(new \Backend\Event\RouteBuilderEvent('Backend.Routes.build', $routes));
        });
    }
}
