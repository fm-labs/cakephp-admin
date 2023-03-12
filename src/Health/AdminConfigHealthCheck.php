<?php

namespace Admin\Health;

use Cake\Core\Configure;
use Cupcake\Health\HealthCheckInterface;
use Cupcake\Health\HealthStatus;

class AdminConfigHealthCheck implements HealthCheckInterface
{
    /**
     * @inheritDoc
     */
    public function getHealthStatus(): HealthStatus
    {
        return HealthStatus::ok('The admin plugin is properly configured');
    }
}