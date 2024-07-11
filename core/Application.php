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
            $this->showHelp();
            exit(1);
        }

        $commandName = $argv[1];
        if (isset($this->commands[$commandName])) {
            $this->commands[$commandName]->execute();
        } else {
            echo "Command not found.\n";
            $this->showHelp();
        }
    }

    private function showHelp()
    {
        echo "Available commands:\n";
        foreach ($this->commands as $command) {
            echo "  " . $command->getName() . "\n";
        }
    }
}
