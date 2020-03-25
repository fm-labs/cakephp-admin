<?php

namespace Backend\View\Helper;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\View\Helper;

/**
 * Class BackendHelper
 *
 * @package Backend\View\Helper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class BackendHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    protected $_themeConfig = ['name' => null, 'skin' => null, 'bodyClass' => null, 'darkmode' => null];

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $this->_themeConfig = (array)Configure::read('Backend.Theme') + $this->_themeConfig;

        $this->Html->script('/backend/libs/jquery/jquery.min.js', ['block' => 'headjs']);

        // 3rd party dependencies
        // Bootstrap
        $this->Html->css('/backend/libs/bootstrap/dist/css/bootstrap.min.css', ['block' => true]);
        $this->Html->script('/backend/libs/bootstrap/dist/js/bootstrap.min.js', ['block' => true]);
        // MomentJS
        $this->Html->script('/backend/js/momentjs/moment.min.js', ['block' => true]);
        // FontAwesome
        $this->Html->css('/backend/libs/fontawesome/css/font-awesome.min.css', ['block' => true]);
        // IonIcons
        $this->Html->css('/backend/libs/ionicons/css/ionicons.min.css', ['block' => true]);

        // default helpers
        $this->_View->loadHelper('Bootstrap.Ui');
        $this->_View->loadHelper('Backend.Backbone');
        $this->_View->loadHelper('Backend.Breadcrumb');
        $this->_View->loadHelper('Backend.Toolbar');

        if (Configure::read('Backend.Theme.enableJsFlash')) {
            $this->_View->loadHelper('Backend.Toastr');
        }

        if (Configure::read('Backend.Theme.enableJsAlerts')) {
            $this->_View->loadHelper('Backend.SweetAlert2');
        }

        /*
        $this->_View->loadHelper('User.UserSession', [
            'sessionKey' => 'Backend.UserSession',
            'loginUrl' => ['_name' => 'admin:backend:user:login'],
            'checkUrl' => ['_name' => 'admin:backend:user:checkauth']
        ]);
        */

        // Backend assets
        // Backend css injected after css block, as a dirty workaround to override styles of vendor css injected from views
        $this->Html->css('Backend.backend', ['block' => true]);

        // Inject backendjs init script
        $backendjs = [
            'rootUrl' => $this->Url->build('/', true),
            'adminUrl' => $this->Url->build('/admin/', true),
            'debug' => Configure::read('debug'),
        ];
        //$script = sprintf('console.log("INIT", window.Backend); if (window.Backend !== undefined) { console.log("INIT2");  Backend.initialize(%s); }', json_encode($backendjs));

        $script = sprintf('var BackendSettings = window.BackendSettings = %s;', json_encode($backendjs));
        if (Configure::read('debug')) {
            $script .= 'console.log("[backend] global settings", window.BackendSettings);';
        }
        $this->Html->scriptBlock($script, ['block' => true, 'safe' => false]);

        $this->Html->script('/backend/js/backend.js', ['block' => true]);
        $this->Html->script('/backend/js/backend.iconify.js', ['block' => true]);
        $this->Html->script('/backend/js/backend.tooltip.js', ['block' => true]);
        //$this->Html->script('/backend/js/backend.alert.js', ['block' => true]);
    }

    public function beforeRender(Event $event)
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();
        $view->set('be_title', Configure::read('Backend.Dashboard.title'));
        $view->set('be_dashboard_url', $this->Url->build(Configure::read('Backend.Dashboard.url')));
    }

    /**
     * {@inheritDoc}
     */
    public function beforeLayout(Event $event)
    {
        /** @var \Cake\View\View $view */
        $view = $event->getSubject();
        // title
        $title = $view->fetch('title'); // check the title block
        $title = ($title) ?: $event->getSubject()->get('page.title'); // check the 'page.title' view var
        $title = ($title) ?: Inflector::humanize(Inflector::tableize($view->getRequest()->getParam('controller'))); // inflected controller name
        $view->assign('title', $title);

        $themeClass = ($this->_themeConfig['name']) ?: 'theme-default';
        $themeSkinClass = ($this->_themeConfig['skin']) ?: 'skin-default';
        $themeBodyClass = ($this->_themeConfig['bodyClass']) ?: '';

        if ($this->_themeConfig['darkmode']) {
            $view->Html->css('/backend/css/layout/dark.min.css', ['block' => true]);
            $themeBodyClass = trim($themeBodyClass . ' darkmode');
        }

        $view->set(
            'be_layout_body_class',
            trim(join(' ', [$themeClass, $themeSkinClass, $themeBodyClass]))
        );
        //$view->Html->css('/backend/css/skins/'.$themeSkinClass.'.min.css', ['block' => true]);

        $view->append('meta', $this->Html->charset());
        $view->Html->meta(['http-equiv' => 'X-UA-Compatible', 'content' => 'IE-edge'], null, ['block' => true]);
        $view->Html->meta('robots', 'noindex,nofollow', ['block' => true]);
        $view->Html->meta('mobile-web-app-capable', 'yes', ['block' => true]);
        $view->Html->meta('viewport', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no', ['block' => true]);
        $this->Html->meta('icon', null, ['block' => true]);
    }
}
