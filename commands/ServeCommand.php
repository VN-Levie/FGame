<?php

namespace Commands;

use Core\ConsoleColor;

class ServeCommand
{
    public function getName()
    {
        return 'serve';
    }

    public function execute()
    {
        global $argv;

        $host = 'localhost';
        $port = 8000;

        foreach ($argv as $arg) {
            if (strpos($arg, '--host=') === 0) {
                $host = substr($arg, strlen('--host='));
            }
            if (strpos($arg, '--port=') === 0) {
                $port = substr($arg, strlen('--port='));
            }
        }
        //kiểm tra những port mặc định cùa windows
        // if ($port < 1024) {
        //     echo ConsoleColor::colorize("Ports below 1024 require administrative privileges\n", ConsoleColor::RED);
        //     exit(1);
        // }
        //kiểm tra port có đang được sử dụng không
        $fp = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($fp) {
            fclose($fp);
            echo ConsoleColor::colorize("Port $port is already in use\n", ConsoleColor::RED);
            exit(1);
        }

        $command = sprintf('php -S %s:%d', $host, $port);
        echo ConsoleColor::colorize("Starting server at http://$host:$port\n", ConsoleColor::GREEN);
        echo ConsoleColor::colorize("Press Ctrl+C to stop the server\n", ConsoleColor::YELLOW);
        passthru($command);
    }
}
