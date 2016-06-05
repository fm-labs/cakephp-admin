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
            // 'icon' => '<span class="glyphicon glyphicon-{{class}}"{{attrs}}></span>', #bootstrap style
            'icon' => '<i class="fa fa-{{class}}"{{attrs}}></i>', # fontawesome style
            'modal' => '<div class="modal"></div>',
            'label' => '<span class="label label-{{class}}"{{attrs}}>{{label}}</span>',
            'menu' => '<ul{{attrs}}>{{items}}</ul>',
            'menuItem' => '<li{{attrs}}>{{content}}</li>',
            'menuItemDropdown' => '<li class="dropdown"{{attrs}}>{{content}}{{children}}</li>',
            'menuDropdownButton' => '<a{{attrs}}>{{title}} <span class="caret"></span></a>',
            'menuLink' => '<a{{attrs}}>{{title}}</a>'
        ]
    ];

    public function link($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->icon($options['icon']) . " " . $title;

            $options['escape'] = false;
            unset($options['icon']);
        }
        return $this->Html->link($title, $url, $options);
    }

    public function postLink($title, $url = null, array $options = [])
    {
        if (isset($options['icon'])) {
            $title = $this->icon($options['icon']) . " " . $title;

            $options['escape'] = false;
            unset($options['icon']);
        }
        return $this->Form->postLink($title, $url, $options);
    }

    public function deleteLink($title, $url = null, array $options = [])
    {
        return $this->postLink($title, $url, $options);
    }

    public function statusLabel($status, $options = [], $map = [])
    {
        $options += [];

        if (empty($map)) {
            $map = [
                0 => [__('No'), 'danger'],
                1 => [__('Yes'), 'success']
            ];
        }

        $status = (int) $status;
        $label = $status;
        $class = "";

        if (array_key_exists($status, $map)) {
            $stat = $map[$status];
            if (is_string($stat)) {
                $stat = [$status, $stat];
            }

            if (is_array($stat) && count($stat) == 2) {
                list($label, $class) = $stat;
            }

        }

        $label = $this->templater()->format('label', [
            'class' => $class,
            'label' => $label,
            'attrs' => $this->templater()->formatAttributes($options, ['toggle', 'class', 'label'])
        ]);
        return $label;
    }

    public function icon($class, $options = [])
    {
        $options += ['tag' => 'icon', 'class' => $class, 'attrs' => []];

        $tag = $options['tag'];
        unset($options['tag']);

        return $this->templater()->format($tag, $options);
    }

    public function menu($menuList = [], $menuOptions = [], $childMenuOptions = [], $itemOptions = [])
    {
        $menuOptions += ['class' => 'nav navbar-nav'];

        $items = "";

        foreach ($menuList as $alias => $item) {
            $items .= $this->menuItem($item, $childMenuOptions, $itemOptions);
        }

        // build list
        return $this->templater()->format('menu', [
            'items' => $items,
            'attrs' => $this->templater()->formatAttributes($menuOptions),
        ]);
    }

    public function menuItem($item = [], $childMenuOptions = [], $itemOptions = [])
    {
        $item += ['title' => null, 'plugin' => null, 'url' => [], '_children' => []];


        $plugin = $item['plugin'];
        unset($item['plugin']);

        $url = $item['url'];
        unset($item['url']);

        $children = $item['_children'];
        unset($item['_children']);


        if (!$item['title']) {
            $item['title'] = $item['href'];
        }


        // build item
        if (!empty($children)) {
            if (isset($item['icon'])) {
                $item['data-icon'] = $item['icon'];
            }

            //$link = ($url) ? $this->link($item['title'], $url, ['class' => 'btn btn-default', 'role' => 'button']) : null;

            $ddAttrs = [
                //'data-toggle' => ($url) ? "dropdown disabled" : "drowdown",
                'data-toggle' => 'dropdown',
                'role' => "button",
                'aria-haspopup' => "true",
                'aria-expanded' => "false",
                'href' => '#',
                'data-href' => ($url) ? $this->Url->build($url) : null,
            ];
            $ddAttrs += $item;
            $ddLink = $this->templater()->format('menuDropdownButton', [
                'attrs' => $this->templater()->formatAttributes($ddAttrs, ['requireRoot', 'icon', '_children']),
                'title' => $item['title']
            ]);

            $link = $ddLink;
            $tag = 'menuItemDropdown';
            $children = $this->menu($children, $childMenuOptions, $childMenuOptions, $itemOptions);
        } else {
            $link = $this->link($item['title'], $url, $item);
            $tag = 'menuItem';
            $children = null;
        }

        return $this->templater()->format($tag, [
            'attrs' => $this->templater()->formatAttributes($itemOptions),
            'content' => $link,
            'children' => $children
        ]);
    }
}
