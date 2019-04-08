<?php
namespace Backend\View\Helper;

use Backend\View\BackendView;
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

    protected $_defaultConfig = [
        'element' => 'Backend.Layout/admin/toolbar',
        'block' => 'toolbar',
        'options' => ['class' => '']
    ];

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
     * @param array $config Toolbar config
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
     * @param string $title Link title
     * @param array $options Link options
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
                break;
            case "link":
            default:
                $this->addLink($options);
        }

        return $this;
    }

    /**
     * Add a new toolbar link item.
     *
     * @param string $title Link title
     * @param null|string $url Link Url
     * @param array $attr Link attributes
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
     * @param string $title Link title
     * @param null|string $url Link Url
     * @param array $attr Link attributes
     * @param array $data Post data
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
     * @param string $title Group title
     * @param array $options Group options
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

    /**
     * Render toolbar menu
     *
     * @param array $options Render options
     * @return string Rendered HTML string
     */
    public function render($options = [], $childMenuOptions = [], $itemOptions = [])
    {
        $options = array_merge($this->config('options'), $options);
        $html = $this->Ui->menu($this->getMenuItems(), $options, $childMenuOptions, $itemOptions);
        $this->_rendered = true;

        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeRender(Event $event)
    {
        // parse toolbar actions defined in 'toolbar.actions' view-var
        $toolbarActions = (array)$event->subject()->get('toolbar.actions');

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
     * {@inheritDoc}
     */
    public function beforeLayout(Event $event)
    {
        if (!$this->_rendered) {
            if ($event->subject() instanceof BackendView) {
                $event->subject()->Blocks->set($this->config('block'), $this->_View->element($this->config('element'), [
                    'toolbar' => $this->render()
                ]));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender'],
            'View.beforeLayout' => ['callable' => 'beforeLayout']
        ];
    }
}
