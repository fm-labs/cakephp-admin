<?php

namespace Backend\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Backend\Controller\BackendControllerInterface;
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
    public static $flashComponentClass = '\Backend\Controller\Component\FlashComponent';

    public static $authComponentClass = '\User\Controller\Component\AuthComponent';

    //public $components = ['Backend.Flash', 'User.Auth'];

    protected $_defaultConfig = [
        'authLoginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
        'authLoginRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
        'authLogoutAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
        'authUnauthorizedRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'unauthorized'],
        'authAuthorize' => ['Controller', 'Backend.Backend', 'User.Roles'],
        'userModel' => 'Backend.Users'
    ];

    /**
     * @var Controller
     */
    protected $_controller;

    public function initialize(array $config)
    {
        $controller = $this->_registry->getController();

        // Configure Backend FlashComponent
        if ($this->_registry->has('Flash') || !is_a($this->_registry->get('Flash'), static::$flashComponentClass)) {
            $this->_registry->unload('Flash');
            $controller->Flash = $this->_registry->load('Flash', [
                'className' => static::$flashComponentClass,
                'key' => 'backend',
                //'class' => 'info',
                'plugin' => 'Backend',
                //'params' => ['dismiss' => true],
                'elementMap' => [
                    'default' => ['class' => 'info'],
                    'info' => ['element' => 'default', 'class' => 'info'],
                    'warning' => ['element' => 'default', 'class' => 'warning'],
                    'error' => ['element' => 'default', 'class' => 'danger']
                ]
            ]);
        }

        // Configure Backend Authentication
        if (!$this->_registry->has('Auth') || !is_a($this->_registry->get('Auth'), static::$authComponentClass)) {
            $this->_registry->unload('Auth');
            $controller->Auth = $this->_registry->load('Auth', [
                'className' => static::$authComponentClass,
            ]);
        }
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

        // Configure controller
        $controller->viewBuilder()->className('Backend.Backend');
        $controller->viewBuilder()->layout('Backend.admin');

        // Iframe request detector
        $this->request->addDetector('iframe', function($request) {
            return (bool) $this->request->query('iframe');
        });

        // Handle iframe and ajax requests
        if ($this->request->is('iframe')) {
            $controller->viewBuilder()->layout('Backend.iframe');
        }
        elseif ($this->request->is('ajax')) {
            $controller->viewBuilder()->layout('Backend.ajax');
        }

        $this->_controller =& $controller;
    }

    public function beforeFilter(Event $event)
    {
    }

    public function startup(Event $event)
    {
    }


    public function beforeRender(\Cake\Event\Event $event)
    {
        $controller = $event->subject();
        $controller->set('be_title', Configure::read('Backend.Dashboard.title'));
    }

    public function authConfig($key, $val = null, $merge = true)
    {
        $this->_controller->Auth->config($key, $val, $merge);
    }

    public function flashConfig($key, $val = null, $merge = true)
    {
        $this->_controller->Flash->config($key, $val, $merge);
    }

    public function implementedEvents()
    {
        $events = parent::implementedEvents();

        //@TODO Implement implementedEvents (disabled)
        //$events['User.login'] = 'onUserLogin';

        return $events;
    }

    public function onUserLogin(Event $event)
    {
        //@TODO Implement event callback for 'User.login' (disabled)
        //Log::debug('Backend:Event: User.login');
    }
}
