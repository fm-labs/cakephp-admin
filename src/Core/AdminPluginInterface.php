<?php
declare(strict_types=1);

namespace Admin\Core;

use Cake\Routing\RouteBuilder;

interface AdminPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getRoutingPrefix(): string;

    /**
     * @return void
     */
    public function bootstrap(): void;

    /**
     * @param \Cake\Routing\RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function routes(RouteBuilder $routes): void;
}
