<?php
declare(strict_types=1);

namespace Backend\Controller\Component;

use Cake\Controller\Component\FlashComponent as CakeFlashComponent;

/**
 * Class FlashComponent
 *
 * @package Backend\Controller\Component
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
        $options += ['element' => 'Backend.success'];
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
        $options += ['element' => 'Backend.warning'];
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
        $options += ['element' => 'Backend.error'];
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
        $options += ['element' => 'Backend.info'];
        $this->set($msg, $options);
    }
}
