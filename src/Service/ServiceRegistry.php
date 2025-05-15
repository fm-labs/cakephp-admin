<?php
declare(strict_types=1);

namespace Admin\Service;

use Admin\AdminService;
use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use RuntimeException;

class ServiceRegistry extends ObjectRegistry
{
    use EventDispatcherTrait;

    /**
     * Constructor
     *
     * @param \Cake\Event\EventManager $eventManager EventManager instances that services subscribe to.
     * Typically this is the global event manager.
     */
    public function __construct(EventManager $eventManager)
    {
        $this->setEventManager($eventManager);
    }

    /**
     * Resolve a service class name.
     *
     * Part of the template method for Cake\Utility\ObjectRegistry::load()
     *
     * @param string $class Partial class name to resolve.
     * @return string|false Either the correct class name or false.
     */
    protected function _resolveClassName(string $class): ?string
    {
        return App::className($class, 'Service', 'Service');
    }

    /**
     * Throws an exception when a component is missing.
     *
     * Part of the template method for Cake\Utility\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the component is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new RuntimeException("Unable to find '$class' service.");
    }

    /**
     * Create the services instance.
     *
     * Part of the template method for Cake\Utility\ObjectRegistry::load()
     *
     * @param string $class The classname to create.
     * @param string $alias The alias of the service.
     * @param array $config An array of config to use for the service.
     * @return \Admin\AdminService The constructed service class.
     */
    protected function _create(string|object $class, string $alias, array $config): AdminService
    {
        $instance = new $class($this, $config);
        $this->getEventManager()->on($instance);

        return $instance;
    }
}
