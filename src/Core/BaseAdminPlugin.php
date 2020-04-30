<?php

namespace Admin\Core;

use Cake\Routing\RouteBuilder;

class BaseAdminPlugin implements AdminPluginInterface
{
    protected $name;

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
     * @return void
     */
    public function bootstrap(): void
    {
        // TODO: Implement bootstrap() method.
    }

    /**
     * @param \Cake\Routing\RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        // TODO: Implement routes() method.
    }
}
