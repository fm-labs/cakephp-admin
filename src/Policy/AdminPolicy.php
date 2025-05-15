<?php
declare(strict_types=1);

namespace Admin\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\Result;

class AdminPolicy
{
    /**
     * @param \Authorization\IdentityInterface $user The user object.
     * @param object $context Authorization context
     * @return \Authorization\Policy\Result
     */
    public function canAccess(IdentityInterface $user, object $context): Result
    {
        if ($user->id === 1 || $user->username === 'root') {
            return new Result(false, 'not-root');
        }

        // Results let you define a 'reason' for the failure.
        return new Result(false, 'not-admin');
    }
}
