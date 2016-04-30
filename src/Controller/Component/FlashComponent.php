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
     */
    protected $_defaultConfig = [
        'key' => 'flash',
        'element' => 'default',
        'class' => 'default',
        'params' => [],
        'clear' => false, // since 3.1.
        'plugin' => null,
        'elementMap' => []
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    public function set($message, array $options = [])
    {
        $options += $this->config();

        if ($message instanceof \Exception) {
            $options['params'] += ['code' => $message->getCode()];
            $message = $message->getMessage();
        }

        list($plugin, $element) = pluginSplit($options['element']);

        // check map
        if (isset($options['elementMap'][$element])) {
            $options = array_merge($options, $options['elementMap'][$element]);
            list($plugin, $element) = pluginSplit($options['element']);
        }

        // global plugin
        if (!$plugin && $options['plugin']) {
            $plugin = $options['plugin'];
        }

        if ($plugin) {
            $options['element'] = $plugin . '.Flash/' . $element;
        } else {
            $options['element'] = 'Flash/' . $element;
        }

        if (!isset($options['params']['class'])) {
            $options['params'] += ['class' => $options['class']];
        }

        // debug message shows flash key
        //if (Configure::read('debug')) {
        //    $message = sprintf("[%s] %s", $options['key'], $message);
        //}

        $messages = [];
        if ($options['clear'] === false) {
            $messages = $this->_session->read('Flash.' . $options['key']);
        }

        $messages[] = [
            'message' => $message,
            'key' => $options['key'],
            'element' => $options['element'],
            'params' => $options['params']
        ];

        $this->_session->write('Flash.' . $options['key'], $messages);
    }

    public function __call($name, $args)
    {
        $element = Inflector::underscore($name);
        $options = ['element' => $element, 'class' => $element];

        if (count($args) < 1) {
            throw new InternalErrorException('Flash message missing.');
        }

        if (!empty($args[1])) {
            if (!empty($args[1]['plugin'])) {
                $options = ['element' => $args[1]['plugin'] . '.' . $element];
                unset($args[1]['plugin']);
            }
            $options += (array)$args[1];
        }

        $this->set($args[0], $options);
    }
}
