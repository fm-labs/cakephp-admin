<?php
declare(strict_types=1);

namespace Admin\View;

use Admin\Ui;
use Cake\Event\Event;
use Cake\View\View;

/**
 * Class AdminView
 *
 * @package Admin\View
 */
class AdminView extends View
{
    /**
     * @var string
     */
    public $layout = 'Admin.admin';

    /**
     * @var \Cupcake\Ui\Ui
     */
    public $ui = null;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        // core admin helpers
        $this->loadHelper('Html', ['className' => '\Admin\View\Helper\AdminHtmlHelper']);
        $this->loadHelper('Form', ['className' => '\Admin\View\Helper\AdminFormHelper']);
        $this->loadHelper('Admin.Admin');

        $this->ui = new \Cupcake\Ui\Ui($this);
        try {
            $this->ui->add('header', new Ui\Layout\Header());
            $this->ui->add('header_panels_right', new Ui\Layout\Header\MenuPanel());
            $this->ui->add('header_panels_right', new Ui\Layout\Header\UserPanel());

            $this->ui->add('footer', new Ui\Layout\Footer());

            $this->ui->add('sidebar', new Ui\Layout\Sidebar());
            $this->ui->add('sidebar_panels', new Ui\Layout\Sidebar\MenuPanel());
        } catch (\Exception $ex) {
            debug($ex->getMessage());
        }
        $this->getEventManager()->on($this->ui);

        // trigger cc action 'admin_view_init'
        \Cupcake\Cupcake::doAction('admin_view_init');

        // dispatch 'Admin.View.initialize' event
        $this->getEventManager()->dispatch(new Event('Admin.View.initialize', $this));
    }

    /**
     * @inheritDoc
     */
    public function fetch(string $name, string $default = ''): string
    {
        // 0. check, if there is already content for that block
        $content = parent::fetch($name, '');

        // 1. try to get block contents from UI
        if ($name !== 'content' && !$content) {
            $content .= $this->ui->fetch($name);
        }

        // 2. deploy a cc filter
        //$content = \Cupcake\Cupcake::doFilter('admin_view_fetch', compact('content'));

        // 3. dispatch 'Admin.View.fetch' event
        $event = $this->getEventManager()->dispatch(
            new Event('Admin.View.fetch', $this, ['content' => $content, 'name' => $name])
        );
        $content = $event->getData('content');

        // 4. fallback to the default layout elements for non-content blocks
        if ($this->getCurrentType() == static::TYPE_LAYOUT && $name !== 'content' && !$content) {
            [$ns, $layout] = pluginSplit($this->layout, true);
            $elementPath = $ns . 'layout/' . $layout . '/' . $name;
            if ($this->elementExists($elementPath)) {
                $content .= $this->element($elementPath);
            }
        }

        // 5. fallback to default content
        if (!$content) {
            $content = $default;
        }

        //if (!$content && Configure::read('debug')) {
        //    $content = sprintf('[block "%s" not found]', $name);
        //}

        return $content;
    }
}
