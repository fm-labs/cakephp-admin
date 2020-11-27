<?php
declare(strict_types=1);

namespace Admin\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\RequestPolicyInterface;
use Cake\Http\ServerRequest;

class AdminRequestPolicy implements RequestPolicyInterface
{
    /**
     * Method to check if the request can be accessed
     *
     * @param \Authorization\IdentityInterface|null $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool|\Authorization\Policy\ResultInterface
     */
    public function canAccess(?IdentityInterface $identity, ServerRequest $request)
    {
        // always allow access to the auth controller
        if (
            $request->getParam('prefix') === "Admin"
            && $request->getParam('plugin') == 'Admin'
            && $request->getParam('controller') == 'Auth'
        ) {
            return true;
        }

        if (!$identity) {
            return false;
        }

        if ($identity->id === 1 || $identity->username === "root" || $identity->superuser === true) {
            return true;
        }

        return false;
    }
}
