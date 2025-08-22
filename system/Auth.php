<?php

class Auth
{
    public static function attempt($email, $password)
    {
        $user = new User();
        $userData = $user->where('email', $email);

        if ($userData && password_verify($password, $userData[0]['password'])) {
            $_SESSION['user_id'] = $userData[0]['id'];
            $_SESSION['user_role'] = $userData[0]['role'];
            return true;
        }

        return false;
    }

    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        if (self::check()) {
            $user = new User();
            return $user->find($_SESSION['user_id']);
        }
        return null;
    }

    public static function hasRole($role)
    {
        if (!self::check()) {
            return false;
        }

        return $_SESSION['user_role'] === $role || $_SESSION['user_role'] === 'admin';
    }

    public static function logout()
    {
        session_destroy();
        session_start();
        session_regenerate_id();
    }

    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }
}
