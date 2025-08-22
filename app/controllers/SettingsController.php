<?php

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->handleSettingsSubmission();
        }

        if (isset($_SESSION['success'])) {
            $success_message = $_SESSION['success'];
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            $error_message = $_SESSION['error'];
            unset($_SESSION['error']);
        }

        // Load all autoloaded settings
        $settings = $this->db->fetchAll(
            "SELECT option_name, option_value FROM options WHERE autoload = 'yes'",
            []
        );

        // Convert settings to key-value array for easier access in view
        $settings_data = [];
        foreach ($settings as $setting) {
            $settings_data[$setting['option_name']] = $setting['option_value'];
        }

        $this->view('admin/settings/index', [
            'settings' => $settings_data,
            'success_message' => $success_message ?? null,
            'error_message' => $error_message ?? null
        ]);
    }

    private function handleSettingsSubmission()
    {
        try {
            $fields = [
                'site_title',
                'site_description',
                'posts_per_page',
                'date_format',
                'time_format',
                'start_of_week'
            ];

            foreach ($fields as $field) {
                if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                    throw new Exception("The field '{$field}' is required.");
                }

                // Check if option exists
                $existing = $this->db->fetchOne(
                    "SELECT id FROM options WHERE option_name = ?",
                    [$field]
                );

                if ($existing) {
                    // Update existing option
                    $this->db->query(
                        "UPDATE options SET option_value = ? WHERE option_name = ?",
                        [$_POST[$field], $field]
                    );
                } else {
                    // Insert new option
                    $this->db->query(
                        "INSERT INTO options (option_name, option_value, autoload) VALUES (?, ?, ?)",
                        [$field, $_POST[$field], 'yes']
                    );
                }
            }

            $success_message = 'Settings updated successfully!';
            $settings = $this->db->fetchAll(
                "SELECT option_name, option_value FROM options WHERE autoload = 'yes'",
                []
            );
            $settings_data = [];
            foreach ($settings as $setting) {
                $settings_data[$setting['option_name']] = $setting['option_value'];
            }

            $this->redirect('/admin/settings', [
                'success' => $success_message
            ]);
        } catch (Exception $e) {
            $error_message = 'Error saving settings: ' . $e->getMessage();
            $settings_data = [];
            foreach ($fields as $field) {
                $settings_data[$field] = $_POST[$field] ?? '';
            }
            $this->view('admin/settings/index', [
                'error' => $error_message
            ]);
        }
    }
}
