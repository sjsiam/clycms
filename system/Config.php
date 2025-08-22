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

    public static function getActiveTheme()
    {
        static $active_theme = null;
        if ($active_theme !== null) {
            return $active_theme;
        }

        try {
            $db = Database::getInstance();
            $result = $db->fetchOne("SELECT option_value FROM options WHERE option_name = ?", ['active_theme']);
            $active_theme = $result['option_value'] ?? 'default';
        } catch (Exception $e) {
            $active_theme = 'default';
            if (DEBUG) {
                error_log("Failed to fetch active theme: " . $e->getMessage());
            }
        }

        return $active_theme;
    }
}
