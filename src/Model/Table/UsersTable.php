<?php

namespace Backend\Model\Table;

use Cake\ORM\Query;
use User\Model\Table\UsersTable as BaseUsersTable;

class UsersTable extends BaseUsersTable
{
    /**
     * {@inheritDoc}
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setEntityClass('\User\Model\Entity\User');
    }

    /**
     * Finder method for Backend's Auth component
     *
     * @param Query $query The query object
     * @param array $options Finder options
     * @return Query
     */
    public function findBackendAuthUser(Query $query, array $options)
    {
        $query
            ->where([
                'Users.login_enabled' => true,
                'Users.superuser' => true,
            ])
            ->contain(['UserGroups']);

        return $query;
    }
}
