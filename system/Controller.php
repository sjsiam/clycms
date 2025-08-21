<?php

abstract class Controller
{
    protected $db;
    protected $theme;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function view($template, $data = [])
    {
        extract($data);

        $viewFile = APP_PATH . '/views/' . $template . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$template}");
        }

        include $viewFile;
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function renderTheme($template, $data = [])
    {
        $themeFile = THEMES_PATH . '/' . $this->theme . '/' . $template . '.php';

        if (!file_exists($themeFile)) {
            $themeFile = THEMES_PATH . '/default/' . $template . '.php';
        }

        if (!file_exists($themeFile)) {
            throw new Exception("Theme template not found: {$template}");
        }

        extract($data);
        include $themeFile;
    }

    // To do  - Add Authentication and Authorization methods

}
