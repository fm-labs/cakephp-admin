<?php

namespace Backend\Model\Table;

use Cake\ORM\Query;
use User\Model\Table\UsersTable as BaseUsersTable;

class UsersTable extends BaseUsersTable
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->entityClass('\User\Model\Entity\User');
    }

    public function findBackendAuthUser(Query $query, array $options)
    {
        $query
            ->where([
                'Users.login_enabled' => true,
                'Users.superuser' => true,
            ])
            ->contain(['Groups']);

        return $query;
    }
}
