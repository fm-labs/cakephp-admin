<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

use Cake\Controller\Component\FlashComponent as CakeFlashComponent;

/**
 * Class FlashComponent
 *
 * @package Admin\Controller\Component
 */
class FlashComponent extends CakeFlashComponent
{
    /**
     * Default configuration
     *
     * Added config parameters:
     *
     * - plugin: Set plugin prefix globally for all flash messages, except explicitly overridden
     * - elementMap: List of config parameter for element types
     *      'elementMap' => [
     *          'error' => ['class' => 'danger'],
     *          'info' => ['element' => 'default']
     *      ]
     *
     * @var array
    protected $_defaultConfig = [
        'key' => 'flash',
        'element' => 'default',
        'class' => 'default',
        'params' => [],
        'clear' => false, // since 3.1.
        'plugin' => null,
        'elementMap' => []
    ];
     */

    /**
     * Flash success message
     *
     * @param string $msg Flash message
     * @param array $options Flash options
     * @return void
     */
    public function success($msg, array $options = [])
    {
        $options += ['element' => 'Admin.success'];
        $this->set($msg, $options);
    }

    /**
     * Flash warning message
     *
     * @param string $msg Flash message
     * @param array $options Flash options
     * @return void
     */
    public function warning($msg, array $options = [])
    {
        $options += ['element' => 'Admin.warning'];
        $this->set($msg, $options);
    }

    /**
     * Flash error message
     *
     * @param string $msg Flash message
     * @param array $options Flash options
     * @return void
     */
    public function error($msg, array $options = [])
    {
        $options += ['element' => 'Admin.error'];
        $this->set($msg, $options);
    }

    /**
     * Flash info message
     *
     * @param string $msg Flash message
     * @param array $options Flash options
     * @return void
     */
    public function info($msg, array $options = [])
    {
        $options += ['element' => 'Admin.info'];
        $this->set($msg, $options);
    }
}
