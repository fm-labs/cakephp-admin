<?php
declare(strict_types=1);

namespace Admin\Controller\Admin;

/**
 * Class AppController
 *
 * @package Admin\Controller\Admin
 * @property \Admin\Controller\Component\ActionComponent $Action
 * @property \Admin\Controller\Component\AdminComponent $Admin
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 * @property \Authorization\Controller\Component\AuthorizationComponent $Authorization
 * @property \User\Controller\Component\UserSessionComponent $UserSession
 */
class AppController extends \App\Controller\Admin\AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        // make sure the Admin component is loaded
        $this->components()->load('Admin');
    }
}
