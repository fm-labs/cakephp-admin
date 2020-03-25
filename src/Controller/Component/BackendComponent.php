<?php

namespace Backend\Controller\Component;

use Backend\Backend;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Http\ServerRequest as Request;
use Cake\I18n\I18n;
use Cake\Log\Log;

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
     * @var array
     */
    public $components = [];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'flashClass' => '\Backend\Controller\Component\FlashComponent',
        'flashKey' => 'backend',
        'authClass' => '\Backend\Controller\Component\AuthComponent',
        'authModel' => 'Backend.Users',
        'userSessionMaxLifetimeSec' => 15 * MINUTE,
        'userSessionKey' => 'Backend.UserSession',
    ];

    //protected $_cookieName;

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        $controller = $this->getController();

        // Configure RequestHandler component
        if (!$this->_registry->has('RequestHandler')) {
            //$this->_registry->load('RequestHandler');
        }

        // Configure Flash component
        if ($this->_registry->has('Flash') && !is_a($this->_registry->get('Flash'), $this->_config['flashClass'])) {
            $this->_registry->unload('Flash');
        }
        $controller->loadComponent('Flash', ['className' => $this->_config['flashClass']]);
        $this->_registry->get('Flash')->setConfig([
            'key' => $this->_config['flashKey'],
        ]);

        // Configure Auth component
        if ($this->_registry->has('Auth') && !is_a($this->_registry->get('Auth'), $this->_config['authClass'])) {
            $this->_registry->unload('Auth');
        }
        $controller->loadComponent('Auth', ['className' => $this->_config['authClass']]);
        $this->_registry->get('Auth')->setConfig([
            'userModel' => $this->_config['authModel'],
        ]);

        // Configure UserSession component
        $controller->loadComponent('User.UserSession');
        $this->_registry->get('UserSession')->setConfig([
            'maxLifetimeSec' => $this->_config['userSessionMaxLifetimeSec'],
            'sessionKey' => $this->_config['userSessionKey'],
        ]);

        // Configure Security component
        // @todo @deprecated SecurityComponent will be dropped in CakePHP 4.0
        if (Configure::read('Backend.Security.enabled') && !$controller->components()->has('Security')) {
            $controller->components()->load('Security');
        }

        // Configure Action component
        if (isset($controller->actions)) {
            $controller->loadComponent('Backend.Action');
        }

        // Add Iframe request detector
        $controller->getRequest()->addDetector('iframe', function (Request $request) {
            return $request->getQuery('iframe') == true || $request->getParam('iframe') == true;
        });

        // Attach listeners
        // @todo Remove deprecated code
        foreach (Backend::getListeners('Controller') as $listenerClass) {
            try {
                $modobj = new $listenerClass();
                if ($modobj instanceof EventListenerInterface) {
                    $controller->getEventManager()->on($modobj);
                }
            } catch (\Exception $ex) {
                Log::alert("Failed to load class $listenerClass: " . $ex->getMessage());
                continue;
            }
        }

        //@todo move to CORS-Component/-Middleware
        $controller->setResponse($controller->getResponse()
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'POST, GET, OPTIONS')
            ->withHeader('Access-Control-Allow-Methods', 'Origin, Authorization, X-Requested-With, Content-Type, Accept'));

        // i18n
        //I18n::setLocale('en_US');

//        // Check backend cookie
//        $this->Cookie = $controller->loadComponent('Cookie');
//        $this->Cookie->setConfig('path', '/admin/');
//        $this->Cookie->config([
//            'expires' => '+10 min',
//            'httpOnly' => true,
//            //'secure' => true
//        ]);
//        $cookieSeedData = [
//            $this->getController()->getRequest()->env('REMOTE_ADDR'),
//            $this->getController()->getRequest()->env('SERVER_NAME'),
//            $this->getController()->getRequest()->env('SERVER_PORT')
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
    }

//    protected function _makeCookieHash($seedData)
//    {
//        return sha1(serialize($seedData));
//    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        /* @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();

        // Configure view
        $controller->viewBuilder()->setClassName('Backend.Backend');
        $controller->viewBuilder()->setLayout('Backend.admin');
        if (Configure::read('Backend.theme')) {
            $controller->viewBuilder()->setTheme(Configure::read('Backend.theme'));
        }

        // Handle iframe and ajax requests
        if ($this->getController()->getRequest()->is('iframe')) {
            $controller->viewBuilder()->setLayout('Backend.iframe');
        } elseif ($this->getController()->getRequest()->is('ajax') && !$this->_registry->has('RequestHandler')) {
            $controller->viewBuilder()->setLayout('Backend.ajax/admin');
        }

        if ($controller->Auth->user('locale') && $controller->Auth->user('locale') != I18n::getLocale()) {
            I18n::setLocale($controller->Auth->user('locale'));
        }
    }

    /**
     * @param Event $event The event object
     * @return void
     */
    public function beforeRender(\Cake\Event\EventInterface $event)
    {
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
        $this->getController()->Auth->setConfig($key, $val, $merge);
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
        $this->getController()->Flash->setConfig($key, $val, $merge);
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            //'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown'
        ];
    }
}
