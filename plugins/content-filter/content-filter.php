<?php

/**
 * Plugin Name: Content Filter
 * Plugin URI: https://example.com/content-filter
 * Description: A plugin that demonstrates content filtering and modification
 * Version: 1.0.0
 * Author: ClyCMS Team
 * Author URI: https://clycms.com
 */

// Prevent direct access
if (!defined('PLUGINS_PATH')) {
    exit;
}

class ContentFilterPlugin
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
        // Add content filter
        $this->pluginManager->addFilter('content_filter', [$this, 'filterContent'], 10);

        // Add admin head hook to show plugin is active
        $this->pluginManager->addHook('admin_head', [$this, 'onAdminHead']);
        $this->pluginManager->addHook('clycms_head', [$this, 'onPublicHead']);
    }

    public function onAdminHead()
    {
        echo '<style>
            .content-filter-active {
                background: #d4edda;
                border: 1px solid #c3e6cb;
                color: #155724;
                padding: 10px;
                margin: 10px 0;
                border-radius: 4px;
                font-weight: bold;
            }
        </style>';

        echo '<div class="content-filter-active">🎯 Content Filter Plugin is active!</div>';
    }

    public function onPublicHead()
    {
        echo '<style>
            .content-filtered {
                border: 2px dashed #007bff;
                padding: 15px;
                border-radius: 8px;
                background: #f0f8ff;
            }
            .reading-time {
                font-size: 0.9em;
                color: #6c757d;
                margin-bottom: 10px;
            }
        </style>';
    }

    public function filterContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Get plugin settings
        $settings = $this->pluginManager->getPluginSettings('content-filter');
        $enableAutoLinks = $settings['enable_auto_links'] ?? true;
        $addReadingTime = $settings['add_reading_time'] ?? true;
        $highlightKeywords = $settings['highlight_keywords'] ?? false;
        $keywords = $settings['keywords'] ?? ['important', 'key', 'highlight'];

        // Add reading time estimate
        if ($addReadingTime) {
            $wordCount = str_word_count(strip_tags($content));
            $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute

            $readingTimeHtml = '<div class="reading-time" style="background: #f8f9fa; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 0.9em; color: #6c757d;">
                <i class="fas fa-clock"></i> Estimated reading time: <strong>' . $readingTime . ' minute' . ($readingTime > 1 ? 's' : '') . '</strong>
            </div>';

            $content = $readingTimeHtml . $content;
        }

        // Auto-link certain words
        if ($enableAutoLinks) {
            $content = $this->addAutoLinks($content);
        }

        // Highlight keywords
        if ($highlightKeywords && !empty($keywords)) {
            $content = $this->highlightKeywords($content, $keywords);
        }

        // Add content wrapper
        $content = '<div class="content-filtered">' . $content . '</div>';

        return $content;
    }

    private function addAutoLinks($content)
    {
        // Auto-link common terms
        $links = [
            'ClyCMS' => 'https://clycms.com',
            'WordPress' => 'https://wordpress.org',
            'PHP' => 'https://php.net',
            'MVC' => 'https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller'
        ];

        foreach ($links as $term => $url) {
            $content = preg_replace(
                '/\b' . preg_quote($term, '/') . '\b/i',
                '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $term . '</a>',
                $content
            );
        }

        return $content;
    }

    private function highlightKeywords($content, $keywords)
    {
        foreach ($keywords as $keyword) {
            $content = preg_replace(
                '/\b(' . preg_quote($keyword, '/') . ')\b/i',
                '<mark style="background: #fff3cd; padding: 2px 4px; border-radius: 3px;">$1</mark>',
                $content
            );
        }

        return $content;
    }
}

// Initialize the plugin
new ContentFilterPlugin();
