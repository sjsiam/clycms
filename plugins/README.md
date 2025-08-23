# ClyCMS Plugin Development Guide

This directory contains plugins for ClyCMS. Plugins extend the functionality of your CMS by adding new features, modifying existing behavior, or integrating with external services.

## Plugin Structure

Each plugin should be in its own directory with the following structure:

```plaintext
plugins/
├── plugin-name/
│   ├── plugin-name.php    # Main plugin file
│   ├── README.md          # Plugin documentation
│   ├── assets/            # CSS, JS, images (optional)
│   └── includes/          # Additional PHP files (optional)
└── README.md              # This file
```

## Creating a Plugin

### 1. Create Plugin Directory

Create a new directory in the `plugins/` folder with your plugin name:

```bash
mkdir plugins/my-awesome-plugin
```

### 2. Create Main Plugin File

Create a PHP file with the same name as your directory:

```php
<?php
/**
 * Plugin Name: My Awesome Plugin
 * Plugin URI: https://example.com/my-plugin
 * Description: A description of what your plugin does
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 */

// Prevent direct access
if (!defined('PLUGINS_PATH')) {
    exit;
}

class MyAwesomePlugin
{
    private $pluginManager;
    
    public function __construct()
    {
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
        // Add hooks and filters here
        $this->pluginManager->addHook('init', [$this, 'onInit']);
        $this->pluginManager->addHook('admin_head', [$this, 'onAdminHead']);
                 $this->pluginManager->addHook('clycms_head', [$this, 'onPublicHead']);
         $this->pluginManager->addFilter('content_filter', [$this, 'filterContent']);
    }
    
    public function onInit()
    {
        // Plugin initialization code
    }
    
    public function onAdminHead()
    {
        // Add CSS/JS to admin area
        echo '<style>/* Your admin styles */</style>';
        echo '<script>/* Your admin scripts */</script>';
    }
    
    public function onPublicHead()
    {
        // Add CSS/JS to public area
        echo '<meta name="my-plugin" content="active" />';
    }
    
    public function filterContent($content)
    {
        // Modify post content
        return $content;
    }
}

// Initialize the plugin
new MyAwesomePlugin();
```

### 3. Plugin Header Information

The plugin header comment block is required and must include:

- **Plugin Name**: The name of your plugin
- **Description**: What your plugin does
- **Version**: Plugin version number
- **Author**: Your name or organization

Optional fields:

- **Plugin URI**: Link to plugin website
- **Author URI**: Link to your website

## Available Hooks

### Action Hooks

Hooks that allow you to execute code at specific points:

- `init` - Called when the application initializes
- `admin_head` - Called in admin header (add CSS/JS)
- `admin_footer` - Called in admin footer (add scripts)
- `clycms_head` - Called in public header (add meta tags, CSS/JS)
- `clycms_footer` - Called in public footer (add content, scripts)

### Filter Hooks

Hooks that allow you to modify data:

- `content_filter` - Filter post/page content before display

## Using Hooks

### Adding Actions

```php
$this->pluginManager->addHook('hook_name', [$this, 'callback_method'], $priority);
```

### Adding Filters

```php
$this->pluginManager->addFilter('filter_name', [$this, 'callback_method'], $priority);
```

### Priority

Priority determines the order in which hooks are executed. Lower numbers execute first:

- `1` - Very early
- `5` - Default priority
- `10` - Normal priority
- `20` - Late
- `100` - Very late

## Plugin Settings

Store and retrieve plugin settings:

```php
// Save settings
$this->pluginManager->updatePluginSettings('plugin-name', [
    'option1' => 'value1',
    'option2' => 'value2'
]);

// Retrieve settings
$settings = $this->pluginManager->getPluginSettings('plugin-name');
$option1 = $settings['option1'] ?? 'default';
```

## Best Practices

### 1. Security

- Always validate and sanitize user input
- Use prepared statements for database queries
- Check user permissions before performing actions

### 2. Performance

- Minimize database queries
- Use caching when appropriate
- Load assets only when needed

### 3. Compatibility

- Test with different themes
- Don't assume specific CSS classes exist
- Use responsive design principles

### 4. Code Organization

- Keep your plugin code organized and well-documented
- Use meaningful function and variable names
- Follow PSR coding standards

## Example Plugin

See the `hello-world/` plugin for a complete working example that demonstrates:

- Plugin structure and header
- Hook usage
- Settings management
- Admin integration
- Public area modifications

## Testing Your Plugin

1. Create your plugin files
2. Go to Admin → Plugins
3. Click "Install" on your plugin
4. Click "Activate" to enable it
5. Test functionality in both admin and public areas

## Troubleshooting

### Plugin Not Appearing

- Check that the plugin directory name matches the main PHP file name
- Ensure the plugin header comment is properly formatted
- Check for PHP syntax errors

### Plugin Not Working

- Verify hooks are properly registered
- Check browser console for JavaScript errors
- Review server error logs

### Settings Not Saving

- Ensure the plugin is activated
- Check database permissions
- Verify settings are in valid JSON format

## Getting Help

- Check the main ClyCMS documentation
- Review existing plugins for examples
- Test with a simple plugin first
- Use browser developer tools for debugging

## Plugin Distribution

When sharing your plugin:

1. Include a comprehensive README
2. Document all features and options
3. Provide installation instructions
4. Include example usage
5. Test thoroughly before release

---

Happy plugin development! 🚀
