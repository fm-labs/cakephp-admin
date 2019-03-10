<?php
namespace Backend\Action;

use Backend\Action\Interfaces\ActionInterface;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use RuntimeException;

/**
 * Registry of loaded log engines
 */
class ActionRegistry extends ObjectRegistry
{
    protected $_controller;

    public function __construct(Controller $controller)
    {
        $this->_controller = $controller;
    }

    /**
     * Resolve a checkout step classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return string|false Either the correct classname or false.
     */
    protected function _resolveClassName($class)
    {
        if (is_object($class)) {
            return $class;
        }

        return App::className($class, 'Action', 'Action');
    }

    /**
     * Throws an exception when a checkout step is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the checkout step is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError($class, $plugin)
    {
        throw new RuntimeException(sprintf('Could not load class %s', $class));
    }

    /**
     * Create the checkout step instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string|\Psr\Log\LoggerInterface $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the checkout step.
     * @return \Psr\Log\LoggerInterface The constructed checkout step class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create($class, $alias, $settings)
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($this->_controller, $settings);
        }

        if ($instance instanceof ActionInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'Action ' . $alias . ' must implement ActionInterface.'
        );
    }
}
