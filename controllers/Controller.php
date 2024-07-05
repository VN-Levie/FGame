<?php

namespace Controllers;

use View;

class Controller
{

    public function view($view, $data = [], $hide_header = false)
    {
        return View::render($view, $data, $hide_header);
    }


    public function json($data)
    {
        header('Content-Type: application/json');
        die (json_encode($data));
    }

    public function dd($data)
    {
        echo "<pre>";
        var_dump($data);
        die;
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        die;
    }

    public function back()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die;
    }

    public function error($message)
    {
        die($message);
    }

    public function abort($code)
    {
        http_response_code($code);
        die;
    }
}
