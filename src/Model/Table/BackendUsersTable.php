<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/6/15
 * Time: 10:56 PM
 */

namespace Backend\Model\Table;

use User\Model\Table\UsersTable;
use Cake\Log\Log;

class BackendUsersTable extends UsersTable
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
        $this->entityClass('Backend\Model\Entity\BackendUser');
        //$this->table('users');
    }

    public function validationAdd(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->requirePresence('password1', 'create')
            ->notEmpty('password1')
            ->add('password1', 'password', [
                'rule' => 'validateNewPassword1',
                'provider' => 'table',
                'message' => __('Invalid password')
            ])
            ->requirePresence('password2', 'create')
            ->notEmpty('password2')
            ->add('password2', 'password', [
                'rule' => 'validateNewPassword2',
                'provider' => 'table',
                'message' => __('Passwords do not match')
            ])
            ->add('is_login_allowed', 'valid', ['rule' => 'boolean'])
            //->requirePresence('is_login_allowed', 'create')
            ->notEmpty('is_login_allowed');


        if (static::$emailAsUsername) {
            $validator->add('username', 'email', [
                'rule' => ['email'],
                'message' => __('The provided email address is invalid')
            ]);
        }


        return $validator;
    }

    public function add(array $data)
    {
        $user = $this->newEntity(null);
        $user->accessible('username', true);
        $user->accessible('password1', true);
        $user->accessible('password2', true);
        $this->patchEntity($user, $data, ['validate' => 'add']);
        if ($user->errors()) {
            return $user;
        }

        $user->password = $user->password1;

        if ($this->save($user)) {
            Log::info('[plugin:user] New backend user added with ID ' . $user->id);
        }
        return $user;
    }
}
