<?php

namespace Backend\Controller\Component;

use Backend\Action\ActionRegistry;
use Backend\Action\Interfaces\EntityActionInterface;
use Backend\Action\Interfaces\TableActionInterface;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Network\Response;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
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
     * @var ActionRegistry
     */
    protected $_actionRegistry;

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
            $controller->Flash = $this->_registry->load('Flash', [
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
            $controller->Auth = $this->_registry->load('Auth', [
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

        $this->_controller =& $controller;

        $this->_initActions();
    }

    /**
     * Initialize action registry
     */
    protected function _initActions()
    {
        $this->_actionRegistry = new ActionRegistry();

        // use controller actions, if defined
        if ($this->_controller->actions) {
            $this->actions = $this->_controller->actions;
        }

        // normalize action configs and add actions to actions registry
        $actions = [];
        foreach ($this->actions as $action => $actionConfig) {
            if (is_string($actionConfig)) {
                $actionConfig = ['className' => $actionConfig];
            }
            $this->_actionRegistry->load($action, $actionConfig);
            $actions[$action] = $actionConfig;
        }
        $this->actions = $actions;
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
    public function startup(Event $event)
    {
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
     * @param $action
     * @return bool
     */
    public function hasAction($action)
    {
        return $this->_actionRegistry->has($action);
    }

    /**
     * @param $action
     * @return null|object
     */
    public function getAction($action)
    {
        return $this->_actionRegistry->get($action);
    }

    /**
     * @return array
     */
    public function listActions()
    {
        return $this->_actionRegistry->loaded();
    }

    /**
     * @param $action
     * @return array
     */
    public function getActionUrl($action)
    {
        $actionObj = $this->getAction($action);
        if ($actionObj instanceof EntityActionInterface) {
            return ['action' => $action, ':id'];
        } else {
            return ['action' => $action];
        }
    }

    /**
     * @param $action
     * @return mixed
     */
    public function executeAction($action = null)
    {
        if ($action === null) {
            $action = $this->request->params['action'];
        }

        if ($this->_actionRegistry->has($action)) {
            $config = $this->actions[$action];
            $actionObj = $this->_actionRegistry->get($action);

            $event = $this->_registry->getController()->dispatchEvent('Backend.beforeAction', [ 'name' => $action, 'action' => $actionObj ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            //@TODO Refactor with ActionView
            //--
            $templatePath = 'Action';
            if ($this->request->params['prefix']) {
                $templatePath = Inflector::camelize($this->request->params['prefix']) . '/' . $templatePath;
            }

            list($plugin, ) = pluginSplit($config['className']);
            $template = ($plugin) ? $plugin . '.' . $action : $action;

            $this->_controller->viewBuilder()->templatePath($templatePath);
            $this->_controller->viewBuilder()->template($template);
            //--

            // attach Action instance to controllers event manager
            if ($actionObj instanceof EventListenerInterface) {
                $this->_controller->eventManager()->on($actionObj);
            }

            $response = $actionObj->execute($this->_controller);

            // detach Action instance from controllers event manager
            if ($actionObj instanceof EventListenerInterface) {
                $this->_controller->eventManager()->off($actionObj);
            }

            $event = $this->_registry->getController()->dispatchEvent('Backend.afterAction', [ 'name' => $action, 'action' => $actionObj ]);
            if ($event->result instanceof Response) {
                return $event->result;
            }

            return $response;
        }

        throw new \RuntimeException('Action ' . $action . ' not loaded');
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown',
            'Backend.Table.Actions.get' => [ 'callable' => 'getTableActions', 'priority' => 5 ],
            'Backend.Table.RowActions.get' => [ 'callable' => 'getTableRowActions', 'priority' => 5 ],
            'Backend.Entity.Actions.get' => [ 'callable' => 'getEntityActions', 'priority' => 5 ]
        ];
    }

    /**
     * @param Event $event
     */
    public function getTableActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $event->subject()->Backend->getAction($action);
            if ($action == "index" || (!($_action instanceof TableActionInterface))) {
                continue;
            }
            $event->result[] = [$_action->getLabel(), ['action' => $action], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event
     */
    public function getTableRowActions(Event $event)
    {
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if ($action == "index" || (!($_action instanceof EntityActionInterface))) {
                continue;
            }
            $event->result[] = [$_action->getLabel(), ['action' => $action, ':id'], $_action->getAttributes()];
        }
    }

    /**
     * @param Event $event
     */
    public function getEntityActions(Event $event)
    {
        $entity = $event->data['entity'];
        foreach ($this->listActions() as $action) {
            $_action = $this->getAction($action);
            if (($_action instanceof TableActionInterface)) {
                $event->result[] = [$_action->getLabel(), ['action' => $action]];
            }
            if (($_action instanceof EntityActionInterface)) {
                $event->result[] = [$_action->getLabel(), ['action' => $action, $entity->id]];
            }
        }
    }
}
