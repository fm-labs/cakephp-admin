<?php
namespace Backend\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * BackendUser Entity.
 */
class BackendUser extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'first_name' => true,
        'last_name' => true,
        'username' => true,
        'password' => true,
        'email' => true,
        'active' => true,
        'last_login_datetime' => true,
    ];

    protected function _getFullName()
    {
        return $this->_properties['first_name'] . '  ' . $this->_properties['last_name'];
    }

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
