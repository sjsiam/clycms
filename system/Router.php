<?php

class Router
{
    private $routes = [];

    public function add($pattern, $callback, $method = 'GET')
    {
        $this->routes[] = [
            'pattern' => $pattern,
            'callback' => $callback,
            'method' => strtoupper($method)
        ];
    }

    public function dispatch()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = preg_replace('/^index\.php\//', '', $uri);

        foreach ($this->routes as $route) {
            $pattern = $this->convertPattern($route['pattern']);
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->callAction($route['callback'], $matches);
            }
        }
    }

    private function convertPattern($pattern)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        return '#^' . $pattern . '$#';
    }

    private function callAction($callback, $params = [])
    {
        if (strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);

            if (class_exists($controller)) {
                $instance = new $controller();
                if (method_exists($instance, $method)) {
                    return call_user_func_array([$instance, $method], $params);
                }
            }
        }

        throw new Exception("Route not found: $callback");
    }
}
