<?php

namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Drop-in HtmlHelper replacement, optimized for usage with Backend plugin
 *
 * @package Backend\View\Helper
 * @deprecated This class is currently unused.
 */
class BackendHtmlHelper extends HtmlHelper
{
    /**
     * @var array
     */
    protected $_scripts = [
        /*
        '_jquery' => 'Backend.jquery/jquery-1.11.2.min',
        '_jqueryui' => ['Backend.jqueryui/jquery-ui.min' => ['_jquery']],
        '_chosen' => ['Backend.chosen/chosen.jquery.min' => ['_jquery']],
        '_pickadate_picker' => ['Backend.pickadate/picker'],
        '_pickadate_date' => ['Backend.pickadate/picker.date'],
        '_pickadate_time' => ['Backend.pickadate/picker.time'],
        '_pickadate' => ['_pickadate_picker', '_pickadate_date', '_pickadate_time'],
        '_imagepicker' => ['Backend.imagepicker/image-picker.min'],
        '_tinymce_core' => ['Backend.tinymce/tinymce.min'],
        '_tinymce_jquery' => ['Backend.tinymce/jquery.tinymce.min'],
        '_tinymce' => ['_tinymce_core', '_tinymce_jquery']
        */
    ];

    protected $_css = [

    ];

    protected $_loaded = ['scripts' => [], 'css' => []];

    /**
     * {@inheritDoc}
     */
    public function script($url, array $options = [])
    {
        /*
        if (is_string($url) && $url[0] === "_" && isset($this->_scripts[$url])) {
            $url = $this->_scripts[$url];
        }

        if (is_array($url)) {
            $out = "";
            foreach ($url as $_path => $nested) {
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

        if (isset($this->_loaded['scripts'][$url]) && $options['once'] === true) {
            return;
        }

        $this->_loaded['scripts'][$url] = true;
        */
        return parent::script($url, $options);
    }
}
