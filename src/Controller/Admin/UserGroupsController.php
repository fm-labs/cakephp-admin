<?php
namespace Backend\Controller\Admin;

/**
 * UserGroups Controller
 *
 * @property \User\Model\Table\UserGroupsTable $UserGroups
 */
class UserGroupsController extends AppController
{
    /**
     * @var string
     */
    public $modelClass = 'User.UserGroups';

    /**
     * @var array
     */
    public $actions = [
        'index' => 'Backend.Index',
        'add' => 'Backend.Add',
        'view' => 'Backend.View',
        'edit' => 'Backend.Edit',
        'delete' => 'Backend.Delete'
    ];
}
