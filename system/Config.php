<?php

class Config
{
    private static $config = [];

    public static function load($file)
    {
        $configFile = CONFIG_PATH . '/' . $file . '.php';
        if (file_exists($configFile)) {
            self::$config[$file] = include $configFile;
        }
    }

    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = self::$config;

        foreach ($keys as $segment) {
            if (isset($config[$segment])) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }

        return $config;
    }

    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }
}
