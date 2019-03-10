<?php

namespace Backend\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;

/**
 * Class AuthComponent
 *
 * @package Backend\Controller\Component
 */
class AuthComponent extends \User\Controller\Component\AuthComponent
{
    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->config([
            'userModel' => 'Backend.Users',
            //'ajaxLoginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'ajaxLogin'],
            'loginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
            'loginRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
            'logoutAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
            'unauthorizedRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'unauthorized'],
            'authorize' => ['Backend.Backend' /*, 'User.Roles'*/],
            //'flash' => 'auth',
        ]);
        $this->config('authenticate', [
            AuthComponent::ALL => ['userModel' => $this->config('userModel'), 'finder' => 'backendAuthUser'],
            'Form',
            //'Basic'
        ], false);
        $this->config('storage', [
            'className' => 'Session',
            'key' => 'Backend.User',
            'redirect' => 'Backend.redirect'
        ], false);
    }
}
