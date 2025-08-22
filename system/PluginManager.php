<?php

class PluginManager
{
    private $activePlugins = [];
    private $hooks = [];
    private $filters = [];
    private $pluginData = [];

    public function loadActivePlugins()
    {
        $plugins = $this->getActivePlugins();

        foreach ($plugins as $plugin) {
            $pluginFile = PLUGINS_PATH . '/' . $plugin . '/' . $plugin . '.php';
            if (file_exists($pluginFile)) {
                include_once $pluginFile;
                $this->activePlugins[] = $plugin;

                $this->loadPluginData($plugin);
            }
        }
    }

    private function getActivePlugins()
    {
        $db = Database::getInstance();
        $plugins = $db->fetchAll("SELECT name FROM plugins WHERE status = 'active'");
        return array_column($plugins, 'name');
    }

    public function getAllPlugins()
    {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM plugins ORDER BY name");
    }

    public function getPluginData($pluginName)
    {
        if (isset($this->pluginData[$pluginName])) {
            return $this->pluginData[$pluginName];
        }
        return null;
    }

    private function loadPluginData($pluginName)
    {
        $pluginFile = PLUGINS_PATH . '/' . $pluginName . '/' . $pluginName . '.php';
        if (file_exists($pluginFile)) {
            $content = file_get_contents($pluginFile);

            // Extract plugin header information
            preg_match('/Plugin Name:\s*(.+)$/m', $content, $name);
            preg_match('/Plugin URI:\s*(.+)$/m', $content, $uri);
            preg_match('/Description:\s*(.+)$/m', $content, $description);
            preg_match('/Version:\s*(.+)$/m', $content, $version);
            preg_match('/Author:\s*(.+)$/m', $content, $author);
            preg_match('/Author URI:\s*(.+)$/m', $content, $authorUri);

            $this->pluginData[$pluginName] = [
                'name' => trim($name[1] ?? $pluginName),
                'plugin_name' => trim($pluginName),
                'uri' => trim($uri[1] ?? ''),
                'description' => trim($description[1] ?? ''),
                'version' => trim($version[1] ?? '1.0.0'),
                'author' => trim($author[1] ?? ''),
                'author_uri' => trim($authorUri[1] ?? ''),
                'plugin_file' => $pluginName . '.php'
            ];
        }
    }

    public function activatePlugin($plugin)
    {
        $db = Database::getInstance();

        $this->loadPluginData($plugin);
        $data = $this->getPluginData($plugin);
        $pluginName = $data['plugin_name'] ?? $plugin;

        $existing = $db->fetch("SELECT * FROM plugins WHERE name = ?", [$pluginName]);

        if ($existing) {
            $result = $db->update('plugins', ['status' => 'active'], 'name = ?', [$pluginName]);
            return $result;
        } else {
            $result = $db->insert('plugins', [
                'name' => $pluginName,
                'version' => $data['version'] ?? '1.0.0',
                'status' => 'active',
                'settings' => json_encode([])
            ]);
            return $result;
        }
    }

    public function deactivatePlugin($plugin)
    {
        $db = Database::getInstance();


        $this->loadPluginData($plugin);
        $data = $this->getPluginData($plugin);
        $pluginName = $data['plugin_name'] ?? $plugin;

        return $db->update('plugins', ['status' => 'inactive'], 'name = ?', [$pluginName]);
    }

    public function deletePlugin($plugin)
    {
        $db = Database::getInstance();

        $this->loadPluginData($plugin);
        $data = $this->getPluginData($plugin);
        $pluginName = $data['name'] ?? $plugin;

        return $db->delete('plugins', 'name = ?', [$pluginName]);
    }

    public function updatePluginSettings($plugin, $settings)
    {
        $db = Database::getInstance();

        // Get plugin data first to get the actual plugin name from header
        $this->loadPluginData($plugin);
        $data = $this->getPluginData($plugin);
        $pluginName = $data['plugin_name'] ?? $plugin;

        return $db->update('plugins', ['settings' => json_encode($settings)], 'name = ?', [$pluginName]);
    }

    public function getPluginSettings($plugin)
    {
        $db = Database::getInstance();

        // Get plugin data first to get the actual plugin name from header
        $this->loadPluginData($plugin);
        $data = $this->getPluginData($plugin);
        $pluginName = $data['plugin_name'] ?? $plugin;

        $result = $db->fetch("SELECT settings FROM plugins WHERE name = ?", [$pluginName]);

        if ($result && $result['settings']) {
            return json_decode($result['settings'], true);
        }
        return [];
    }

    // Hook system
    public function addHook($hook, $callback, $priority = 10)
    {
        if (!isset($this->hooks[$hook])) {
            $this->hooks[$hook] = [];
        }

        $this->hooks[$hook][] = [
            'callback' => $callback,
            'priority' => $priority
        ];

        // Sort by priority
        usort($this->hooks[$hook], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }

    public function doHook($hook, ...$args)
    {
        if (isset($this->hooks[$hook])) {
            foreach ($this->hooks[$hook] as $hookData) {
                call_user_func_array($hookData['callback'], $args);
            }
        }
    }

    public function applyFilter($filter, $value, ...$args)
    {
        if (isset($this->filters[$filter])) {
            foreach ($this->filters[$filter] as $filterData) {
                $value = call_user_func_array($filterData['callback'], array_merge([$value], $args));
            }
        }
        return $value;
    }

    public function addFilter($filter, $callback, $priority = 10)
    {
        if (!isset($this->filters[$filter])) {
            $this->filters[$filter] = [];
        }

        $this->filters[$filter][] = [
            'callback' => $callback,
            'priority' => $priority
        ];

        // Sort by priority
        usort($this->filters[$filter], function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });
    }

    public function getActivePluginsList()
    {
        return $this->activePlugins;
    }

    public function isPluginActive($plugin)
    {
        return in_array($plugin, $this->activePlugins);
    }

    public function scanPlugins()
    {
        $plugins = [];
        $pluginDirs = glob(PLUGINS_PATH . '/*', GLOB_ONLYDIR);

        foreach ($pluginDirs as $dir) {
            $pluginName = basename($dir);
            $pluginFile = $dir . '/' . $pluginName . '.php';

            if (file_exists($pluginFile)) {
                $this->loadPluginData($pluginName);
                $data = $this->getPluginData($pluginName);

                $db = Database::getInstance();
                $existing = $db->fetch("SELECT * FROM plugins WHERE name = ?", [$data['plugin_name'] ?? $pluginName]);

                if ($existing) {
                    $data['status'] = $existing['status'];
                    $data['id'] = $existing['id'];
                    $data['installed'] = true;
                } else {
                    $data['status'] = 'not_installed';
                    $data['installed'] = false;
                }

                $plugins[] = $data;
            }
        }

        return $plugins;
    }
}
