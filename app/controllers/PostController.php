<?php

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->post = new Post();
        $this->category = new Category();
    }

    public function index()
    {
        // Handle search and filtering
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $sql = "SELECT p.*, u.name as author_name 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.post_type = 'post'";
        $params = [];

        if ($search) {
            $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $posts = $this->db->fetchAll($sql, $params);
        $this->view('admin/posts/index', ['posts' => $posts]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePostSubmission();
        }

        $categories = $this->category->all();

        $this->view('admin/posts/form', [
            'categories' => $categories,
            'post_categories' => []
        ]);
    }

    public function edit($id)
    {
        $post = $this->post->find($id);

        if (!$post) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePostSubmission($id);
        }

        $categories = $this->category->all();

        // Get post categories
        $postCategories = $this->db->fetchAll(
            "SELECT category_id FROM post_categories WHERE post_id = ?",
            [$id]
        );
        $postCategoryIds = array_column($postCategories, 'category_id');

        $this->view('admin/posts/form', [
            'post' => $post,
            'categories' => $categories,
            'post_categories' => $postCategoryIds
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = $this->post->find($id);

            if (!$post) {
                http_response_code(404);
                return;
            }

            $this->db->delete('post_categories', 'post_id = ?', [$id]);

            $this->db->delete('post_tags', 'post_id = ?', [$id]);

            $this->post->delete($id);

            $this->redirect('/admin/posts');
        }
    }

    private function handlePostSubmission($id = null)
    {
        try {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->generateSlug($_POST['slug'] ?: $_POST['title']),
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? '',
                'status' => $_POST['status'],
                'post_type' => $_POST['post_type'] ?? 'post',
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? ''
            ];

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $data['featured_image'] = $this->handleImageUpload($_FILES['featured_image']);
            } elseif (isset($_POST['remove_featured_image']) && $_POST['remove_featured_image'] === '1') {
                $data['featured_image'] = null;
            }

            if ($id) {
                // Update existing post
                $this->post->update($id, $data);
                $postId = $id;
                $success_message = 'Post updated successfully!';
            } else {
                // Create new post
                $data['author_id'] = Auth::id();
                $postId = $this->post->create($data);
                $success_message = 'Post created successfully!';
            }

            // Handle categories
            if (isset($_POST['categories'])) {
                $this->updateCategories($postId, $_POST['categories']);
            } else {
                // Remove all categories if none selected
                $this->db->delete('post_categories', 'post_id = ?', [$postId]);
            }

            $this->redirect('/admin/posts');
        } catch (Exception $e) {
            $error_message = 'Error saving post: ' . $e->getMessage();
            // Re-display form with error
            return $this->create();
        }
    }

    private function generateSlug($title, $id = null)
    {
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = $baseSlug;
        $counter = 1;

        $sql = "SELECT slug FROM posts WHERE slug = ? AND post_type = 'post'";
        if ($id) {
            $sql .= " AND id != ?";
        }
        $params = [$slug];
        if ($id) {
            $params[] = $id;
        }

        while ($this->db->fetchOne($sql, $params)) {
            $slug = $baseSlug . '-' . $counter;
            $params[0] = $slug;
            $counter++;
        }

        return $slug;
    }

    private function handleImageUpload($file)
    {
        $uploadDir = STORAGE_PATH . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.');
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/storage/uploads/' . $fileName;
        } else {
            throw new Exception('Failed to upload image.');
        }
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
