<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Event\Event;
use Cake\View\Helper;
use RuntimeException;

/**
 * Class ToolbarHelper
 *
 * @package Admin\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Bootstrap\View\Helper\UiHelper $Ui
 * @property \Bootstrap\View\Helper\ButtonHelper $Button
 */
class ToolbarHelper extends Helper
{
    public array $helpers = ['Html', 'Bootstrap.Ui', 'Bootstrap.Button'];

    protected array $_defaultConfig = [
        'element' => 'Admin.layout/admin/toolbar',
        'block' => 'toolbar',
        'options' => ['class' => ''],
    ];

    /**
     * @var array List of toolbar menu items
     */
    protected array $_items = [];

    /**
     * @var bool Render flag. True, if render method has been called
     */
    protected bool $_rendered = false;

    /**
     * @var bool Grouping flag. True, if grouping is active (experimental)
     */
    protected bool $_grouping = false;

    /**
     * Reset to default config settings and clear items
     *
     * @return $this
     */
    public function reset()
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->setConfig($this->_defaultConfig, null, false);

        return $this;
    }

    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->create($config);
    }

    /**
     * Create a new toolbar.
     * Clears items and optionally applies a custom config
     *
     * @param array $config Toolbar config
     * @return $this
     */
    public function create(array $config = [])
    {
        $this->_items = [];
        $this->_rendered = false;
        $this->_grouping = false;
        $this->setConfig($config);

        // parse toolbar actions defined in 'toolbar.actions' view-var
        $toolbarActions = $this->_View->get('toolbar.actions');
        if ($toolbarActions) {
            array_walk($toolbarActions, function ($action): void {
                $title = $url = null;
                $attr = [];
                if (!is_array($action)) {
                    throw new RuntimeException('Invalid toolbar action item');
                }
                if (count($action) == 2) {
                    [$title, $url] = $action;
                } elseif (count($action) === 3) {
                    [$title, $url, $attr] = $action;
                } else {
                    extract($action, EXTR_IF_EXISTS); // maybe it's an assoc array. try to extract available vars from action
                }

                $this->add($title, compact('url', 'attr'));
            });
        }

        return $this;
    }

    /**
     * Add a toolbar item.
     *
     * @param string $title Link title
     * @param array $options Link options
     * @return $this
     */
    public function add(string $title, array $options = [])
    {
        $options = array_merge([
            'type' => null,
            'title' => $title,
            'url' => null,
            'attr' => [],
        ], $options);

        switch ($options['type']) {
            case 'postLink':
            case 'post':
                $this->addPostLink($options);
                break;
            case 'link':
            default:
                $this->addLink($options);
        }

        return $this;
    }

    /**
     * Add a new toolbar link item.
     *
     * @param array|string $title Link title
     * @param string|null $url Link Url
     * @param array $attr Link attributes
     * @return $this
     */
    public function addLink(string|array $title, ?string $url = null, array $attr = [])
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
     * @param array|string $title Link title
     * @param string|null $url Link Url
     * @param array $attr Link attributes
     * @param array $data Post data
     * @return $this
     */
    public function addPostLink(string|array $title, ?string $url = null, array $attr = [], array $data = [])
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
    public function startGroup(string $title, array $options = [])
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
    public function getMenuItems(): array
    {
        return $this->_items;
    }

    /**
     * Render toolbar menu
     *
     * @param array $options Render options
     * @return string Rendered HTML string
     */
    public function render(array $options = [], $childMenuOptions = [], $itemOptions = []): string
    {
        //$options = array_merge($this->getConfig('options'), $options);
        //$html = $this->Ui->menu($this->getMenuItems(), $options, $childMenuOptions, $itemOptions);
        $html = '';
        foreach ($this->getMenuItems() as $item) {
            $html .= $this->_renderMenuItem($item);
        }
        $this->_rendered = true;

        return $html;
    }

    protected function _renderMenuItem(array $item)
    {
        $item += ['size' => 'sm'];

        return $this->Button->create($item['title'], $item);
    }

    /**
     * @inheritDoc
     */
    public function beforeRender(Event $event)
    {
    }

    /**
     * @inheritDoc
     */
    public function beforeLayout(Event $event)
    {
        /*
        debug("Toolbar::beforeLayout: render!" . $event->getSubject()->getCurrentType());
        */
        if (!$this->_rendered) {
            //if ($event->getSubject() instanceof AdminView) {
                $event->getSubject()->assign($this->getConfig('block'), $this->_View->element($this->getConfig('element'), [
                    'toolbar' => $this->render(),
                ]));
            //}
        }
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [
            'View.beforeRender' => ['callable' => 'beforeRender'],
            'View.beforeLayout' => ['callable' => 'beforeLayout'],
        ];
    }
}
