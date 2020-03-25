<?php
declare(strict_types=1);

namespace Backend;

use Cake\Routing\RouteBuilder;

/**
 * @deprecated
 */
interface BackendPluginInterface
{
    /**
     * @param \Backend\Backend $backend Backend object
     * @return void
     */
    public function backendBootstrap(Backend $backend);

    /**
     * @param \Cake\Routing\RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function backendRoutes(RouteBuilder $routes);
}
