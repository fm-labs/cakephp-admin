<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 2/7/15
 * Time: 11:11 AM
 */

namespace Backend\Controller\Component;

use Cake\Controller\Component\FlashComponent as CakeFlashComponent;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;

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

    public function success($msg, array $options = []) {
        $options += ['element' => 'Backend.success'];
        $this->set($msg, $options);
    }

    public function warning($msg, array $options = []) {
        $options += ['element' => 'Backend.warning'];
        $this->set($msg, $options);
    }

    public function error($msg, array $options = []) {
        $options += ['element' => 'Backend.error'];
        $this->set($msg, $options);
    }

    public function info($msg, array $options = []) {
        $options += ['element' => 'Backend.info'];
        $this->set($msg, $options);
    }
}
