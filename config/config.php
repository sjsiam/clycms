<?php

$config = [];

$config['database'] = [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_DATABASE'] ?? 'clycms',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['CHARSET'] ?? 'utf8mb4'
];

$config['app'] = [
    'name' => 'ClyCMS',
    'url' => $_ENV['APP_URL'] ?? 'https://clycms.com',
    'theme' => $_ENV['APP_THEME'] ?? 'default',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
];

foreach ($config as $key => $value) {
    Config::set($key, $value);
}

date_default_timezone_set($config['app']['timezone']);

define('DEBUG', $_ENV['DEBUG'] ?? false);
