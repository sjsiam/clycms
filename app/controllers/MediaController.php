<?php

class MediaController extends Controller
{
    private $media;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->media = new Media();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';

        if ($search) {
            $mediaFiles = $this->media->search($search);
        } elseif ($type) {
            $mediaFiles = $this->media->getByType($type);
        } else {
            $mediaFiles = $this->media->getByType();
        }

        $this->view('admin/media/index', [
            'mediaFiles' => $mediaFiles,
            'search' => $search,
            'type' => $type
        ]);
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception('No file uploaded or upload error.');
                }

                $media = $this->media->uploadFile($_FILES['file']);
                
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => true,
                        'media' => $media,
                        'message' => 'File uploaded successfully!'
                    ]);
                } else {
                    $this->redirect('/admin/media?uploaded=1');
                }
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                } else {
                    $this->redirect('/admin/media?error=' . urlencode($e->getMessage()));
                }
            }
        }

        $this->view('admin/media/upload');
    }

    public function edit($id)
    {
        $media = $this->media->find($id);
        if (!$media) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'alt_text' => $_POST['alt_text'] ?? '',
                    'caption' => $_POST['caption'] ?? '',
                    'description' => $_POST['description'] ?? ''
                ];

                $this->media->update($id, $data);
                
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => true,
                        'message' => 'Media updated successfully!'
                    ]);
                } else {
                    $this->redirect('/admin/media?updated=1');
                }
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                } else {
                    $this->redirect('/admin/media/edit/' . $id . '?error=' . urlencode($e->getMessage()));
                }
            }
        }

        $this->view('admin/media/edit', ['media' => $media]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->media->deleteFile($id);
                
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => true,
                        'message' => 'Media deleted successfully!'
                    ]);
                } else {
                    $this->redirect('/admin/media?deleted=1');
                }
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    $this->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ]);
                } else {
                    $this->redirect('/admin/media?error=' . urlencode($e->getMessage()));
                }
            }
        }
    }

    public function library()
    {
        // AJAX endpoint for media library modal
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;

        if ($search) {
            $mediaFiles = $this->media->search($search);
        } elseif ($type) {
            $mediaFiles = $this->media->getByType($type);
        } else {
            $mediaFiles = $this->media->getByType();
        }

        // Simple pagination
        $total = count($mediaFiles);
        $offset = ($page - 1) * $perPage;
        $mediaFiles = array_slice($mediaFiles, $offset, $perPage);

        $this->json([
            'success' => true,
            'media' => $mediaFiles,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total
            ]
        ]);
    }

    public function details($id)
    {
        $media = $this->media->find($id);
        if (!$media) {
            $this->json(['success' => false, 'message' => 'Media not found']);
            return;
        }

        // Get uploader info
        if ($media['uploaded_by']) {
            $user = new User();
            $uploader = $user->find($media['uploaded_by']);
            $media['uploader_name'] = $uploader['name'] ?? 'Unknown';
        }

        $this->json([
            'success' => true,
            'media' => $media
        ]);
    }
}