<?php

class PluginManager
{
    private $activePlugins = [];

    public function loadActivePlugins()
    {
        $plugins = $this->getActivePlugins();

        foreach ($plugins as $plugin) {
            $pluginFile = PLUGINS_PATH . '/' . $plugin . '/' . $plugin . '.php';
            if (file_exists($pluginFile)) {
                include_once $pluginFile;
                $this->activePlugins[] = $plugin;
            }
        }
    }

    private function getActivePlugins()
    {
        $db = Database::getInstance();
        $plugins = $db->fetchAll("SELECT name FROM plugins WHERE status = 'active'");
        return array_column($plugins, 'name');
    }

    public function activatePlugin($plugin)
    {
        $db = Database::getInstance();
        return $db->insert('plugins', [
            'name' => $plugin,
            'status' => 'active'
        ]);
    }

    public function deactivatePlugin($plugin)
    {
        $db = Database::getInstance();
        return $db->update('plugins', ['status' => 'inactive'], 'name = ?', [$plugin]);
    }
}
