<?php
declare(strict_types=1);

namespace Admin\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * Class AdminHelper
 *
 * @package Admin\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class AdminThemeHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    protected $_themeConfig = ['name' => null, 'skin' => null, 'bodyClass' => null, 'darkmode' => null];

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        $this->_themeConfig = (array)Configure::read('Admin.Theme') + $this->_themeConfig;

        $this->Html->css('Admin.admin/admin.min', ['block' => true]);
    }

    /**
     * @param \Cake\Event\Event $event The event.
     * @return void
     */
    public function beforeRender(Event $event): void
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();
        $view->set('admin_layout_title', Configure::read('Admin.Dashboard.title', __d('admin', 'Administration')));
        $view->set('admin_dashboard_url', $this->Url->build(Configure::read('Admin.Dashboard.url')));
        // @TODO Remove deprecated layout view vars
        $view->set('be_title', Configure::read('Admin.Dashboard.title', __d('admin', 'Administration')));
        $view->set('be_dashboard_url', $this->Url->build(Configure::read('Admin.Dashboard.url')));
    }

    /**
     * @param \Cake\Event\Event $event The event.
     * @return void
     */
    public function beforeLayout(Event $event)
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();
        // title
        $title = $view->fetch('title'); // check the title block
        $title = $title ?: $event->getSubject()->get('page.title'); // check the 'page.title' view var
        $title = $title ?: Inflector::humanize(Inflector::tableize($view->getRequest()->getParam('controller'))); // inflected controller name
        $view->assign('title', $title);

        $themeClass = $this->_themeConfig['name'] ?: 'theme-default';
        $themeSkinClass = $this->_themeConfig['skin'] ?: 'skin-default';
        $themeBodyClass = $this->_themeConfig['bodyClass'] ?: '';

        if ($this->_themeConfig['darkmode']) {
            $view->Html->css('/css/layout/dark.min.css', ['block' => true]);
            $themeBodyClass = trim($themeBodyClass . ' darkmode');
        }

        $view->set(
            'admin_layout_html_class',
            trim(join(' ', [$themeClass, $themeSkinClass])));
        $view->set(
            'admin_layout_body_class',
            trim(join(' ', [$themeBodyClass]))
        );
        //$view->Html->css('/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

        //$view->append('meta', $this->Html->charset());
        //$view->Html->meta(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE-edge'], null, ['block' => true]);
        //$view->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
        //$view->Html->meta('mobile-web-app-capable', 'yes', ['block' => true]);
        //$view->Html->meta('viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no', ['block' => true]);
        //$this->Html->meta('icon', null, ['block' => true]);
        $view->Html->meta('generator', __d('admin', 'CakePHP Admin by fmlabs'), ['block' => true]);
    }
}
