<?php
declare(strict_types=1);

namespace Admin\Health;

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
