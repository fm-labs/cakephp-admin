<?php
declare(strict_types=1);

namespace Admin\Model\Table;

use Cake\ORM\Query;
use User\Model\Entity\User;
use User\Model\Table\UsersTable as BaseUsersTable;

class UsersTable extends BaseUsersTable
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        //@todo Use AdminUser entity
        $this->setEntityClass(User::class);
    }

    /**
     * Finder method for Admin's Auth component
     *
     * @param \Cake\ORM\Query $query The query object
     * @param array $options Finder options
     * @return \Cake\ORM\Query
     */
    public function findAuthUser(Query $query, array $options)
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
