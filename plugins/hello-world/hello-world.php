<?php

/**
 * Plugin Name: Hello World
 * Plugin URI: https://example.com/hello-world
 * Description: A simple Hello World plugin that demonstrates the ClyCMS plugin system
 * Version: 1.0.0
 * Author: ClyCMS Team
 * Author URI: https://clycms.com
 */

// Prevent direct access
if (!defined('PLUGINS_PATH')) {
    exit;
}

class HelloWorldPlugin
{
    private $pluginManager;

    public function __construct()
    {
        // Access the global application instance
        global $app;
        if (isset($app) && method_exists($app, 'getPluginManager')) {
            $this->pluginManager = $app->getPluginManager();
        } else {
            return;
        }
        $this->init();

    }

    public function init()
    {
        // Add hooks
        $this->pluginManager->addHook('init', [$this, 'onInit']);
        $this->pluginManager->addHook('admin_head', [$this, 'onAdminHead']);
        $this->pluginManager->addHook('clycms_head', [$this, 'onPublicHead']);
        $this->pluginManager->addHook('clycms_footer', [$this, 'onPublicFooter']);

        // Add filters
        $this->pluginManager->addFilter('content_filter', [$this, 'filterContent']);


        // Add admin menu
        $this->addAdminMenu();
    }

    public function onInit()
    {
        // Plugin initialization code
        // This hook is called when the application initializes
    }

    public function onAdminHead()
    {
        // Add custom CSS/JS to admin area
        echo '<style>
            .hello-world-notice {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
                padding: 10px;
                margin: 10px 0;
                border-radius: 4px;
            }
        </style>';

        echo '<script>
            console.log("Hello World Plugin loaded in admin area!");
        </script>';
    }

    public function onPublicHead()
    {
        // Add custom CSS/JS to public area
        echo '<meta name="hello-world-plugin" content="Hello World Plugin is active" />';
        echo '<style>
            .hello-world-content::before {
                content: "👋 Hello from Hello World Plugin! ";
                color: #007cba;
                font-weight: bold;
            }
        </style>';
    }

    public function onPublicFooter()
    {
        // Add content to public footer
        echo '<div style="text-align: center; padding: 20px; background: #f8f9fa; margin-top: 30px;">
            <p>This site is powered by <strong>ClyCMS</strong> with the <strong>Hello World Plugin</strong> 🚀</p>
        </div>';
    }

    public function filterContent($content)
    {
        // Filter post content to add a greeting
        $settings = $this->pluginManager->getPluginSettings('hello-world');
        $greeting = $settings['greeting'] ?? 'Hello from Hello World Plugin!';

        if (!empty($content)) {
            $content = '<div class="hello-world-content">' . $content . '</div>';
        }

        return $content;
    }

    public function addAdminMenu()
    {
        // Admin menu functionality can be implemented here
        // For now, we'll use the built-in plugin settings system
    }

    public function adminPage()
    {
        // Admin settings page
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $greeting = $_POST['greeting'] ?? 'Hello from Hello World Plugin!';
            $this->pluginManager->updatePluginSettings('hello-world', ['greeting' => $greeting]);
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }

        $settings = $this->pluginManager->getPluginSettings('hello-world');
        $greeting = $settings['greeting'] ?? 'Hello from Hello World Plugin!';

        echo '<div class="wrap">
            <h1>Hello World Plugin Settings</h1>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row">Greeting Message</th>
                        <td>
                            <input type="text" name="greeting" value="' . htmlspecialchars($greeting) . '" class="regular-text" />
                            <p class="description">This message will be displayed in your content.</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                </p>
            </form>
        </div>';
    }

    public function addMetaTags()
    {
        // Add meta tags to public pages
        echo '<meta name="generator" content="ClyCMS with Hello World Plugin" />';
    }
}

// Initialize the plugin
new HelloWorldPlugin();



// Add a simple function that can be called from themes
if (!function_exists('hello_world_greeting')) {
    function hello_world_greeting($name = 'World')
    {
        return "Hello, $name! This greeting is from the Hello World Plugin.";
    }
}

// Add a shortcode-like functionality
if (!function_exists('hello_world_shortcode')) {
    function hello_world_shortcode($atts = [])
    {
        $name = $atts['name'] ?? 'World';
        $style = $atts['style'] ?? 'default';

        $styles = [
            'default' => 'color: #007cba; font-weight: bold;',
            'success' => 'color: #28a745; font-weight: bold;',
            'warning' => 'color: #ffc107; font-weight: bold;',
            'danger' => 'color: #dc3545; font-weight: bold;'
        ];

        $style = $styles[$style] ?? $styles['default'];

        return '<span style="' . $style . '">👋 Hello, ' . htmlspecialchars($name) . '! This is from the Hello World Plugin.</span>';
    }
}
