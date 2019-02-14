<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class ToastrHelper
 * @package Backend\View\Helper
 *
 * @property HtmlHelper $Html
 */
class ToastrHelper extends Helper
{
    public $helpers = ['Html'];

    public function initialize(array $config)
    {
        $this->Html->css('/backend/libs/toastr/toastr.css', ['block' => true]);
        $this->Html->script('/backend/libs/toastr/toastr.min.js', ['block' => true]);

        // set global toastr options
        // @TODO Make toastr js global options configurable
        $toastrOptions = 'toastr.options.positionClass = "toast-top-center";';
        $toastrOptions .= 'toastr.options.newestOnTop = false;';
        $toastrOptions .= 'toastr.options.progressBar = true;';
        $this->Html->scriptBlock($toastrOptions, ['safe' => false, 'block' => true]);
    }

    /**
     * Automatically render 'backend' and default flash messages as toastr messages.
     *
     * @param Event $event The event object
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        $this->flash('backend', ['block' => true]);
        $this->flash('flash', ['block' => true]);
    }

    /**
     * Render flash messages as toastr messages
     * Grabs messages from session and injects a toastr javascript 'script' block.
     *
     * @param string $key The flash message key
     * @return null|string The rendered javascript (if the 'block' option is not used)
     */
    public function flash($key = 'flash', array $options = [])
    {
        $options += ['block' => null];
        $messages = (array)$this->request->session()->read('Flash.' . $key);

        $scriptTemplate = 'toastr.%s("%s", "%s", %s);';
        $script = "";
        foreach ($messages as $message) {
            // Toastr default options can be found in source (most of the options are undocumented in the README file)
            // @see https://github.com/CodeSeven/toastr/blob/master/toastr.js ~ line 153
            $toastr = [
                //'toastClass' => 'toast',
                //'containerId' => 'toast-container',
                //'target' => 'body',
                //'iconClass' => 'toast-info',
                //'positionClass' => 'toast-top-center',
                //'titleClass' => 'toast-title',
                //'messageClass' => 'toast-message',
                //'closeClass' => 'toast-close-button',
                //'progressClass' => 'toast-progress'

                //'showMethod' => 'fadeIn', //fadeIn, slideDown, and show are built into jQuery
                //'showDuration' => 300,
                //'showEasing' => 'swing', // swing | linear
                //'hideMethod' => 'fadeOut',
                //'hideDuration' => 300,
                //'hideEasing' => 'swing', // swing | linear
                //'closeMethod' => 'fadeOut',
                //'closeDuration' => 300,
                //'closeEasing' => 'swing', // swing | linear
                //'closeOnHover' => true,
                //'closeHtml' => '<button><i class="icon-off"></i></button>',
                //'closeButton' => true,

                //'debug' => false,
                //'escapeHtml' => false,
                //'rtl' => false,
                //'preventDuplicates' => false,
                //'tapToDismiss' => true,
                //'newestOnTop' => false, // Default: True
                //'progressBar' => true, // Default: False

                'timeOut' => 10000, // How long the toast will display without user interaction. Default: 5000
                'extendedTimeOut' => 2000, // How long the toast will display after a user hovers over it. Default: 1000
            ];

            $supportedMethods = ['info', 'error', 'warning', 'success'];
            $method = null;

            // detect flash method from params
            if ($method === null && isset($message['params']['class'])) {
                $method = $message['params']['class'];
            }

            // detect flash method from element name
            // the plugin part and possible sub paths are stripped
            // the element name must match a supported toastr method
            if ($method === null) {
                list($plugin, $element) = pluginSplit($message['element']);
                if (strrpos($element, '/') !== false) {
                    $element = substr($element, strrpos($element, '/') + 1);
                }
                $method = $element;
            }

            if (!in_array($method, $supportedMethods)) {
                $method = 'info';
            }

            $title = (isset($message['params']['title'])) ? $message['params']['title'] : Inflector::humanize($method);
            $script .= sprintf($scriptTemplate, $method, $message['message'], $title, json_encode($toastr));

        }
        $this->request->session()->delete('Flash.' . $key);
        return $this->Html->scriptBlock($script, ['safe' => false, 'block' => $options['block']]);
    }

    public function implementedEvents()
    {
        return [
            'View.beforeLayout' => 'beforeLayout'
        ];
    }
}
