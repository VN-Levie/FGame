<?php

namespace Commands;

class GreetCommand
{
    public function getName()
    {
        return 'greet';
    }

    public function execute()
    {
        echo "Hello, User!\n";
    }
}
