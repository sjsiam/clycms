<?php

require __DIR__ . '/vendor/autoload.php';

$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');



if ($url) {
    $url = rtrim($url, '/');
    $url = explode('/', $url);



    if (isset($url[0])) {
        $controllerName = 'App\\Controllers\\' . ucfirst($url[0]);

        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            $methodName = $url[1] ?? 'home';

            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
            } else {
                echo "Method {$methodName} not found in controller {$controllerName}";
            }
        }else{
            echo "Controller {$controllerName} not found!";
        }
    } else {
        unset($url);
    }
} else {

    $controller = new App\Controllers\Index();
    $controller->home();
}
