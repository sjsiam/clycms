<?php

class Application
{
    private $router;
    private $database;

    public function __construct()
    {
        $this->router = new Router();
        $this->database = Database::getInstance();
        $this->loadPlugins();
        $this->setupRoutes();
    }

    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (RouteNotFoundException $e) {
            http_response_code(404);

            $errorPage = THEMES_PATH . '/' . $_ENV['APP_THEME'] . '/404.php';
            if (file_exists($errorPage)) {
                include $errorPage;
            } else {
                echo "<h1>404 Not Found</h1><p>The page you are looking for does not exist.</p>";
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function setupRoutes()
    {
        // Admin routes
        $this->router->add('admin', 'AdminController@dashboard');
        $this->router->add('admin/login', 'AuthController@login');
        $this->router->add('admin/login', 'AuthController@login', 'POST');
        $this->router->add('admin/logout', 'AuthController@logout');
        $this->router->add('admin/posts', 'PostController@index');
        $this->router->add('admin/posts/create', 'PostController@create');
        $this->router->add('admin/posts/create', 'PostController@create', 'POST');
        $this->router->add('admin/posts/edit/{id}', 'PostController@edit');
        $this->router->add('admin/posts/edit/{id}', 'PostController@edit', 'POST');
        $this->router->add('admin/posts/delete/{id}', 'PostController@delete', 'POST');
        $this->router->add('admin/pages', 'PageController@index');
        $this->router->add('admin/pages/create', 'PageController@create');
        $this->router->add('admin/pages/create', 'PageController@create', 'POST');
        $this->router->add('admin/pages/edit/{id}', 'PageController@edit');
        $this->router->add('admin/pages/edit/{id}', 'PageController@edit', 'POST');
        $this->router->add('admin/pages/delete/{id}', 'PageController@delete', 'POST');
        $this->router->add('admin/media', 'MediaController@index');
        $this->router->add('admin/users', 'UserController@index');
        $this->router->add('admin/users/create', 'UserController@create');
        $this->router->add('admin/themes', 'ThemeController@index');
        $this->router->add('admin/plugins', 'PluginController@index');
        $this->router->add('admin/settings', 'SettingsController@index');

        // API routes
        $this->router->add('api/posts', 'ApiController@posts');
        $this->router->add('api/upload', 'ApiController@upload');

        // Public routes
        $this->router->add('', 'PublicController@home');
        $this->router->add('post/{slug}', 'PublicController@post');
        $this->router->add('search', 'PublicController@search');
        $this->router->add('/{slug}', 'PublicController@page');
        $this->router->add('category/{slug}', 'PublicController@category');
        $this->router->add('tag/{slug}', 'PublicController@tag');
        $this->router->add('sitemap.xml', 'PublicController@sitemap');
    }

    private function loadPlugins()
    {
        $pluginManager = new PluginManager();
        $pluginManager->loadActivePlugins();
    }

    private function handleError($e)
    {
        error_log($e->getMessage());
        if (defined('DEBUG') && DEBUG) {
            echo '<h1>Error</h1><p>' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            include APP_PATH . '/views/errors/500.php';
        }
    }
}
