<?php
namespace Backend\View\Helper;

use Bootstrap\View\Helper\UiHelper;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class ToolbarHelper
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 * @property UiHelper $Ui
 */
class ToolbarHelper extends Helper
{
    public $helpers = ['Html', 'Bootstrap.Ui'];

    protected $_defaultConfig = [
    ];

    protected $_items = [];

    protected $_rendered = false;

    protected $_grouping = false;

    /**
     * Reset to default config settings and clear items
     */
    public function reset()
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->config($this->_defaultConfig, null, false);
    }

    /**
     * Create a new toolbar.
     * Clears items and optionally applies a custom config
     *
     * @param array $config
     */
    public function create($config = [])
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->config($config);
    }

    public function add($name, $options = [])
    {
        $options = array_merge([
            'type' => null,
            'name' => $name
        ], $options);

        switch ($options['type'])
        {
            case "link":
            default:
                $this->addLink($options);
        }
    }

    /**
     * Add a new toolbar item (link).
     * @param $title
     * @param null $url
     * @param array $attr
     */
    public function addLink($title, $url = null, $attr = [])
    {
        if ($this->_grouping === true) {
            return;
        }

        if (is_array($title)) {
            extract($title, EXTR_IF_EXISTS);
        }

        $item = [
            'title' => $title,
            'url' => $url,
        ];

        $item += $attr;

        $this->_items[] = $item;
    }

    /**
     * Add a new toolbar item (post-link).
     * @param $title
     * @param null $url
     * @param array $attr
     */
    public function addPostLink($title, $url = null, $attr = [])
    {
        //@TODO Implement toolbar form post link item
        $this->addLink($title, $url, $attr);
    }

    /**
     * Experimental! Item grouping
     * @param $title
     * @param array $options
     */
    public function startGroup($title, $options = [])
    {
        $this->_grouping = true;
    }

    /**
     * Experimental! Item grouping
     */
    public function endGroup()
    {
        $this->_grouping = false;
    }

    /**
     * Render toolbar menu
     *
     * @param array $options
     * @return string
     */
    public function render($options = [])
    {
        return $this->Ui->menu($this->getMenuItems(), $options);
    }

    /**
     * Automatically trigger the rendering method before rendering the layout,
     * if the toolbar has not been rendered yet
     */
    public function beforeLayout()
    {
        //if ($this->_rendered === false && !empty($this->_items)) {
        //    $this->render();
        //}
    }

    /**
     * Trigger rendering method by self invocation
     */
    public function __invoke($options = [])
    {
        return $this->render($options);
    }


    public function getMenuItems()
    {
        return $this->_items;
    }
}
