<?php
declare(strict_types=1);

namespace Admin;

use Cake\Routing\RouteBuilder;

/**
 * @deprecated
 */
interface AdminPluginInterface
{
    /**
     * @param \Admin\Admin $admin Admin object
     * @return void
     */
    public function adminBootstrap(Admin $admin);

    /**
     * @param \Cake\Routing\RouteBuilder $routes RouterBuilder object
     * @return void
     */
    public function adminRoutes(RouteBuilder $routes);
}
