<?php
/**
 * Plugin System Demo
 * 
 * This file demonstrates how to test the ClyCMS plugin system.
 * 
 * To test:
 * 1. Make sure your database is set up with the plugins table
 * 2. Visit /admin/plugins in your browser
 * 3. You should see the Hello World and Content Filter plugins
 * 4. Install and activate them to see them in action
 */

// Test the plugin system
if (php_sapi_name() === 'cli') {
    echo "Plugin System Demo\n";
    echo "==================\n\n";
    
    // Check if plugins directory exists
    if (is_dir(__DIR__)) {
        echo "✓ Plugins directory found\n";
        
        // List available plugins
        $plugins = glob(__DIR__ . '/*', GLOB_ONLYDIR);
        echo "Available plugins:\n";
        foreach ($plugins as $plugin) {
            $pluginName = basename($plugin);
            echo "- $pluginName\n";
        }
        
        echo "\nTo test the plugin system:\n";
        echo "1. Set up your database\n";
        echo "2. Visit /admin/plugins in your browser\n";
        echo "3. Install and activate plugins\n";
        echo "4. Check the admin area and public pages for plugin effects\n";
        
    } else {
        echo "✗ Plugins directory not found\n";
    }
} else {
    echo "<h1>Plugin System Demo</h1>";
    echo "<p>This file is for demonstration purposes.</p>";
    echo "<p>To test the plugin system, visit <a href='/admin/plugins'>/admin/plugins</a></p>";
}
?> 