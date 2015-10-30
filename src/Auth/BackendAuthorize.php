<?php

namespace Backend\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Network\Request;

class BackendAuthorize extends BaseAuthorize
{
    /**
     * Constructor
     *
     * @param ComponentRegistry $registry
     * @param array $config
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
    }

    /**
     * Authorize user for request
     *
     * @param array $user Current authenticated user
     * @param \Cake\Network\Request $request Request instance.
     * @return bool
     */
    public function authorize($user, Request $request)
    {
        $userId = $user['id'];
        if (!$userId) {
            return null;
        }

        // root is always authorized
        if ($userId === 1 && $user['username'] === 'root') {
            return true;
        }

        // configured backend user ids
        $backendUsersIds = (array) Configure::read('Backend.Users');
        if (in_array($userId, $backendUsersIds)) {
            return true;
        }

//        // user group authorization
//        if ($user['_groups'] &&
//            is_array($user['_groups']) &&
//            //isset($user['_groups'][0]) &&
//            in_array('backend', $user['_groups'])
//        ) {
//            return true;
//        }

        return null;
    }
}