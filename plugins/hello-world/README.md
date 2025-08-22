# Hello World Plugin

A simple demonstration plugin for ClyCMS that shows how to create and use plugins in the system.

## Features

- Adds a greeting message to your content
- Customizable greeting text through plugin settings
- Adds custom CSS and JavaScript to admin and public areas
- Demonstrates hooks and filters system
- Provides utility functions for themes

## Installation

1. The plugin is already included in your ClyCMS installation
2. Go to Admin → Plugins
3. Click "Install" on the Hello World plugin
4. Click "Activate" to enable the plugin

## Configuration

1. Go to Admin → Plugins
2. Find the Hello World plugin and click "Settings"
3. Modify the greeting message as desired
4. Save your changes

## Usage

### In Themes

You can use the provided functions in your themes:

```php
<?php
// Display a greeting
echo hello_world_greeting('Your Name');

// Use the shortcode-like function
echo hello_world_shortcode(['name' => 'World', 'style' => 'success']);
?>
```

### Available Styles

- `default` - Blue text
- `success` - Green text  
- `warning` - Yellow text
- `danger` - Red text

## Hooks and Filters

This plugin demonstrates the following hooks:

- `init` - Called when the application initializes
- `admin_head` - Adds CSS/JS to admin header
- `clycms_head` - Adds CSS/JS to public header
- `clycms_footer` - Adds content to public footer
- `content_filter` - Filters post content

## File Structure

```
plugins/hello-world/
├── hello-world.php    # Main plugin file
└── README.md          # This file
```

## Customization

You can modify the plugin by editing the `hello-world.php` file. The plugin automatically reloads when you refresh the plugins page in the admin area.

## Support

This is a demonstration plugin. For questions about ClyCMS, please refer to the main documentation. 