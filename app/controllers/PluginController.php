<?php

class PluginController extends Controller
{
    private $pluginManager;

    public function __construct()
    {
        parent::__construct();
        $this->pluginManager = new PluginManager();

        // Check if user is admin
        if (!Auth::check() || !Auth::hasRole('admin')) {
            header('Location: /admin/login');
            exit;
        }
    }

    public function index()
    {
        $plugins = $this->pluginManager->scanPlugins();

        $this->view('admin/plugins/index', [
            'title' => 'Plugins',
            'plugins' => $plugins
        ]);
    }

    public function activate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plugin = $_POST['plugin'] ?? '';

            if ($plugin) {
                $result = $this->pluginManager->activatePlugin($plugin);

                if ($result) {
                    $_SESSION['success'] = "Plugin '$plugin' activated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to activate plugin '$plugin'.";
                }
            }
        }

        header('Location: /admin/plugins');
        exit;
    }

    public function deactivate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plugin = $_POST['plugin'] ?? '';

            if ($plugin) {
                $result = $this->pluginManager->deactivatePlugin($plugin);

                if ($result) {
                    $_SESSION['success'] = "Plugin '$plugin' deactivated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to deactivate plugin '$plugin'.";
                }
            }
        }

        header('Location: /admin/plugins');
        exit;
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plugin = $_POST['plugin'] ?? '';

            if ($plugin) {
                $result = $this->pluginManager->deletePlugin($plugin);

                if ($result) {
                    $_SESSION['success'] = "Plugin '$plugin' deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete plugin '$plugin'.";
                }
            }
        }

        header('Location: /admin/plugins');
        exit;
    }

    public function settings($pluginName)
    {
        $plugin = $this->pluginManager->getPluginData($pluginName);
        $settings = $this->pluginManager->getPluginSettings($pluginName);

        if (!$plugin) {
            $_SESSION['error'] = "Plugin not found.";
            header('Location: /admin/plugins');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle settings update
            $newSettings = $_POST['settings'] ?? [];
            $result = $this->pluginManager->updatePluginSettings($pluginName, $newSettings);

            if ($result) {
                $_SESSION['success'] = "Plugin settings updated successfully!";
                header('Location: /admin/plugins');
                exit;
            } else {
                $_SESSION['error'] = "Failed to update plugin settings.";
            }
        }

        $this->view('admin/plugins/settings', [
            'title' => 'Plugin Settings - ' . $plugin['name'],
            'plugin' => $plugin,
            'settings' => $settings
        ]);
    }

    public function install()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plugin = $_POST['plugin'] ?? '';

            if ($plugin) {
                $result = $this->pluginManager->activatePlugin($plugin);

                if ($result) {
                    $_SESSION['success'] = "Plugin '$plugin' installed and activated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to install plugin '$plugin'.";
                }
            }
        }

        header('Location: /admin/plugins');
        exit;
    }
}
