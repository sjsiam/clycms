<?php

class PageController extends Controller
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
        // Handle search and filtering
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $sql = "SELECT p.*, u.name as author_name 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.post_type = 'page'";
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

        $pages = $this->db->fetchAll($sql, $params);
        $this->view('admin/pages/index', ['pages' => $pages]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePageSubmission();
        }

        $this->view('admin/pages/form', []);
    }

    public function edit($id)
    {
        $page = $this->post->find($id);

        if (!$page || $page['post_type'] !== 'page') {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handlePageSubmission($id);
        }

        $this->view('admin/pages/form', [
            'page' => $page
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $page = $this->post->find($id);

            if (!$page || $page['post_type'] !== 'page') {
                http_response_code(404);
                return;
            }

            // Delete the page
            $this->post->delete($id);

            $this->redirect('/admin/pages');
        }
    }

    private function handlePageSubmission($id = null)
    {
        try {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->generateSlug($_POST['slug'] ?: $_POST['title'], $id),
                'content' => $_POST['content'],
                'excerpt' => $_POST['excerpt'] ?? '',
                'status' => $_POST['status'],
                'post_type' => 'page',
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? ''
            ];

            // Handle featured image upload
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $data['featured_image'] = $this->handleImageUpload($_FILES['featured_image']);
            } elseif (isset($_POST['featured_image_id']) && !empty($_POST['featured_image_id'])) {
                // Handle media library selection
                $media = new Media();
                $mediaFile = $media->find($_POST['featured_image_id']);
                if ($mediaFile) {
                    $data['featured_image'] = $mediaFile['file_path'];
                }
            } elseif (isset($_POST['remove_featured_image']) && $_POST['remove_featured_image'] === '1') {
                $data['featured_image'] = null;
            }

            if ($id) {
                // Update existing page
                $this->post->update($id, $data);
                $success_message = 'Page updated successfully!';
            } else {
                // Create new page
                $data['author_id'] = Auth::id();
                $this->post->create($data);
                $success_message = 'Page created successfully!';
            }

            $this->redirect('/admin/pages');
        } catch (Exception $e) {
            $error_message = 'Error saving page: ' . $e->getMessage();
            // Re-display form with error
            return $this->create();
        }
    }

    private function generateSlug($title, $id = null)
    {
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = $baseSlug;
        $counter = 1;

        $sql = "SELECT slug FROM posts WHERE slug = ? AND post_type = 'page'";
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
}
