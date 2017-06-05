<?php

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
    /**
     * @var array
     */
    public $tasks = [
        'Backend.RootUser'
    ];

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
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
