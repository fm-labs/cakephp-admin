<?php
namespace Backend\View\Helper;

use Bootstrap\View\Helper\UiHelper;
use Cake\Event\Event;
use Cake\View\Helper;
use Cake\View\Helper\HtmlHelper;

/**
 * Class ToolbarHelper
 *
 * @package Backend\View\Helper
 * @property HtmlHelper $Html
 * @property UiHelper $Ui
 */
class ToolbarHelper extends Helper
{
    public $helpers = ['Html', 'Bootstrap.Ui'];

    /**
     * @var array Default config
     */
    protected $_defaultConfig = [];

    /**
     * @var array List of toolbar menu items
     */
    protected $_items = [];

    /**
     * @var bool Render flag. True, if render method has been called
     */
    protected $_rendered = false;

    /**
     * @var bool Grouping flag. True, if grouping is active (experimental)
     */
    protected $_grouping = false;

    /**
     * Reset to default config settings and clear items
     * @return $this
     */
    public function reset()
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->config($this->_defaultConfig, null, false);

        return $this;
    }

    /**
     * Create a new toolbar.
     * Clears items and optionally applies a custom config
     *
     * @param array $config
     * @return $this
     */
    public function create($config = [])
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->config($config);

        return $this;
    }

    /**
     * Add a toolbar item.
     *
     * @param $title
     * @param array $options
     * @return $this
     */
    public function add($title, $options = [])
    {
        $options = array_merge([
            'type' => null,
            'title' => $title,
            'url' => null,
            'attr' => []
        ], $options);

        switch ($options['type']) {
            case "postLink":
            case "post":
                $this->addPostLink($options);
            case "link":
            default:
                $this->addLink($options);
        }

        return $this;
    }

    /**
     * Add a new toolbar link item.
     *
     * @param $title
     * @param null $url
     * @param array $attr
     * @return $this
     */
    public function addLink($title, $url = null, $attr = [])
    {
        if ($this->_grouping === true) {
            return $this;
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

        return $this;
    }

    /**
     * Add a new toolbar item (post-link).
     *
     * @param $title
     * @param null $url
     * @param array $attr
     * @return $this
     */
    public function addPostLink($title, $url = null, $attr = [], $data = [])
    {
        //@TODO Implement ToolbarHelper::addPostLink()
        $this->addLink($title, $url, $attr);

        return $this;
    }

    /**
     * Experimental! Item grouping
     *
     * @param $title
     * @param array $options
     * @return $this
     */
    public function startGroup($title, $options = [])
    {
        //@TODO Implement ToolbarHelper::startGroup()
        $this->_grouping = true;

        return $this;
    }

    /**
     * Experimental! Item grouping
     *
     * @return $this
     */
    public function endGroup()
    {
        //@TODO Implement ToolbarHelper::endGroup()
        $this->_grouping = false;

        return $this;
    }

    /**
     * Get list of menu items
     *
     * @return array
     */
    public function getMenuItems()
    {
        return $this->_items;
    }

    public function beforeRender(Event $event)
    {
        // parse toolbar actions defined in 'toolbar.actions' view-var
        $toolbarActions = (array)$event->subject()->get('actions');

        if ($toolbarActions) {
            array_walk($toolbarActions, function ($action) {
                $title = $url = null;
                $attr = [];
                if (!is_array($action)) {
                    throw new \RuntimeException('Invalid toolbar action item');
                }
                if (count($action) == 2) {
                    list($title, $url) = $action;
                } elseif (count($action) === 3) {
                    list($title, $url, $attr) = $action;
                } else {
                    extract($action, EXTR_IF_EXISTS); // maybe it's an assoc array. try to extract available vars from action
                }

                $this->add($title, compact('url', 'attr'));
            });
        }
    }

    /**
     * Render toolbar menu
     *
     * @param array $options
     * @return string Rendered HTML string
     */
    public function render($options = [])
    {
        //if ($this->_rendered === true) {
        //    return null;
        //}

        //$this->_rendered = true;
        return $this->Ui->menu($this->getMenuItems(), $options);
    }

    /**
     * Trigger rendering method by self invocation
     *
     * @param array $options
     * @return string Rendered HTML string
     */
    public function __invoke($options = [])
    {
        return $this->render($options);
    }
}
