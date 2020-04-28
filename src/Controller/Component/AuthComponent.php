<?php
declare(strict_types=1);

namespace Admin\Controller\Component;

/**
 * Class AuthComponent
 *
 * @package Admin\Controller\Component
 */
class AuthComponent extends \User\Controller\Component\AuthComponent
{
    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setConfig([
            'userModel' => 'Admin.Users',
            //'ajaxLoginAction' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'ajaxLogin'],
            'loginAction' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'login'],
            'loginRedirect' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'loginSuccess'],
            'logoutAction' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'logout'],
            'unauthorizedRedirect' => ['plugin' => 'Admin', 'controller' => 'Auth', 'action' => 'unauthorized'],
            'authorize' => ['Admin.Admin' /*, 'User.Roles'*/],
            //'flash' => 'auth',
        ]);
        $this->setConfig('authenticate', [
            AuthComponent::ALL => ['userModel' => $this->getConfig('userModel'), 'finder' => 'adminAuthUser'],
            'Form',
            //'Basic'
        ], false);
        $this->setConfig('storage', [
            'className' => 'Session',
            'key' => 'Admin.User',
            'redirect' => 'Admin.redirect',
        ], false);
    }
}
