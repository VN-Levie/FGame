<?php

namespace Core;

use Models\Model;
use Models\User;
use View;

class StoredRoute
{
    public $name;
    public $path;
    public $controller;
    public $method;
    public $prefix;

    public  function name($name)
    {
        $this->name = $name;
        return $this;
    }
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
}
class Route
{


    public $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function run()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = rtrim($path, '/');
        $path = parse_url($path, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $routes =  $this->routes[$method];
        $storedRoute = $routes[$path] ?? null;
        if ($storedRoute) {
            $controller = new $storedRoute->controller();
            $method = $storedRoute->method;
            $controller->$method();
        } else {
            return View::abort(404, "Route <strong>{$path}</strong> not found.");
        }
    }

    public  function get($path, $controller, $method, $prefix = null)
    {
        $path = rtrim($path, '/');
        if (file_exists("controllers/$controller.php")) {
            if (!method_exists(new $controller(), $method)) {
                return View::abort(500, "Route::get() <br>Method <strong>{$method}</strong> not found in controller <strong>{$controller}</strong>.");
            }
            $path = $prefix ? $prefix . $path : $path;
            // echo $path;
            $storedRoute = new StoredRoute();
            $storedRoute->path = $path;
            $storedRoute->controller = $controller;
            $storedRoute->method = $method;
            $storedRoute->prefix = $prefix;

            // echo $path;
            $this->routes['GET'][$path] = $storedRoute;
            return $storedRoute;
        } else {
            return View::abort(500, "Route::get() <br>Controller <strong>{$controller}</strong> not found.");
        }
    }


    //get route url bằng name và bind các tham số
    public  function route($name, $params = [])
    {
        // echo '123' . $name . '<br>';
        $routes = $this->routes['GET'];
        $routes = array_merge($routes, $this->routes['POST']);
        $found = false;
        foreach ($routes as $route) {
            if ($route->name == $name) {
                $path = $route->path;
                foreach ($params as $key => $value) {
                    $path = str_replace("{{$key}}", $value, $path);
                }
                echo (DOMAIN . $path);
                $found = true;
                break;
            }
        }
        if (!$found) {
            // return View::abort(500, "Route::route() <br>Route <strong>{$name}</strong> not found.");
            return View::renderError(new \Exception("Route::route() <br>Route <strong>'{$name}'</strong> not found."));
            // throw
            // throw new \Exception("Route::route() <br>Route <strong>'{$name}'</strong> not found.");
        }
    }

    //prefix route
    public  function prefix($prefix, $callback)
    {
        $callback($this, $prefix);
    }





    public  function post($path, $controller, $method, $prefix = null)
    {
        $path = rtrim($path, '/');
        if (file_exists("controllers/$controller.php")) {
            if (!method_exists(new $controller(), $method)) {
                return View::abort(500, "Route::post() <br>Method <strong>{$method}</strong> not found in controller <strong>{$controller}</strong>.");
            }
            $path = $prefix ? $prefix . $path : $path;
            $storedRoute = new StoredRoute();
            $storedRoute->path = $path;
            $storedRoute->controller = $controller;
            $storedRoute->method = $method;
            $storedRoute->prefix = $prefix;

            // echo $path;
            $this->routes['POST'][$path] = $storedRoute;
            return $storedRoute;
        } else {
            return View::abort(500, "Route::post() <br>Controller <strong>{$controller}</strong> not found.");
        }
    }
}
