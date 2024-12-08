<?php

class Router
{
    protected $routes = [];

    public function get($route, $callback, $middleware = [])
    {
        $route = trim($route, "/");
        $this->routes["get"][$route] = [
            "callback" => $callback,
            "middleware" => $middleware
        ];
    }

    public function post($route, $callback, $middleware = [])
    {
        $route = trim($route, "/");
        $this->routes["post"][$route] = [
            "callback" => $callback,
            "middleware" => $middleware
        ];
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

                    $callback = $target["callback"];
                    $middlewares = $target["middleware"];

                    // handle middleware
                    foreach ($middlewares as $middleware) {
                        Helper::middleware($middleware);
                    }

                    // call to the target action
                    if (gettype($callback) == "array") {

                        // check it's callback
                        if (is_callable([$callback[0], $callback[1]])) {
                            call_user_func_array([$callback[0], $callback[1]], $matches);
                        }
                    } else {
                        // check it's callback
                        if (is_callable($callback)) {
                            call_user_func_array($callback, $matches);
                        }
                    }
                }
            }
        }
        Helper::jsonResponse(["code" => 404, "message" => "Route not found"], 404);
    }
}
