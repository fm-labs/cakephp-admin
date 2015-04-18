<?php
namespace Backend\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Controller\Component\FlashComponent;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\Admin\AppController as AppAdminController;

/**
 * Class BackendAppController
 *
 * Use this class as a base controller for app controllers
 * which should run in backend context
 *
 * @package Backend\Controller
 *
 * @property AuthComponent $Auth
 * @property FlashComponent $Flash
 * @property PaginatorComponent $Paginator
 */
abstract class BaseController extends AppAdminController
{
    public $layout = "Backend.admin";

    public $helpers = [
        'Html',
        'SemanticUi.Ui',
        'Form' => [
            'templates' => 'Backend.semantic-form-templates',
            'widgets' => ['button' => ['SemanticUi\View\Widget\ButtonWidget']]
        ],
        'Paginator' => [
            'templates' => 'Backend.semantic-paginator-templates'
        ]
    ];

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @throws \Cake\Core\Exception\Exception
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        // Attach Backend's FlashComponent
        if ($this->components()->has('Flash')) {
            $this->components()->unload('Flash');
        }
        $this->loadComponent('Flash', [
            'className' => '\Backend\Controller\Component\FlashComponent',
            'key' => 'backend',
            'plugin' => 'Backend'
        ]);

        // Check for AuthComponent
        if (!$this->components()->has('Auth')) {
            throw new Exception('Backend: Authentication not configured');
        }

        //@TODO inject Backend's Authorizatin Provider
        $authorize = $this->Auth->config('authorize');
        if (empty($authorize)) {
            throw new Exception('Backend: Authorization not configured');
        }

        $authModel = $this->Auth->config('authenticate')['all']['userModel'];
    }

    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
    }

    public function beforeRender(\Cake\Event\Event $event)
    {
        parent::beforeRender($event);

        $this->set('be_title', Configure::read('Backend.title'));
        $this->set('be_dashboard_url', Configure::read('Backend.dashboardUrl'));
        $this->set('be_auth_login_url', '/login');
        $this->set('be_auth_logout_url', '/logout');
    }

    /**
     * Backend fallback controller authorization
     *
     * If controller authorization has been enabled and no other subclasses handles `isAuthorized`
     * ALL of the following fallback validations will be performed:
     *
     * 1) root: TRUE, if user with Id '1' or username 'root'
     * 2) userfield: TRUE, if user with field 'is_backend_user' set to TRUE
     * 3) usergroup: TRUE, if user is member of group 'backend' listed in field 'groups'
     *
     * @return bool
     */
    public function isAuthorized()
    {
        //@TODO Make controller authorization configurable

        // root is always authorized
        if ($this->Auth->user('id') === 1 || $this->Auth->user('username') === 'root') {
            return true;
        }

        // user field authorization
        if ($this->Auth->user('is_backend_user') === true) {
            return true;
        }

        // user group authorization
        if ($this->Auth->user('groups') &&
            is_array($this->Auth->user('groups')) &&
            //isset($this->Auth->user('groups')[0]) &&
            in_array('backend', $this->Auth->user('groups'))
        ) {
            return true;
        }
    }
}
