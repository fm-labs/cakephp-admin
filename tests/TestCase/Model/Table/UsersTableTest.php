<?php
namespace User\Test\TestCase\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Backend\Model\Table\UsersTable;

/**
 * User\Model\Table\UsersTable Test Case
 *
 * @property UsersTable $Users
 */
class UsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Users' => 'plugin.user.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        //UsersTable::$emailAsUsername = false;
        $config = TableRegistry::exists('Users') ? [] : [
            'className' => 'Backend\Model\Table\UsersTable'
        ];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    public function testCreateRootUser()
    {
        $this->Users->deleteAll([1 => 1]);

        // test with valid username and password
        $user = $this->Users->createRootUser();
        $this->assertInstanceOf('Backend\\Model\\Entity\\User', $user);
        $this->assertEmpty($user->errors());
        $this->assertTrue((new DefaultPasswordHasher())->check('t00rt00r', $user->password));
    }

}
