<?php

class TagController extends Controller
{
    private $tag;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->tag = new Tag();
    }

    public function index()
    {
        // Handle search and filtering
        $search = $_GET['search'] ?? '';

        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM tags t 
                LEFT JOIN post_tags pt ON t.id = pt.tag_id";
        $params = [];

        if ($search) {
            $sql .= " WHERE t.name LIKE ? OR t.description LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $sql .= " GROUP BY t.id ORDER BY t.name ASC";

        $tags = $this->db->fetchAll($sql, $params);
        $this->view('admin/tags/index', ['tags' => $tags]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleTagSubmission();
        }

        $this->view('admin/tags/form', []);
    }

    public function edit($id)
    {
        $tag = $this->tag->find($id);

        if (!$tag) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleTagSubmission($id);
        }

        $this->view('admin/tags/form', [
            'tag' => $tag
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tag = $this->tag->find($id);

            if (!$tag) {
                http_response_code(404);
                return;
            }

            // Delete tag relationships
            $this->db->delete('post_tags', 'tag_id = ?', [$id]);

            // Delete the tag
            $this->tag->delete($id);

            $this->redirect('/admin/tags');
        }
    }

    private function handleTagSubmission($id = null)
    {
        try {
            $data = [
                'name' => $_POST['name'],
                'slug' => $this->generateSlug($_POST['slug'] ?: $_POST['name'], $id),
                'description' => $_POST['description'] ?? ''
            ];

            if ($id) {
                // Update existing tag
                $this->tag->update($id, $data);
                $success_message = 'Tag updated successfully!';
            } else {
                // Create new tag
                $this->tag->create($data);
                $success_message = 'Tag created successfully!';
            }

            $this->redirect('/admin/tags');
        } catch (Exception $e) {
            $error_message = 'Error saving tag: ' . $e->getMessage();
            // Re-display form with error
            return $this->create();
        }
    }

    private function generateSlug($title, $id = null)
    {
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $slug = $baseSlug;
        $counter = 1;

        $sql = "SELECT slug FROM tags WHERE slug = ?";
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
}