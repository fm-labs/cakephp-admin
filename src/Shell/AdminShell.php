<?php
declare(strict_types=1);

namespace Admin\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;

/**
 * Class AdminShell
 * @package Admin\Shell
 * @property \Admin\Shell\Task\RootUserTask $RootUser
 */
class AdminShell extends Shell
{
    /**
     * @var array
     */
    public $tasks = [
        'Admin.RootUser',
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
