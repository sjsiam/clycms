# Content Filter Plugin

A demonstration plugin for ClyCMS that shows how to filter and modify post content using the plugin system.

## Features

- **Reading Time Estimation**: Automatically calculates and displays estimated reading time for posts
- **Auto-linking**: Converts common terms like "ClyCMS", "WordPress", "PHP", and "MVC" into clickable links
- **Keyword Highlighting**: Highlights important keywords in content (configurable)
- **Content Wrapping**: Wraps filtered content in a container for styling

## Installation

1. The plugin is already included in your ClyCMS installation
2. Go to Admin → Plugins
3. Click "Install" on the Content Filter plugin
4. Click "Activate" to enable the plugin

## Configuration

1. Go to Admin → Plugins
2. Find the Content Filter plugin and click "Settings"
3. Configure the following options:

```json
{
    "enable_auto_links": true,
    "add_reading_time": true,
    "highlight_keywords": false,
    "keywords": ["important", "key", "highlight"]
}
```

### Settings Options

- **enable_auto_links**: Enable/disable automatic linking of common terms
- **add_reading_time**: Show/hide reading time estimation
- **highlight_keywords**: Enable/disable keyword highlighting
- **keywords**: Array of keywords to highlight in content

## How It Works

### Content Filtering

The plugin uses the `content_filter` hook to modify post content before it's displayed. This happens automatically for all posts and pages.

### Reading Time Calculation

Reading time is calculated based on word count, assuming an average reading speed of 200 words per minute. The formula used is:

```
reading_time = ceil(word_count / 200)
```

### Auto-linking

The plugin automatically converts these terms into clickable links:
- ClyCMS → https://clycms.com
- WordPress → https://wordpress.org
- PHP → https://php.net
- MVC → https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller

### Keyword Highlighting

When enabled, specified keywords are highlighted with a yellow background using the `<mark>` HTML element.

## Example Output

When the plugin is active, your content will look like this:

```html
<div class="content-filtered">
    <div class="reading-time" style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 0.9em; color: #6c757d;">
        <i class="fas fa-clock"></i> Estimated reading time: <strong>3 minutes</strong>
    </div>
    
    <p>This is a post about <a href="https://clycms.com" target="_blank" rel="noopener noreferrer">ClyCMS</a> and how it compares to <a href="https://wordpress.org" target="_blank" rel="noopener noreferrer">WordPress</a>.</p>
    
    <p>The <mark style="background: #fff3cd; padding: 2px 4px; border-radius: 3px;">important</mark> thing to remember is that this CMS uses <a href="https://php.net" target="_blank" rel="noopener noreferrer">PHP</a> and follows the <a href="https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller" target="_blank" rel="noopener noreferrer">MVC</a> pattern.</p>
</div>
```

## Customization

### Adding More Auto-links

To add more terms for auto-linking, modify the `$links` array in the `addAutoLinks()` method:

```php
$links = [
    'ClyCMS' => 'https://clycms.com',
    'WordPress' => 'https://wordpress.org',
    'PHP' => 'https://php.net',
    'MVC' => 'https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller',
    'Your Term' => 'https://your-link.com'
];
```

### Changing Reading Speed

To adjust the reading speed calculation, modify the divisor in the `filterContent()` method:

```php
$readingTime = ceil($wordCount / 150); // 150 words per minute for slower readers
```

### Custom Styling

The plugin adds CSS classes that you can style:
- `.reading-time` - Reading time display
- `.content-filtered` - Content wrapper
- `.highlighted` - Highlighted keywords

## Technical Details

### Hooks Used

- `content_filter` - Filters post content before display
- `admin_head` - Adds admin area styling and notifications

### Priority

The content filter runs with priority 10, which means it runs after most other filters but before final output.

### Performance

The plugin is designed to be lightweight:
- Minimal database queries
- Efficient regex operations
- Conditional processing based on settings

## Troubleshooting

### Plugin Not Working

1. Ensure the plugin is activated
2. Check that posts have content
3. Verify settings are saved correctly

### Reading Time Not Showing

1. Check the `add_reading_time` setting
2. Ensure posts have text content (not just images)
3. Verify the plugin is active

### Links Not Working

1. Check the `enable_auto_links` setting
2. Ensure terms are spelled correctly in content
3. Check for JavaScript errors in browser console

## Development

This plugin serves as a reference implementation for:
- Content filtering and modification
- Plugin settings management
- Hook and filter usage
- Admin area integration

Feel free to use this code as a starting point for your own content modification plugins!

---

For more information about plugin development, see the main plugins README.md file. 