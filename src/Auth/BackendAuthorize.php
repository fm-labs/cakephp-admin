<?php
declare(strict_types=1);

namespace Backend\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Http\ServerRequest as Request;

/**
 * Class BackendAuthorize
 * s
 * @package Backend\Auth
 */
class BackendAuthorize extends BaseAuthorize
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
    public function authorize($user, Request $request)
    {
        if (!$user['id']) {
            return null;
        }

        // allow root
        if ($user['username'] === 'root') {
            return true;
        }

        // allow superusers
        if (isset($user['is_superuser']) && $user['is_superuser'] === true) {
            return true;
        }

        // configured backend users
        //@TODO Refactor this dirty UserId-hack with http basic auth
        $backendUsers = (array)Configure::read('Backend.Users');
        if (in_array($user['username'], $backendUsers)) {
            return true;
        }

        return null;
    }
}
