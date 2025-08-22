<?php

class PublicController extends Controller
{
    protected $navigations = [];
    public function __construct()
    {
        parent::__construct();
        $this->theme = Config::getActiveTheme();
        $this->loadNavigation();
        $this->sharedData['navigations'] = $this->navigations;
    }

    protected function loadNavigation()
    {
        $post = new Post();
        $pages = $post->getPublished('page');

        foreach ($pages as $page) {
            $this->navigations[] = [
                'title' => $page['title'],
                'slug' => $page['slug'],
            ];
        }
    }

    public function home()
    {
        $post = new Post();
        $posts = $post->getPublished();

        $this->renderTheme('index', ['posts' => $posts]);
    }

    public function post($slug)
    {
        $post = new Post();
        $postData = $post->getBySlug($slug);
        if (!$postData) {
            http_response_code(404);
            $this->renderTheme('404');
            return;
        }

        $this->renderTheme('single', ['post' => $postData]);
    }

    public function page($slug)
    {
        $post = new Post();
        $page = $post->getBySlug($slug, 'page');

        if (!$page) {
            http_response_code(404);
            $this->renderTheme('404');
            return;
        }

        $this->renderTheme('page', ['page' => $page]);
    }

    public function category($slug)
    {
        $category = new Category();
        $categoryData = $category->getBySlug($slug);

        if (!$categoryData) {
            http_response_code(404);
            $this->renderTheme('404');
            return;
        }

        $post = new Post();
        $posts = $post->getByCategory($categoryData['id']);

        $this->renderTheme('category', [
            'category' => $categoryData,
            'posts' => $posts
        ]);
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $posts = [];

        if ($query) {
            $post = new Post();
            $posts = $post->search($query);
        }

        $this->renderTheme('search', [
            'query' => $query,
            'posts' => $posts
        ]);
    }

    public function sitemap()
    {
        header('Content-Type: application/xml');

        $post = new Post();
        $posts = $post->getPublished();

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        echo '<url><loc>' . Config::get('app.url') . '</loc></url>';

        // Posts
        foreach ($posts as $post) {
            echo '<url><loc>' . Config::get('app.url') . '/post/' . $post['slug'] . '</loc></url>';
        }

        echo '</urlset>';
    }
}
