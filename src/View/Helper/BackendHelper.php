<?php

namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class BackendHelper
 *
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 */
class BackendHelper extends Helper
{
    public static $scriptBlockBottom = "scriptBottom";

    public $helpers = ['Html'];

    protected $_defaultConfig = [
        //'autoload_scripts' => ['jquery', 'semanticui'],
        //'autoload_css' => []
    ];

    /**
     * Unstable
     * @var array
     */
    protected $_scripts = [
        'jquery' => 'Backend.jquery/jquery-1.11.2.min',
        'jqueryui' => ['Backend.jquery/jquery-ui.min' => ['jquery']],
        'chosen' => ['Backend.chosen/chosen.jquery.min' => ['jquery']],
        'pickadate_picker' => ['Backend.pickadate/picker'],
        'pickadate_date' => ['Backend.pickadate/picker.date'],
        'pickadate_time' => ['Backend.pickadate/picker.time'],
        'pickadate' => ['pickadate_picker', 'pickadate_date', 'pickadate_time'],
        'imagepicker' => ['Backend.imagepicker/image-picker.min'],
        'semanticui' => ['Backend.semantic/semantic.min'],
        'tinymce' => ['Backend.tinymce/tinymce.min'],
        'tinymce_jquery' => ['Backend.tinymce/jquery.tinymce.min'],
        'admin' => ['Backend.admin'],
        'admin_chosen' => ['Backend.admin-chosen' => ['chosen'] ],
        'admin_sidebar' => ['Backend.admin-sidebar' => ['jquery']],
        'admin_tinymce' => ['Backend.admin-tinymce' => ['tinymce', 'tinymce_jquery']],
        'shared' => 'Backend.shared'
    ];

    protected $_css = [

    ];

    protected $_loaded = ['scripts' => [], 'css' => []];

    /**
     * Unstable
     *
     * @param $name
     * @param array $options
     * @return mixed|string|void
     */
    public function script($name, $options = [])
    {
        if (is_string($name) && isset($this->_scripts[$name])) {
            $path = $this->_scripts[$name];
        } else {
            $path = $name;
        }

        if (is_array($path)) {
            $out = "";
            foreach ($path as $_path => $nested) {
                if (is_numeric($_path)) {
                    $_path = $nested;
                    $nested = [];
                }

                if (!empty($nested)) {
                    $out .= $this->script($nested, $options);
                }

                $out .= $this->script($_path, $options);
            }
            return $out;
        }

        $options = array_merge(['block' => null, 'once' => true], $options);

        if (isset($this->_loaded['scripts'][$path]) && $options['once'] === true) {
            return;
        }

        //debug("Loading script: " . $path . "::" . $options['block']);

        $this->_loaded['scripts'][$path] = true;
        return $this->Html->script($path, $options);
    }

}
