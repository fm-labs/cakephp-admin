<?php

namespace Backend\Controller\Component;

use Backend\Backend;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\I18n\I18n;
use Cake\Log\Log;
use Cake\Network\Request;
use Cake\Routing\Router;
use User\Controller\Component\AuthComponent;

/**
 * Class BackendComponent
 *
 * @package Backend\Controller\Component
 * @property \Backend\Controller\Component\AuthComponent $Auth
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
    public static $authComponentClass = '\Backend\Controller\Component\AuthComponent';

    /**
     * @var array
     */
    public $components = [
        //'RequestHandler',
        'Flash' => ['className' => '\Backend\Controller\Component\FlashComponent', ['key' => 'backend']],
        'Auth' => ['className' => '\Backend\Controller\Component\AuthComponent', 'userModel' => 'Backend.Users']
    ];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'backendPath' => '/backend',
        'backendTitle' => 'Backend',
        'dashboardUrl' => ['_name' => 'admin:backend:dashboard'],
        'searchUrl' => ['plugin' => 'Backend', 'controller' => 'Search', 'action' => 'query'],
    ];

    /**
     * @var Controller
     */
    protected $_controller;

    //protected $_cookieName;

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        $controller = $this->_registry->getController();

        // Configure Backend from config
        // @TODO Remove config mapping
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

        // Configure RequestHandler component
        if (!$this->_registry->has('RequestHandler')) {
            //$this->_registry->load('RequestHandler');
        }

        // Configure Flash component
        if ($this->_registry->has('Flash') && !is_a($this->_registry->get('Flash'), static::$flashComponentClass)) {
            $this->_registry->unload('Flash');
        }
        $controller->loadComponent('Flash', ['className' => static::$flashComponentClass]);

        // Configure Auth component
        if ($this->_registry->has('Auth') && !is_a($this->_registry->get('Auth'), static::$authComponentClass)) {
            $this->_registry->unload('Auth');
        }
        $controller->loadComponent('Auth', ['className' => static::$authComponentClass]);

        // Configure UserSession component
        $controller->loadComponent('User.UserSession');
        $controller->components()->get('UserSession')->config([
            'maxLifetimeSec' => 15 * MINUTE,
            'sessionKey' => 'Backend.UserSession'
        ]);

        // Configure Security component
        if (Configure::read('Backend.Security.enabled') && !$controller->components()->has('Security')) {
            $controller->components()->load('Security');
        }

        // Add Iframe request detector
        $this->request->addDetector('iframe', function (Request $request) {
            return (bool)$request->query('iframe') == true || (bool)$request->param('iframe') == true;
        });

        // Attach listeners @todo Remove deprecated code
        foreach (Backend::getListeners('Controller') as $listenerClass) {
            try {
                $modobj = new $listenerClass();
                if ($modobj instanceof EventListenerInterface) {
                    $controller->eventManager()->on($modobj);
                }
            } catch (\Exception $ex) {
                Log::alert("Failed to load class $listenerClass: " . $ex->getMessage());
                continue;
            }
        }

        $controller->loadComponent('Backend.Action'); // @TODO Lazy load or remove

        //@todo use CORS-Component/-Middleware
        $this->response->header([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Methods' => 'Origin, Authorization, X-Requested-With, Content-Type, Accept'
        ]);

        // i18n
        //I18n::locale('en_US');

//        // Check backend cookie
//        $this->Cookie = $controller->loadComponent('Cookie');
//        $this->Cookie->config('path', '/admin/');
//        $this->Cookie->config([
//            'expires' => '+10 min',
//            'httpOnly' => true,
//            //'secure' => true
//        ]);
//        $cookieSeedData = [
//            $this->request->env('REMOTE_ADDR'),
//            $this->request->env('SERVER_NAME'),
//            $this->request->env('SERVER_PORT')
//        ];
//        $this->_cookieName = 'b' . substr(md5(join('|', $cookieSeedData)), 3);
//        if (!$this->Cookie->read($this->_cookieName)) {
//            $this->Cookie->write($this->_cookieName, ['cookie_is_set' => true, 'h' => $this->_makeCookieHash($cookieSeedData)]);
//            $controller->Flash->success("Cookie set");
//        } else {
//            $cookie = $this->Cookie->read($this->_cookieName);
//            if (!isset($cookie['h'])) {
//                $controller->Flash->error("Cookie hash missing");
//                $this->Cookie->delete($this->_cookieName);
//            } elseif ($cookie['h'] != $this->_makeCookieHash($cookieSeedData)) {
//                $controller->Flash->warn("Invalid cookie hash detected: " . $cookie['h'] . ' != ' . $this->_makeCookieHash($cookieSeedData));
//            } else {
//            }
//        }

        $this->_controller =& $controller;
    }

//    protected function _makeCookieHash($seedData)
//    {
//        return sha1(serialize($seedData));
//    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function init(Event $event)
    {
        /* @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();

        // Configure view
        $controller->viewBuilder()->className('Backend.Backend');
        $controller->viewBuilder()->helpers(['Html', 'Form' => ['className' => 'Backend\View\Helper\BackendFormHelper']], false);
        $controller->viewBuilder()->layout('Backend.admin');
        if (Configure::read('Backend.theme')) {
            $controller->viewBuilder()->theme(Configure::read('Backend.theme'));
        }

        // Handle iframe and ajax requests
        if ($this->request->is('iframe')) {
            $controller->viewBuilder()->layout('Backend.iframe');
        } elseif ($this->request->is('ajax') && !$this->_registry->has('RequestHandler')) {
            $controller->viewBuilder()->layout('Backend.ajax/admin');
        }

        if ($controller->Auth->user('locale') && $controller->Auth->user('locale') != I18n::locale()) {
            I18n::locale($controller->Auth->user('locale'));
        }
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $controller = $event->getSubject();

        if ($event->getSubject()->Auth && $event->getSubject()->Auth->user()) {
            $event->getSubject()->viewBuilder()->helpers(['Backend.BackendLayout']);

            $controller->set('be_title', $this->getConfig('backendTitle'));
            $controller->set('be_dashboard_url', Router::url($this->getConfig('dashboardUrl')));
        }
    }

    /**
     * Convenience method to configure auth component
     *
     * @param string $key Auth config key
     * @param null $val Config value
     * @param bool|true $merge Merge flag
     * @return void
     * @deprecated
     */
    public function configAuth($key, $val = null, $merge = true)
    {
        $this->_controller->Auth->config($key, $val, $merge);
    }

    /**
     * Convenience method to configure flash component
     *
     * @param string $key Flash config key
     * @param null $val Config value
     * @param bool|true $merge Merge flag
     * @return void
     * @deprecated
     */
    public function configFlash($key, $val = null, $merge = true)
    {
        $this->_controller->Flash->config($key, $val, $merge);
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Controller.initialize' => 'init',
            //'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown'
        ];
    }
}
