<?php

class PostController extends Controller
{
    private $post;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->post = new Post();
    }

    public function index()
    {
        $posts = $this->post->all();
        $this->view('admin/posts/index', ['posts' => $posts]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->generateSlug($_POST['title']),
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? '',
                'status' => $_POST['status'],
                'author_id' => Auth::id(),
                'post_type' => 'post',
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? ''
            ];

            $postId = $this->post->create($data);

            // Handle categories
            if (isset($_POST['categories'])) {
                $this->assignCategories($postId, $_POST['categories']);
            }

            $this->redirect('/admin/posts');
        }

        $category = new Category();
        $categories = $category->all();

        $this->view('admin/posts/create', ['categories' => $categories]);
    }

    public function edit($id)
    {
        $post = $this->post->find($id);

        if (!$post) {
            http_response_code(404);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->generateSlug($_POST['title']),
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? '',
                'status' => $_POST['status'],
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? ''
            ];

            $this->post->update($id, $data);

            // Handle categories
            if (isset($_POST['categories'])) {
                $this->updateCategories($id, $_POST['categories']);
            }

            $this->redirect('/admin/posts');
        }

        $category = new Category();
        $categories = $category->all();

        // Get post categories
        $postCategories = $this->db->fetchAll(
            "SELECT category_id FROM post_categories WHERE post_id = ?",
            [$id]
        );
        $postCategoryIds = array_column($postCategories, 'category_id');

        $this->view('admin/posts/edit', [
            'post' => $post,
            'categories' => $categories,
            'post_categories' => $postCategoryIds
        ]);
    }

    private function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        return $slug;
    }

    private function assignCategories($postId, $categories)
    {
        foreach ($categories as $categoryId) {
            $this->db->insert('post_categories', [
                'post_id' => $postId,
                'category_id' => $categoryId
            ]);
        }
    }

    private function updateCategories($postId, $categories)
    {
        // Remove existing categories
        $this->db->delete('post_categories', 'post_id = ?', [$postId]);

        // Add new categories
        $this->assignCategories($postId, $categories);
    }
}
