<?php
declare(strict_types=1);

namespace Admin\View;

use Admin\Ui;
use Cake\Core\Configure;
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

        // default helpers
        $this->loadHelper('Bootstrap.Bootstrap5');
        $this->loadHelper('Bootstrap.Ui');

        $this->loadHelper('Sugar.Jquery');
        $this->loadHelper('Sugar.FontAwesome4');
        //$this->loadHelper('Sugar.MomentJs');

        $this->loadHelper('Admin.AdminJs');
        $this->loadHelper('Admin.AdminTheme');
        $this->loadHelper('Admin.Breadcrumb');
        $this->loadHelper('Admin.Toolbar');

        if (Configure::read('Admin.Theme.enableJsFlash')) {
            $this->loadHelper('Sugar.Toastr');
        }

        if (Configure::read('Admin.Theme.enableJsAlerts')) {
            $this->loadHelper('Sugar.SweetAlert2');
        }

        // configure UI
        $this->ui = new \Cupcake\Ui\Ui($this);
        try {
            $this->ui->add('header', Ui\Layout\Header::class);
            //$this->ui->add('header_panels_right', new Ui\Layout\Header\MenuPanel());
            //$this->ui->add('header_panels_right', Ui\Layout\Header\HeaderSysMenu::class);
            //$this->ui->add('header_panels_right', new Ui\Layout\Header\UserPanel());

            $this->ui->add('footer', Ui\Layout\Footer::class);

            $this->ui->add('sidebar', Ui\Layout\Sidebar::class);
            $this->ui->add('sidebar_panels', Ui\Layout\Sidebar\MenuPanel::class);
        } catch (\Exception $ex) {
            debug($ex->getMessage());
        }

        // trigger cc action 'admin_view_init'
        //\Cupcake\Cupcake::doAction('admin_view_init');

        // dispatch 'Admin.View.initialize' event
        $this->getEventManager()->on($this->ui);
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
