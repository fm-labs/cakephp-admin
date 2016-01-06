<?php
namespace Backend\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\BasicWidget;
use Cake\Routing\Router;

/**
 * Class HtmlEditorWidget
 *
 * TinyMCE Html Editor widget
 *
 * @package Backend\View\Widget
 */
class HtmlEditorWidget extends BasicWidget
{
    /**
     * Default TinyMCE html editor config
     * @see https://www.tinymce.com/docs
     * @var array
     */
    public static $defaultConfig = [
        // A CSS selector for the areas that TinyMCE should make editable.
        'selector' => 'textarea.htmleditor',
        // Which plugins TinyMCE will attempt to load when starting up
        'plugins' => [
            'image link lists code table media paste wordcount'
        ],
        // This option allows you to disable the element path within the status bar at the bottom of the editor.
        // Default: True
        'elementpath' => true,
        // Height of the editable area in pixels.
        'height' => 300,

        'content_css_url' => null,

        'menubar' => false,
        'menu' => [
            //'file' => [ 'title' => 'File', 'items' => 'newdocument'],
        ],
        'toolbar' => [
            'formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote | code',
            'undo redo | cut copy paste | link image media | table'
        ],
        // URL Handling
        'convert_urls' => true, // TinyMCE default: true
        'relative_urls' => false, // TinyMCE default: true
        'remove_script_host' => true, // TinyMCE default: true
        'document_base_url_url' => '/',
        //'importcss_append' => true,
        'cache_suffix' => null,
    ];

    /**
     * Editor config fields that will be treated as an URL, if the suffix '_url' is appended.
     * E.g. if 'image_list_url' is set, the config key 'image_list' will be set with the corresponding route URL
     *
     * @var array
     */
    public static $urlFields = ['document_base_url', 'content_css', 'image_list', 'link_list'];
    
    /**
     * Render a text area element which will be converted to a tinymce htmleditor.
     *
     * Data supports the following keys
     *
     * @param array $data The data to build a textarea with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string HTML elements.
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'val' => '',
            'name' => '',
            'escape' => true,
            'class' => '',
            'id' => '',
            'editor' => [],
        ];
        $data['class'] = ($data['class']) ? $data['class'] . ' htmleditor' : 'htmleditor';
        $data['id'] = ($data['id']) ? $data['id'] : uniqid('htmleditor');

        $editor = ($data['editor']) ? array_merge(static::$defaultConfig, $data['editor']) : static::$defaultConfig;
        $editor['selector'] = '#' . $data['id'];

        // convert urls
        foreach (static::$urlFields as $key) {
            if (isset($editor[$key . '_url'])) {

                $url = $editor[$key . '_url'];
                unset($editor[$key . '_url']);

                /*
                if (is_array($url)) {
                    array_walk($url, function(&$val) {
                       $val = Router::url($val, true);
                    });
                } elseif ($url) {
                    $url = Router::url($url, true);
                }
                */

                $url = Router::url($url, true);
                $editor[$key] = $url;
            }
        }

        $this->_templates->add([
            'htmlEditor' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea><script>{{editorScript}}</script>',
        ]);

        $selector = $editor['selector'];
        unset($editor['selector']);
        //$editorScript = "$(document).ready(function() { tinymce.init(" . json_encode($editor) .") });";
        $jsTemplate = '$(document).ready(function() { $("%s").tinymce(%s); });';
        $editorScript = sprintf($jsTemplate, $selector, json_encode($editor));

        return $this->_templates->format('htmlEditor', [
            'name' => $data['name'],
            'value' => $data['escape'] ? h($data['val']) : $data['val'],
            'editorScript' => $editorScript,
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'val', 'type', 'editor']
            )
        ]);
    }
}