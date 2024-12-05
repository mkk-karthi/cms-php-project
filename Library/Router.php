<?php

class Router
{
    protected $routes = [];

    public function get($route, $callback)
    {
        $route = trim($route, "/");
        $this->routes["get"][$route] = $callback;
    }

    public function post($route, $callback)
    {
        $route = trim($route, "/");
        $this->routes["post"][$route] = $callback;
    }

    // check the route and call the associate funtion
    public function match()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $url = trim($_SERVER['REQUEST_URI'], "/");

        // check method
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $target) {

                // check params and route
                $pattern = preg_replace('/\/{([^\/]+)}/', '/([^/]+)', $route);

                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    array_shift($matches);  // remove first element

                    // call to the target action
                    call_user_func_array($target, $matches);
                }
            }
        }
        throw new Exception('Route not found');
    }
}
