<?php
declare(strict_types=1);

namespace Backend\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;

/**
 * Class BackendShell
 * @package Backend\Shell
 * @property \Backend\Shell\Task\RootUserTask $RootUser
 */
class BackendShell extends Shell
{
    /**
     * @var array
     */
    public $tasks = [
        'Backend.RootUser',
    ];

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('rootUser', [
            'help' => 'Execute The RootUser Task.',
            'parser' => $this->RootUser->getOptionParser(),
        ]);

        return $parser;
    }
}
