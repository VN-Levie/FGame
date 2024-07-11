<?php

namespace Core;

class Application
{
    private $commands = [];

    public function addCommand($command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function run()
    {
        global $argv;

        if (count($argv) < 2) {
            echo "No command provided.\n";
            exit(1);
        }

        $commandName = $argv[1];
        if (isset($this->commands[$commandName])) {
            $this->commands[$commandName]->execute();
        } else {
            echo "Command not found.\n";
        }
    }
}
