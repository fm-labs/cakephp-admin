<?php

namespace Backend\Controller\Component;

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

        $this->setConfig([
            'userModel' => 'Backend.Users',
            //'ajaxLoginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'ajaxLogin'],
            'loginAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'login'],
            'loginRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'loginSuccess'],
            'logoutAction' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'logout'],
            'unauthorizedRedirect' => ['plugin' => 'Backend', 'controller' => 'Auth', 'action' => 'unauthorized'],
            'authorize' => ['Backend.Backend' /*, 'User.Roles'*/],
            //'flash' => 'auth',
        ]);
        $this->setConfig('authenticate', [
            AuthComponent::ALL => ['userModel' => $this->getConfig('userModel'), 'finder' => 'backendAuthUser'],
            'Form',
            //'Basic'
        ], false);
        $this->setConfig('storage', [
            'className' => 'Session',
            'key' => 'Backend.User',
            'redirect' => 'Backend.redirect'
        ], false);
    }
}
