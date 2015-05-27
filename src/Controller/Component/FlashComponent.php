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
     * @var array
     */
    protected $_defaultConfig = [
        'key' => 'flash',
        'element' => 'default',
        'class' => 'default',
        'params' => []
    ];

    public function set($message, array $options = [])
    {
        $options += $this->config();

        if ($message instanceof \Exception) {
            $options['params'] += ['code' => $message->getCode()];
            $message = $message->getMessage();
        }

        list($plugin, $element) = pluginSplit($options['element']);

        if ($plugin) {
            $options['element'] = $plugin . '.Flash/' . $element;
        } elseif (isset($options['plugin'])) {
            $options['element'] = $options['plugin'] . '.Flash/' . $element;
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

        $this->_session->write('Flash.' . $options['key'], [
            'message' => $message,
            'key' => $options['key'],
            'element' => $options['element'],
            'params' => $options['params']
        ]);
    }

    public function __call($name, $args)
    {
        $_name = Inflector::underscore($name);
        $options = ['element' => $_name, 'class' => $_name];

        if (count($args) < 1) {
            throw new InternalErrorException('Flash message missing.');
        }

        if (!empty($args[1])) {
            $options += (array)$args[1];
        }

        $this->set($args[0], $options);
    }
}
