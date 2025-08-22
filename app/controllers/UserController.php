<?php

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->user = new User();
    }

    public function index()
    {
        // Handle search and filtering
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $sql = "SELECT * FROM users";
        $params = [];

        if ($search) {
            $sql .= " WHERE name LIKE ? OR email LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($status) {
            $sql .= $search ? " AND status = ?" : " WHERE status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY created_at DESC";

        $users = $this->db->fetchAll($sql, $params);
        $this->view('admin/users/index', ['users' => $users]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleUserSubmission();
        }

        $this->view('admin/users/form', []);
    }

    public function edit($id)
    {
        $user = $this->user->find($id);

        if (!$user) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleUserSubmission($id);
        }

        $this->view('admin/users/form', [
            'user' => $user
        ]);
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->user->find($id);

            if (!$user) {
                http_response_code(404);
                return;
            }

            // Prevent deleting the current user
            if ($id == Auth::id()) {
                http_response_code(403);
                $this->view('errors/403');
                return;
            }

            $this->user->delete($id);
            $this->redirect('/admin/users');
        }
    }

    private function handleUserSubmission($id = null)
    {
        try {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
                'status' => $_POST['status']
            ];

            // Only include password if provided
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            // Validate email uniqueness
            $existingUser = $this->db->fetchOne(
                "SELECT id FROM users WHERE email = ? AND id != ?",
                [$data['email'], $id ?? 0]
            );
            if ($existingUser) {
                throw new Exception('Email address is already in use.');
            }

            if ($id) {
                // Prevent non-admins from changing their own role
                if ($id == Auth::id() && $data['role'] !== Auth::user()['role']) {
                    throw new Exception('You cannot change your own role.');
                }

                // Update existing user
                $this->user->update($id, $data);
                $success_message = 'User updated successfully!';
            } else {
                // Create new user
                $this->user->createUser($data);
                $success_message = 'User created successfully!';
            }

            $this->redirect('/admin/users');
        } catch (Exception $e) {
            $error_message = 'Error saving user: ' . $e->getMessage();
            // Re-display form with error
            return $this->create();
        }
    }
}