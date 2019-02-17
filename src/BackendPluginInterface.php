<?php

namespace Backend;

use Cake\Routing\RouteBuilder;

interface BackendPluginInterface
{
    public function backendBootstrap(Backend $backend);
    public function backendRoutes(RouteBuilder $routes);
}
