<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

use Authentication\AuthenticationService;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Http\ServerRequest as Request;
use Cake\Routing\Router;

/**
 * Class AdminComponent
 *
 * @package Admin\Controller\Component
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @property \Admin\Controller\Component\FlashComponent $Flash
 */
class AdminComponent extends Component
{
    /**
     * @var array
     */
    public $components = ['Admin.Flash'];

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'flashClass' => '\Admin\Controller\Component\FlashComponent',
        'flashKey' => 'admin',
        'authClass' => '\Admin\Controller\Component\AuthComponent',
        'authModel' => 'Admin.Users',
        'userSessionMaxLifetimeSec' => 15 * MINUTE,
        'userSessionKey' => 'Admin.UserSession',
    ];

    //protected $_cookieName;

    /**
     * @param array $config The component config.
     * @return void
     * @throws \Exception
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $controller = $this->getController();

        // Configure RequestHandler component
        //if (!$this->_registry->has('RequestHandler')) {
        //    $this->_registry->load('RequestHandler');
        //}

        // Configure Flash component
        if ($this->_registry->has('Flash') && !is_a($this->_registry->get('Flash'), $this->_config['flashClass'])) {
            $this->_registry->unload('Flash');
        }
        $controller->loadComponent('Flash', ['className' => $this->_config['flashClass']]);
        $this->_registry->get('Flash')->setConfig([
            'key' => $this->_config['flashKey'],
        ]);

        //if (Configure::read('Admin.Auth.authenticationEnabled')) {
            $this->Authentication = $controller->loadComponent('Authentication.Authentication');
            $this->Authentication->setConfig([
                //'logoutRedirect' => false,
                //'requireIdentity' => true,
                'identityAttribute' => \Admin\AdminPlugin::AUTH_IDENTITY_ATTRIBUTE,
                //'identityCheckEvent' => 'Controller.initialize',
            ]);
            $authService = $this->Authentication->getAuthenticationService();
        if ($authService instanceof AuthenticationService) {
            $authService->setConfig([
                //'authenticators' => [],
                //'identifiers' => [],
                //'identityClass' => Identity::class,
                //'identityAttribute' => 'identity',
                'identityAttribute' => \Admin\AdminPlugin::AUTH_IDENTITY_ATTRIBUTE,
                //'queryParam' => null,
                'unauthenticatedRedirect' => Router::url([
                    'plugin' => 'Admin',
                    'controller' => 'Auth',
                    'action' => 'login',
                    'prefix' => 'Admin',
                ]),
            ]);
        }
        //}

        if (Configure::read('Admin.Auth.authorizationEnabled')) {
            $this->Authorization = $controller->loadComponent('Authorization.Authorization');
            $this->Authorization->setConfig([
                'identityAttribute' => \Admin\AdminPlugin::AUTH_IDENTITY_ATTRIBUTE,
                //'serviceAttribute' => 'authorization',
                //'authorizationEvent' => 'Controller.startup',
                //'skipAuthorization' => [],
                //'authorizeModel' => [],
                //'actionMap' => [],
            ]);
        }
        //$controller->loadComponent('User.Auth');

//        // @todo Configure UserSession component
//        $controller->loadComponent('User.UserSession');
//        $this->_registry->get('UserSession')->setConfig([
//            'maxLifetimeSec' => $this->_config['userSessionMaxLifetimeSec'],
//            'sessionKey' => $this->_config['userSessionKey'],
//        ]);

//        // Configure Security component
//        // @todo @deprecated SecurityComponent will be dropped in CakePHP 4.0
//        if (Configure::read('Admin.Security.enabled') && !$controller->components()->has('Security')) {
//            $controller->components()->load('Security');
//        }

        // Configure Action component
        if (isset($controller->actions)) {
            $controller->loadComponent('Admin.Action');
        }

        // Add Iframe request detector
        $controller->getRequest()->addDetector('iframe', function (Request $request) {
            return $request->getQuery('iframe') == true || $request->getParam('iframe') == true;
        });

//        // Attach listeners
//        // @todo Remove deprecated code
//        foreach (Admin::getListeners('Controller') as $listenerClass) {
//            try {
//                $modobj = new $listenerClass();
//                if ($modobj instanceof EventListenerInterface) {
//                    $controller->getEventManager()->on($modobj);
//                }
//            } catch (\Exception $ex) {
//                Log::alert("Failed to load class $listenerClass: " . $ex->getMessage());
//                continue;
//            }
//        }

        //@todo move to CORS-Component/-Middleware
        $controller->setResponse($controller->getResponse()
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'POST, GET, OPTIONS')
            ->withHeader(
                'Access-Control-Allow-Methods',
                'Origin, Authorization, X-Requested-With, Content-Type, Accept'
            ));

        // i18n
        //I18n::setLocale('en_US');

//        // Check admin cookie
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
     * @param \Cake\Event\EventInterface $event The event object
     * @return void
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();

        // Configure view
        $controller->viewBuilder()->setClassName('Admin.Admin');
        $controller->viewBuilder()->setLayout('Admin.admin');
        $controller->viewBuilder()->setTheme(Configure::read('Admin.theme'));

        // Handle iframe and ajax requests
        if ($this->getController()->getRequest()->is('iframe')) {
            $controller->viewBuilder()->setLayout('Admin.iframe');
        } elseif ($this->getController()->getRequest()->is('ajax') && !$this->_registry->has('RequestHandler')) {
            $controller->viewBuilder()->setLayout('Admin.ajax/admin');
        }

        //@todo Use locale selector middleware
        //if ($controller->Auth->user('locale') && $controller->Auth->user('locale') != I18n::getLocale()) {
        //    I18n::setLocale($controller->Auth->user('locale'));
        //}

        $user = $controller->getRequest()->getAttribute('adminIdentity');
        if ($user && !$user->is_superuser) {
            $this->Flash->warning('Non super-user detectected');
        }
    }

    /**
     * @return void
     */
    public function startup(): void
    {
    }

    /**
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            'Controller.startup' => 'startup',
            //'Controller.beforeRender' => 'beforeRender',
            //'Controller.beforeRedirect' => 'beforeRedirect',
            //'Controller.shutdown' => 'shutdown'
        ];
    }
}
