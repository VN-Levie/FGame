<?php

namespace Core;

class ConsoleColor
{
    const RESET = "\033[0m";
    const BLACK = "\033[0;30m";
    const RED = "\033[0;31m";
    const GREEN = "\033[0;32m";
    const YELLOW = "\033[0;33m";
    const BLUE = "\033[0;34m";
    const MAGENTA = "\033[0;35m";
    const CYAN = "\033[0;36m";
    const WHITE = "\033[0;37m";

    public static function colorize($text, $color)
    {
        return $color . $text . self::RESET;
    }
}
