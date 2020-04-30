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
class AdminHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    protected $_themeConfig = ['name' => null, 'skin' => null, 'bodyClass' => null, 'darkmode' => null];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->_themeConfig = (array)Configure::read('Admin.Theme') + $this->_themeConfig;

        $this->Html->script('/admin/libs/jquery/jquery.min.js', ['block' => 'headjs']);

        // 3rd party dependencies
        // Bootstrap
        $this->Html->css('/admin/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $this->Html->script('/admin/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        // MomentJS
        $this->Html->script('/admin/js/momentjs/moment.min.js', ['block' => true]);
        // FontAwesome
        $this->Html->css('/admin/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        // IonIcons
        $this->Html->css('/admin/libs/ionicons/css/ionicons.min.css', ['block' => true]);

        // default helpers
        $this->_View->loadHelper('Bootstrap.Ui');
        $this->_View->loadHelper('Admin.Breadcrumb');
        $this->_View->loadHelper('Admin.Toolbar');

        if (Configure::read('Admin.Theme.enableJsFlash')) {
            $this->_View->loadHelper('Admin.Toastr');
        }

        if (Configure::read('Admin.Theme.enableJsAlerts')) {
            $this->_View->loadHelper('Admin.SweetAlert2');
        }

        /*
        $this->_View->loadHelper('User.UserSession', [
            'sessionKey' => 'Admin.UserSession',
            'loginUrl' => ['_name' => 'admin:admin:user:login'],
            'checkUrl' => ['_name' => 'admin:admin:user:checkauth']
        ]);
        */

        // Admin assets
        // Admin css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $this->Html->css('Admin.admin', ['block' => true]);

        // Inject adminjs init script
        $adminjs = [
            'rootUrl' => $this->Url->build('/', ['fullBase' => true]),
            'adminUrl' => $this->Url->build('/admin/', ['fullBase' => true]),
            'debug' => Configure::read('debug'),
        ];
        //$script = sprintf('console.log("INIT", window.Admin); if (window.Admin !== undefined) { console.log("INIT2");  Admin.initialize(%s); }', json_encode($adminjs));

        $script = sprintf('var AdminSettings = window.AdminSettings = %s;', json_encode($adminjs));
        if (Configure::read('debug')) {
            $script .= 'console.log("[admin] global settings", window.AdminSettings);';
        }
        $this->Html->scriptBlock($script, ['block' => true, 'safe' => false]);

        $this->Html->script('/admin/js/admin.js', ['block' => true]);
        $this->Html->script('/admin/js/admin.iconify.js', ['block' => true]);
        $this->Html->script('/admin/js/admin.tooltip.js', ['block' => true]);
        //$this->Html->script('/admin/js/admin.alert.js', ['block' => true]);
    }

    /**
     * @param \Cake\Event\Event $event The event.
     * @return void
     */
    public function beforeRender(Event $event): void
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();
        $view->set('be_title', Configure::read('Admin.Dashboard.title'));
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
            $view->Html->css('/admin/css/layout/dark.min.css', ['block' => true]);
            $themeBodyClass = trim($themeBodyClass . ' darkmode');
        }

        $view->set(
            'be_layout_body_class',
            trim(join(' ', [$themeClass, $themeSkinClass, $themeBodyClass]))
        );
        //$view->Html->css('/admin/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

        //$view->append('meta', $this->Html->charset());
        //$view->Html->meta(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE-edge'], null, ['block' => true]);
        //$view->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
        //$view->Html->meta('mobile-web-app-capable', 'yes', ['block' => true]);
        //$view->Html->meta('viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no', ['block' => true]);
        //$this->Html->meta('icon', null, ['block' => true]);
        $view->Html->meta('generator', 'CakePHP Admin by fmlabs', ['block' => true]);
    }
}
