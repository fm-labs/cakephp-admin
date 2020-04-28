<?php
declare(strict_types=1);

namespace Admin\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;

/**
 * Class AdminAuthorize
 * s
 * @package Admin\Auth
 */
class AdminAuthorize extends BaseAuthorize
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
    }

    /**
     * Authorize user for request
     *
     * @param array $user Current authenticated user
     * @param \Cake\Http\ServerRequest $request Request instance.
     * @return bool
     */
    public function authorize($user, \Cake\Http\ServerRequest $request): bool
    {
        if (!$user['id']) {
            return false;
        }

        // allow root
        if ($user['username'] === 'root') {
            return true;
        }

        // allow superusers
        if (isset($user['is_superuser']) && $user['is_superuser'] === true) {
            return true;
        }

        // configured admin users
        //@TODO Refactor this dirty UserId-hack with http basic auth
        $adminUsers = (array)Configure::read('Admin.Users');
        if (in_array($user['username'], $adminUsers)) {
            return true;
        }

        return false;
    }
}
