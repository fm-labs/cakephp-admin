<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/18/15
 * Time: 11:25 PM
 */

namespace Backend\Shell;

use Cake\Console\Shell;
use Backend\Model\Table\UsersTable;

/**
 * Class BackendShell
 * @package Backend\Shell
 * @property UsersTable $Users
 */
class BackendShell extends Shell
{
    protected $_tasks  = [
        'setup_root_user' => 'Setup root user',
        'cache_clear' => 'Clear cache',
        'quit' => 'Quit'
    ];

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        /*
        $parser->epilog([
            '-----------------',
            '# BACKEND SHELL #',
            '-----------------'
        ]);
        */
        $parser->addArgument('task', [
            'help' => 'Backend task to be executed',
            'required' => true,
            'choices' => array_keys($this->_tasks)
        ])->description(__("Select from the list of available backend tasks"));

        return $parser;
    }

    public function setupRootUser()
    {
        $this->loadModel('Backend.Users');
        $root = $this->Users->createRootUser();
        if ($root === false) {
            $this->error("Failed to create root user: Root user already exists!");
        }

        $this->out("<success>Root user created with default password.</success>");
    }

    public function cacheClear()
    {
        $this->out('Sorry, cache clearing is not implemented yet');
    }

    public function quit()
    {
        $this->err('ByeBye');
    }
}