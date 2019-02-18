<?php

namespace Backend;

use Cake\Routing\RouteBuilder;

interface BackendPluginInterface
{
    /**
     * @param Backend $backend Backend object
     * @return void
     */
    public function backendBootstrap(Backend $backend);

    /**
     * @param RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function backendRoutes(RouteBuilder $routes);
}
