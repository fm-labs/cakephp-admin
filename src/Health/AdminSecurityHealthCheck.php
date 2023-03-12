<?php

namespace Admin\Health;

use Cake\Core\Configure;
use Cupcake\Health\HealthCheckInterface;
use Cupcake\Health\HealthStatus;

class AdminSecurityHealthCheck implements HealthCheckInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): HealthStatus
    {
        if ((bool)Configure::read('Admin.Security.enabled') !== true) {
            return HealthStatus::warn('The admin plugin security settings are not enabled');
        }

        return HealthStatus::ok('The admin plugin security settings are properly configured');
    }
}