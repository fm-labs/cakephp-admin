<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\View\Helper\HtmlHelper;

/**
 * Drop-in HtmlHelper replacement, optimized for usage with Admin plugin
 *
 * @package Admin\View\Helper
 * @codeCoverageIgnore
 */
class AdminHtmlHelper extends HtmlHelper
{
    /**
     * @var array
     */
    protected $_scripts = [
        /*
        '_jquery' => 'Admin.jquery/jquery-1.11.2.min',
        '_jqueryui' => ['Admin.jqueryui/jquery-ui.min' => ['_jquery']],
        '_pickadate_picker' => ['Admin.pickadate/picker'],
        '_pickadate_date' => ['Admin.pickadate/picker.date'],
        '_pickadate_time' => ['Admin.pickadate/picker.time'],
        '_pickadate' => ['_pickadate_picker', '_pickadate_date', '_pickadate_time'],
        '_imagepicker' => ['Admin.imagepicker/image-picker.min'],
        '_tinymce_core' => ['Admin.tinymce/tinymce.min'],
        '_tinymce_jquery' => ['Admin.tinymce/jquery.tinymce.min'],
        '_tinymce' => ['_tinymce_core', '_tinymce_jquery']
        */
    ];

    protected $_css = [

    ];

    protected $_loaded = ['scripts' => [], 'css' => []];

    public function css($path, array $options = []): ?string
    {
        //$url = $this->Url->css($path, $options);
        return parent::css($path, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function script($url, array $options = []): ?string
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
