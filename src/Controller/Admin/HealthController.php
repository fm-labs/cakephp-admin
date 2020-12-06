<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

use Cake\Core\Configure;
use Cupcake\Cupcake;
use Cupcake\Health\HealthStatus;

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
            $checks = Cupcake::doFilter('health_checks_init', []);
            $hm = new \Cupcake\Health\HealthManager($checks);

            $this->dispatchEvent('Health.beforeCheck', [], $hm);

            $hm->addCheck('sys_php_version', [
                'category' => 'system',
                'callback' => function () {
                    if (version_compare(PHP_VERSION, '7.2.0') < 0) {
                        return 'Your PHP version must be equal or higher than 7.2.0 to use CakePHP.';
                    }

                    return HealthStatus::ok(__('You are using supported PHP version {0}', PHP_VERSION));
                },
            ])
            ->addCheck('sys_php_ext_intl', [
                'category' => 'system',
                'callback' => function () {
                    if (!extension_loaded('intl')) {
                        return 'You must enable the intl extension to use CakePHP.';
                    }

                    if (version_compare(INTL_ICU_VERSION, '50.1', '<')) {
                        return 'ICU >= 50.1 is needed to use CakePHP. Please update the `libicu` package of your system.';
                    }

                    return HealthStatus::ok(__('You are using supported ICU version {0}', INTL_ICU_VERSION));
                },
            ])
            ->addCheck('sys_php_ext_mbstring', [
                'category' => 'system',
                'callback' => function () {
                    if (!extension_loaded('mbstring')) {
                        return 'You must enable the mbstring extension to use CakePHP.';
                    }

                    return HealthStatus::ok(__('The PHP extension {0} is loaded', 'mbstring'));
                },
            ])
            ->addCheck('sys_dirs_exist', [
                'category' => 'system',
                'callback' => function () {
                    $dirs = \Cupcake\Cupcake::getSysDirs();

                    $out = '';
                    foreach ($dirs as $dir) {
                        if (!file_exists($dir)) {
                            $out .= sprintf("Not found: %s\n", $dir);
                        }
                    }
                    if ($out) {
                        return HealthStatus::crit($out);
                    }

                    return HealthStatus::ok('All system directories exist');
                },
            ])
            ->addCheck('admin_configuration', [
                'category' => 'configuration',
                'label' => __('Checks if the admin plugin is properly configured'),
                'callback' => function () {
                    return HealthStatus::warn('The admin plugin is not properly configured');
                },
            ])
            ->addCheck('admin_security', [
                'category' => 'configuration',
                'label' => __('Checks if the admin plugin is properly secured'),
                'callback' => function () {
                    if (Configure::read('Admin.Security.enable') !== true) {
                        return HealthStatus::warn('The admin plugin security settings are not enabled');
                    }

                    return HealthStatus::ok('The admin plugin security settings are properly configured');
                },
            ]);

            $hm->check();
            $this->dispatchEvent('Health.afterCheck', [], $hm);
            $health = $hm->getResults();
        } catch (\Exception $ex) {
            $this->Flash->error($ex->getMessage());
        }

        $this->set(compact('health'));
    }
}
