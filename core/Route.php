<?php

namespace Core;

use Models\Model;
use Models\User;
use View;

class StoredRoute
{
    public $_requestMethod;
    public $name;
    public $path;
    public $controller;
    public $methodName;

    public $prefix;

    public function name($name)
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
            $method = $storedRoute->methodName;
            $controller->$method();
        } else {
            return View::abort(404, "Route <strong>{$path}</strong> not found.");
        }
        // print_r($routes);
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
            $storedRoute->_requestMethod = 'GET';
            $storedRoute->path = $path;
            $storedRoute->controller = $controller;
            $storedRoute->methodName = $method;
            $storedRoute->prefix = $prefix;


            // echo $path;
            $this->routes['GET'][$path] = $storedRoute;
            return $storedRoute;
        } else {
            return View::abort(500, "Route::get() <br>Controller <strong>{$controller}</strong> not found.");
        }
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
            $storedRoute->_requestMethod = 'POST';
            $storedRoute->path = $path;
            $storedRoute->controller = $controller;
            $storedRoute->methodName = $method;
            $storedRoute->prefix = $prefix;

            // echo $path;
            $this->routes['POST'][$path] = $storedRoute;
            return $storedRoute;
        } else {
            return View::abort(500, "Route::post() <br>Controller <strong>{$controller}</strong> not found.");
        }
    }

    //get route url bằng name và bind các tham số
    public  function route($name = null, $params = []) : string
    {
        //nếu $routeName = null thì trả về url hiện tại và bỏ / ở cuối
        if (!$name) {
            return rtrim(DOMAIN . $_SERVER['REQUEST_URI'], '/');
        }
        // echo '123' . $name . '<br>';
        $routes = $this->routes['GET'];
        $found = false;
        $url = DOMAIN;

        //get
        foreach ($routes as $route) {
            // echo "$route->name<br>"; 
            if ($route->name == $name) {

                $path = $route->path;
                foreach ($params as $key => $value) {
                    $path = str_replace("{{$key}}", $value, $path);
                }
                $url .= $path;
                $found = true;
                break;
            }
        }
        //post
        if (!$found) {
            $routes = $this->routes['POST'];

            foreach ($routes as $route) {
                // echo "$route->name<br>";
                if ($route->name == $name) {
                    $path = $route->path;
                    foreach ($params as $key => $value) {
                        $path = str_replace("{{$key}}", $value, $path);
                    }
                    $url .= $path;
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            // return View::abort(500, "Route::route() <br>Route <strong>{$name}</strong> not found.");
            // return View::renderError(new \Exception("Route::route() <br>Route <strong>'{$name}'</strong> not found."));
            // throw
            throw new \Exception("Route::route() <br>Route <strong>'{$name}'</strong> not found.");
        } else {
            //add params
            if (count($params) > 0) {
                $url .= '?';
                foreach ($params as $key => $value) {
                    $url .= "$key=$value&";
                }
                $url = rtrim($url, '&');
            }
            return $url;
        }
    }
    //prefix route
    public  function prefix($prefix, $callback)
    {
        $callback($this, $prefix);
        // print_r($this);
    }
}
