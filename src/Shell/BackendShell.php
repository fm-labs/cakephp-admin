<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/18/15
 * Time: 11:25 PM
 */

namespace Backend\Shell;

use Backend\Shell\Task\RootUserTask;
use Cake\Console\Shell;
use User\Model\Table\UsersTable;

/**
 * Class BackendShell
 * @package Backend\Shell
 * @property UsersTable $Users
 * @property RootUserTask
 */
class BackendShell extends Shell
{
    public $tasks = [
        'Backend.RootUser'
    ];

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('rootUser', [
            'help' => 'Execute The RootUser Task.',
            'parser' => $this->RootUser->getOptionParser()
        ]);
        return $parser;
    }
}