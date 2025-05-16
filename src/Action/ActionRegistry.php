<?php
declare(strict_types=1);

namespace Admin\Action;

use Admin\Action\Interfaces\ActionInterface;
use Cake\Core\App;
use Cake\Core\ObjectRegistry;
use Generator;
use RuntimeException;

/**
 * Registry of loaded log engines
 */
class ActionRegistry extends ObjectRegistry
{
    /**
     * Resolve a action classname.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class Partial classname to resolve.
     * @return string|false Either the correct classname or false.
     */
    protected function _resolveClassName(string $class): ?string
    {
        if (is_object($class)) {
            return $class;
        }

        return App::className($class, 'Action', 'Action');
    }

    /**
     * Throws an exception when a action is missing.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param string $class The classname that is missing.
     * @param string $plugin The plugin the action is missing in.
     * @return void
     * @throws \RuntimeException
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new RuntimeException(sprintf('Could not load class %s', $class));
    }

    /**
     * Create the action instance.
     *
     * Part of the template method for Cake\Core\ObjectRegistry::load()
     *
     * @param \Admin\Action\Interfaces\ActionInterface|string $class The classname or object to make.
     * @param string $alias The alias of the object.
     * @param array $settings An array of settings to use for the action.
     * @return \Admin\Action\Interfaces\ActionInterface The constructed action class.
     * @throws \RuntimeException when an object doesn't implement the correct interface.
     */
    protected function _create(object|string $class, string $alias, array $config): object
    {
        if (is_callable($class)) {
            $class = $class($alias);
        }

        if (is_object($class)) {
            $instance = $class;
        }

        if (!isset($instance)) {
            $instance = new $class($config);
        }

        if ($instance instanceof ActionInterface) {
            return $instance;
        }

        throw new RuntimeException(
            'Action ' . $alias . ' must implement ActionInterface.',
        );
    }

    /**
     * @param string $className Class or Interface to inherit from
     * @return \Generator
     */
    public function with(string $className = ActionInterface::class): Generator
    {
        foreach ($this->loaded() as $actionName) {
            $action = $this->get($actionName);
            if ($action instanceof $className) {
                yield $action;
            }
        }
    }
}
