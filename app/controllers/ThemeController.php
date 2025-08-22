<?php

class ThemeController extends Controller
{
    private $themes_dir;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->themes_dir = __DIR__ . '/../../themes'; // Adjust path as needed
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleThemeAction();
        }

        if (isset($_SESSION['success'])) {
            $success_message = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error_message = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        // Get list of themes (folders with index.php)
        $themes = $this->getThemesList();

        // Get active theme from options table
        $active_theme = Setting::get('active_theme', 'default');

        $this->view('admin/themes/index', [
            'themes' => $themes,
            'active_theme' => $active_theme,
            'success_message' => $success_message ?? null,
            'error_message' => $error_message ?? null
        ]);
    }

    private function handleThemeAction()
    {
        try {
            if (isset($_POST['action']) && isset($_POST['theme'])) {
                $theme = $_POST['theme'];
                $action = $_POST['action'];

                if ($action === 'activate') {
                    // Update or insert active theme in options table
                    Setting::set('active_theme', $theme);
                    $success_message = "Theme '$theme' activated successfully!";
                } elseif ($action === 'delete' && $theme !== 'default') {
                    // Delete theme folder (except default)
                    $theme_path = $this->themes_dir . '/' . $theme;
                    if (is_dir($theme_path) && $this->isValidTheme($theme_path)) {
                        $this->deleteDirectory($theme_path);
                        $success_message = "Theme '$theme' deleted successfully!";
                    } else {
                        throw new Exception("Invalid or non-existent theme: $theme");
                    }
                } else {
                    throw new Exception("Invalid action or attempt to delete default theme.");
                }
            } else {
                throw new Exception("No action or theme specified.");
            }


            $this->redirect('/admin/themes', [
                'success' => $success_message
            ]);
        } catch (Exception $e) {

            $this->redirect('/admin/themes', [
                'error' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    private function getThemesList()
    {
        $themes = [];
        if (is_dir($this->themes_dir)) {
            $dirs = scandir($this->themes_dir);
            foreach ($dirs as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($this->themes_dir . '/' . $dir)) {
                    if ($this->isValidTheme($this->themes_dir . '/' . $dir)) {
                        $themes[] = $dir;
                    }
                }
            }
        }
        return $themes;
    }

    private function isValidTheme($path)
    {
        return is_file($path . '/index.php');
    }

    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }
}