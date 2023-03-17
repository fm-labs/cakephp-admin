<?php

namespace Admin\Core;

use Cake\Routing\RouteBuilder;
use Cake\Utility\Inflector;

class BaseAdminPlugin implements AdminPluginInterface
{
    protected ?string $name = null;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        if ($this->name) {
            return $this->name;
        }
        $parts = explode('\\', static::class);
        array_pop($parts);
        $this->name = implode('/', $parts);

        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getRoutingPrefix(): string
    {
        return Inflector::underscore($this->getName());
    }

    /**
     * @return void
     */
    public function bootstrap(): void
    {
    }

    /**
     * @param \Cake\Routing\RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
    }
}
