<?php

class Setting
{
    private static $settings = [];

    public static function get($key, $default = null)
    {
        if (isset(self::$settings[$key])) {
            return self::$settings[$key];
        }

        try {
            $db = Database::getInstance();
            $result = $db->fetchOne("SELECT option_value FROM options WHERE option_name = ?", [$key]);
            self::$settings[$key] = $result['option_value'] ?? $default;
        } catch (Exception $e) {
            self::$settings[$key] = $default;
            if (defined('DEBUG') && DEBUG) {
                error_log("Failed to fetch setting '$key': " . $e->getMessage());
            }
        }

        return self::$settings[$key];
    }

    public static function set($key, $value)
    {
        try {
            $db = Database::getInstance();
            $existing = $db->fetchOne("SELECT id FROM options WHERE option_name = ?", [$key]);
            if ($existing) {
                $db->query("UPDATE options SET option_value = ? WHERE option_name = ?", [$value, $key]);
            } else {
                $db->query("INSERT INTO options (option_name, option_value, autoload) VALUES (?, ?, ?)", [$key, $value, 'yes']);
            }
            self::$settings[$key] = $value;
        } catch (Exception $e) {
            if (defined('DEBUG') && DEBUG) {
                error_log("Failed to set setting '$key': " . $e->getMessage());
            }
        }
    }
}
