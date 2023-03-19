<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cupcake\Cupcake;
use Cupcake\Health\Check\CakePhpHealthCheck;
use Cupcake\Health\Check\DatabaseConnectionCheck;
use Cupcake\Health\Check\PhpExtensionCheck;
use Cupcake\Health\Check\PhpVersionCheck;
use Cupcake\Health\Check\SysDirCheck;
use Cupcake\Health\HealthManager;

/**
 * Class HealthController
 *
 * @package Admin\Controller\Admin
 */
class HealthController extends AppController
{
    /**
     * @return void
     */
    public function index(): void
    {
        $health = null;
        try {
            //@todo Move to configuration
            $checks = [
                'system:php_version' => new PhpVersionCheck(),
                'system:php_ext' => new PhpExtensionCheck(),
                'system:cakephp' => new CakePhpHealthCheck(),
                'system:dirs' => new SysDirCheck(),
                'app:db_connection' => new DatabaseConnectionCheck()
            ];

            $checks = Cupcake::doFilter('health_checks_init', $checks);
            $hm = new HealthManager($checks);
//            $hm
//                ->addCheck('php_version', new PhpVersionCheck())
//                ->addCheck('php_extension', new PhpExtensionCheck())
//                ->addCheck('sys_dirs_exist', new SysDirCheck());

            $this->dispatchEvent('Health.beforeCheck', [], $hm);
            $hm->check();
            $this->dispatchEvent('Health.afterCheck', [], $hm);
            $health = $hm->getResults();
        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
        }

        $this->set(compact('health'));
    }
}
