<?php
declare(strict_types=1);

namespace Admin\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\Table;

/**
 * RootUser command.
 */
class RootUserCommand extends Command
{
    /**
     * @var \Cake\ORM\Table|null
     */
    protected ?Table $Users;

    /**
     * @return string
     */
    public static function defaultName(): string
    {
        return 'admin root-user';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

//        $parser
//            ->setDescription(__d('admin', "Create admin root user"))
//            ->addOption('email', [
//                'help' => 'Root user email',
//                'short' => 'e',
//            ])
//            ->addOption('password', [
//                'help' => 'Root user password',
//                'short' => 'p',
//            ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $io->out('-- Setup root user --');
        foreach ($args as $key => $val) {
            $io->out("Arg: $key - $val");
        }

        $this->Users = $this->fetchTable('Admin.Users');
        $rootCount = $this->Users->find()->where(['Users.username' => 'root'])->count();
        if ($rootCount > 0) {
            $io->abort('Root user already exists');
        }

        do {
            $email = trim($io->ask('Enter root email address: '));
            $strlen = strlen($email);
        } while ($strlen < 1);

        do {
            $pass1 = trim($io->ask('Choose root password: '));
            if (strlen($pass1) < 1) {
                $io->out('Please enter a password');
                continue;
            }

            $pass2 = trim($io->ask('Repeat password: '));

            $match = ($pass1 === $pass2);
            if (!$match) {
                $io->out('Passwords do not match. Please try again.');
            }
        } while (!$match);

        $root = $this->Users->createRootUser($email, $pass1);
        if ($root === false) {
            $io->abort('Failed to create root user');
        }

        $io->success('Root user successfully created!');
    }
}
