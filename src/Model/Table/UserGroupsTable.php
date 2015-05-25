<?php

namespace Backend\Model\Table;

use User\Model\Table\UserGroupsTable as BaseUserGroupsTable;
use Cake\Log\Log;

class UserGroupsTable extends BaseUserGroupsTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->entityClass('Backend\Model\Entity\UserGroup');
    }

}
