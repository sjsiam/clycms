<?php

require __DIR__ . '/../bootstrap/app.php';

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('SYSTEM_PATH', ROOT_PATH . '/system');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PLUGINS_PATH', ROOT_PATH . '/plugins');
define('THEMES_PATH', ROOT_PATH . '/themes');

session_start();

spl_autoload_register(function ($class) {
    $paths = [
        SYSTEM_PATH . '/',
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/helpers/'
    ];

    foreach ($paths as $path) {
        $file = $path . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Initialize the application
$app = new Application();
$app->run();
