<?php
declare(strict_types=1);

namespace Admin\View;

use Admin\Ui\Header;
use Admin\Ui\Sidebar;
use Admin\Ui\Ui;
use Cake\Event\Event;
use Cake\View\View;

/**
 * Class AdminView
 *
 * @package Admin\View
 */
class AdminView extends View
{
    public $layout = "Admin.admin";

    /**
     * @var \Admin\Ui\Ui
     */
    public $ui = null;

    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        $this->loadHelper('Html', ['className' => '\Admin\View\Helper\AdminHtmlHelper']);
        $this->loadHelper('Form', ['className' => '\Admin\View\Helper\AdminFormHelper']);
        $this->loadHelper('Admin.Admin');

        $this->ui = new Ui($this);
        //$this->ui->add('header_panels_left', new Header\MenuPanel('system'));
        $this->ui->add('header_panels_right', new Header\MenuPanel());
        $this->ui->add('header_panels_right', new Header\UserPanel());
        $this->ui->add('sidebar_panels', new Sidebar\MenuPanel());
        //$this->getEventManager()->on($this->ui);

        $this->getEventManager()->dispatch(new Event('Admin.View.initialize', $this));
    }

    /**
     * {@inheritDoc}
     */
    public function fetch(string $name, string $default = ''): string
    {
        $content = parent::fetch($name, '');

        // get block contents from UI
        if ($name !== "content" && !$content) {
            $content .= $this->ui->render($name);
        }

        // get layout block contents from default elements
        if ($this->getCurrentType() == 'layout' && $name !== "content" && !$content) {
            [$ns, $layout] = pluginSplit($this->layout, true);
            $elementPath = $ns . 'layout/' . $layout . '/' . $name;
            if ($this->elementExists($elementPath)) {
                $content .= $this->element($elementPath);
            }
        }

        // dispatch 'Admin.View.fetch' event
        $event = $this->getEventManager()->dispatch(
            new Event('Admin.View.fetch', $this, ['name' => $name, 'content' => $content])
        );
        $content = $event->getData('content');

        if (!$content) {
            $content = $default;
        }

        return $content;
    }
}
