<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/18/15
 * Time: 11:25 PM
 */

namespace Backend\Shell;

use Cake\Console\Shell;
use User\Model\Table\UsersTable;

/**
 * Class BackendShell
 * @package Backend\Shell
 * @property UsersTable $Users
 */
class BackendShell extends Shell
{
    protected $_tasks  = [
        'setup_root_user' => 'Setup root user',
        'quit' => 'Quit'
    ];

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addArgument('task', [
            'help' => 'Backend task to be executed',
            'required' => false,
            'choices' => array_keys($this->_tasks)
        ])->description(__("Select from the list of available backend tasks"));

        return $parser;
    }

    public function setupRootUser()
    {
        $this->out("-- Setup root user --");

        $rootCount = $this->Users->find()->where(['Users.username' => 'root'])->count();
        if ($rootCount > 0) {
            $this->error('Root user already exists');
        }

        do {

            $pass1 = trim($this->in("Choose root password: "));
            $pass2 = trim($this->in("Repeat password: "));

            $match = ($pass1 === $pass2);
            if (!$match) {
                $this->out("Passwords do not match. Please try again.");
            }

        } while (!$match);



        $this->loadModel('User.Users');
        $root = $this->Users->createRootUser($pass1);
        if ($root === false) {
            $this->error("Failed to create root user");
        }

        $this->out("<success>Root user successfully created!</success>");
    }

    public function quit()
    {
        $this->out('ByeBye');
    }
}