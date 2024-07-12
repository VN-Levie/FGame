<?php
//limit word($test, $limt = 10)
function limit_word($string, $limit = 10)
{
    $words = explode(' ', $string);
    $words = array_slice($words, 0, $limit);
    return implode(' ', $words);
}