<?php

namespace Backend\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Controller\Exception\MissingComponentException;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use User\Controller\Component\AuthComponent;

/**
 * Class BackendComponent
 * @package Backend\Controller\Component
 *
 * @property \User\Controller\Component\AuthComponent $Auth
 * @property \Backend\Controller\Component\FlashComponent $Flash
 */
class BackendComponent extends Component
{
    /**
     * @var string
     */
    public static $flashComponentClass = '\Backend\Controller\Component\FlashComponent';

    /**
     * @var string
     */
    public static $authComponentClass = '\User\Controller\Component\AuthComponent';

    /**
     * @var array
     */
    public $actions = [];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'backendPath' => '/backend',
        'backendTitle' => 'Backend',
        'dashboardUrl' => ['plugin' => 'Backend', 'controller' => 'Backend', 'action' => 'index'],
        'authLoginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
        'authLoginRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
        'authLogoutAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
        'authUnauthorizedRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'unauthorized'],
        'authAuthorize' => ['Backend.Backend', 'User.Roles'],
        'userModel' => 'Backend.Users',
        'searchUrl' => ['plugin' => 'Backend', 'controller' => 'Search', 'action' => 'query'],
    ];

    /**
     * @var Controller
     */
    protected $_controller;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        $controller = $this->_registry->getController();

        // Configure Backend from config
        $configMap = [
            'Backend.path' => 'backendPath',
            'Backend.Dashboard.title' => 'backendTitle',
            'Backend.Dashboard.url' => 'dashboardUrl',
            'Backend.Search.url' => 'searchUrl'
        ];
        foreach ($configMap as $configKey => $prop) {
            if (Configure::check($configKey)) {
                $this->config($prop, Configure::read($configKey));
            }
        }

        // Configure Backend FlashComponent
        if ($this->_registry->has('Flash') || !is_a($this->_registry->get('Flash'), static::$flashComponentClass)) {
            $this->_registry->unload('Flash');
            $controller->Flash = $controller->loadComponent('Flash', [
                'className' => static::$flashComponentClass,
                'key' => 'backend'
            ]);
        }

        // Configure RequestHandler component
        if (!$this->_registry->has('RequestHandler')) {
            //$this->_registry->load('RequestHandler');
        }
        // Iframe request detector
        $this->request->addDetector('iframe', function ($request) {
            return (bool)$this->request->query('iframe');
        });

        // Configure Backend Authentication
        if (!$this->_registry->has('Auth') || !is_a($this->_registry->get('Auth'), static::$authComponentClass)) {
            $this->_registry->unload('Auth');
            $controller->Auth = $controller->loadComponent('Auth', [
                'className' => static::$authComponentClass,
            ]);
        }

        // Configure controller
        $controller->viewBuilder()->className('Backend.Backend');
        $controller->viewBuilder()->layout('Backend.admin');

        // Apply Backend theme
        if (Configure::read('Backend.theme')) {
            $controller->viewBuilder()->theme(Configure::read('Backend.theme'));
        }

        // Handle iframe and ajax requests
        if ($this->request->is('iframe')) {
            $controller->viewBuilder()->layout('Backend.iframe');
        } elseif ($this->request->is('ajax')) {
            $controller->viewBuilder()->layout('Backend.ajax');
        }

        // Auto-load ActionComponent if controller defines actions
        if (isset($controller->actions) && !$this->_registry->has('Action')) {
            $controller->loadComponent('Backend.Action');
        }

        $this->_controller =& $controller;
    }


    /**
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        $controller =& $this->_controller;
        $controller->Auth->config('loginAction', $this->config('authLoginAction'));
        $controller->Auth->config('loginRedirect', $this->config('authLoginRedirect'));
        $controller->Auth->config('authenticate', [
            AuthComponent::ALL => ['userModel' => $this->config('userModel'), 'finder' => 'backendAuthUser'],
            'Form',
            //'Basic'
        ]);
        // Configure Backend Auth Storage
        $controller->Auth->config('storage', [
            'className' => 'Session',
            'key' => 'Backend.User',
            'redirect' => 'Backend.redirect'
        ]);

        // Configure Backend Authorization
        $controller->Auth->config('unauthorizedRedirect', $this->config('authUnauthorizedRedirect'));
        $controller->Auth->config('authorize', $this->config('authAuthorize'));
    }

    /**
     * @param Event $event
     */
    public function beforeRender(\Cake\Event\Event $event)
    {
        $controller = $event->subject();
        $controller->set('be_path', $this->config('backendPath'));
        $controller->set('be_title', $this->config('backendTitle'));
        $controller->set('be_dashboard_url', Router::url($this->config('dashboardUrl')));
        $controller->set('be_search_url', Router::url($this->config('searchUrl')));
    }

    /**
     * Convenience method to configure auth component
     *
     * @param $key
     * @param null $val
     * @param bool|true $merge
     */
    public function configAuth($key, $val = null, $merge = true)
    {
        $this->_controller->Auth->config($key, $val, $merge);
    }

    /**
     * Convenience method to configure flash component
     *
     * @param $key
     * @param null $val
     * @param bool|true $merge
     */
    public function configFlash($key, $val = null, $merge = true)
    {
        $this->_controller->Flash->config($key, $val, $merge);
    }

    /**
     * @param null $action
     * @deprecated Use ActionComponent instead
     */
    public function executeAction($action = null)
    {
        if (!$this->_registry->has('Action')) {
            throw new MissingComponentException(['class' => 'ActionComponent']);
        }

        return $this->_registry->get('Action')->execute($action);
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            //'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown',
        ];
    }
}
