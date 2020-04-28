<?php
declare(strict_types=1);

namespace Admin\Shell\Task;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;

/**
 * @property \Admin\Model\Table\UsersTable $Users
 */
class RootUserTask extends Shell
{
    /**
     * {@inheritDoc}
     */
    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__d('admin', "Create admin root user"))
            ->addOption('email', [
                'help' => 'Root user email',
                'short' => 'e',
            ])
            ->addOption('password', [
                'help' => 'Root user password',
                'short' => 'p',
            ]);

        return $parser;
    }

    /**
     * @return void
     */
    public function main()
    {
        $this->out("-- Setup root user --");
        foreach ($this->args as $key => $val) {
            $this->out("Arg: $key - $val");
        }

        $this->loadModel('Admin.Users');
        $rootCount = $this->Users->find()->where(['Users.username' => 'root'])->count();
        if ($rootCount > 0) {
            $this->abort('Root user already exists');
        }

        do {
            $email = trim($this->in("Enter root email address: "));
            $strlen = strlen($email);
        } while ($strlen < 1);

        do {
            $pass1 = trim($this->in("Choose root password: "));
            if (strlen($pass1) < 1) {
                $this->out("Please enter a password");
                continue;
            }

            $pass2 = trim($this->in("Repeat password: "));

            $match = ($pass1 === $pass2);
            if (!$match) {
                $this->out("Passwords do not match. Please try again.");
            }
        } while (!$match);

        $root = $this->Users->createRootUser($email, $pass1);
        if ($root === false) {
            $this->abort("Failed to create root user");
        }

        $this->out("<success>Root user successfully created!</success>");
    }
}
