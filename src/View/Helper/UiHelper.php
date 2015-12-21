<?php
namespace Backend\View\Helper;

use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;
use Cake\View\Helper\UrlHelper;
use Cake\View\StringTemplateTrait;
use Cake\View\Helper\FormHelper;

/**
 * Class UiHelper
 *
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 * @property FormHelper $Form
 * @property UrlHelper $Url
 */
class UiHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Html', 'Form', 'Url'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'iconBefore' => '<i class="{{icon}} icon"></i>{{content}}',
            'modal' => '<div class="ui modal"></div>',
            'label' => '<div class="ui label {{class}}">{{label}}</div>'
        ]
    ];

    public function link($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->templater()->format('iconBefore', [
                'icon' => $options['icon'],
                'content' => $title
            ]);
            $options['escape'] = false;
            unset($options['icon']);
        }
        return $this->Html->link($title, $url, $options);
    }

    public function postLink($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->templater()->format('iconBefore', [
                'icon' => $options['icon'],
                'content' => $title
            ]);
            $options['escape'] = false;
            unset($options['icon']);
        }
        return $this->Form->postLink($title, $url, $options);
    }

    public function deleteLink($title, $url = null, array $options = [])
    {
        return $this->postLink($title, $url, $options);
    }

    public function statusLabel($status, $options = [])
    {
        $labels = (isset($options['label'])) ? (array) $options['label'] : [__('No'), __('Yes')];
        $classes = (isset($options['class'])) ? (array) $options['class'] : ['red', 'green'];

        $label = $status;
        $class = '';
        if (isset($labels[$status])) {
            $label = $labels[$status];
        }

        if (isset($classes[$status])) {
            $class = $classes[$status];
        }

        $html = $this->templater()->format('label', [
            'class' => $class,
            'label' => $label
        ]);
        return $html;
    }
}
