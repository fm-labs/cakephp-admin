<?php
declare(strict_types=1);

namespace Admin;

use Admin\Service\ServiceRegistry;
use Cake\Core\InstanceConfigTrait;
use Cake\Event\EventManager;

/**
 * Class ServiceManager
 *
 * @package Admin
 * @deprecated
 */
class ServiceManager
{
    use InstanceConfigTrait;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'services' => [
            //'Admin.Crud' => true,
            //'Admin.Publish' => false,
            //'Admin.Tree' => true,
        ],
    ];

    /**
     * @var array Object storage
     */
    protected static $_objects = [];

    /**
     * The service services.
     *
     * @var \Admin\Service\ServiceRegistry
     */
    protected $services;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new ServiceRegistry(EventManager::instance());
    }

    /**
     * @param string $name Service name
     * @param object $object Service object
     * @return $this
     */
    public function register(string $name, object $object)
    {
        static::$_objects[$name] = $object;

        return $this;
    }

    /**
     * @param string $name Service name
     * @return mixed|null
     */
    public function get(string $name)
    {
        if (isset(static::$_objects[$name])) {
            return static::$_objects[$name];
        }

        return null;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
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
     * @param string $name Service name
     * @return object
     */
    public function service(string $name): object
    {
        return $this->services->get($name);
    }

    /**
     * @return array
     */
    public function loadedServices(): array
    {
        return $this->services->loaded();
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function loadServices(): void
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
     */
    protected function initializeServices(): void
    {
        foreach ($this->services->loaded() as $service) {
            $this->services->{$service}->initialize();
        }
    }
}
