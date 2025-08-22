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
        $settings = [
            'site_title' => Setting::get('site_title', ''),
            'site_description' => Setting::get('site_description', ''),
            'posts_per_page' => Setting::get('posts_per_page', '10'),
            'date_format' => Setting::get('date_format', 'F j, Y'),
            'time_format' => Setting::get('time_format', 'g:i a'),
            'start_of_week' => Setting::get('start_of_week', '0')
        ];


        $this->view('admin/settings/index', [
            'settings' => $settings,
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

                Setting::set($field, trim($_POST[$field]));
            }

            $success_message = 'Settings updated successfully!';
            $settings = [
                'site_title' => Setting::get('site_title', ''),
                'site_description' => Setting::get('site_description', ''),
                'posts_per_page' => Setting::get('posts_per_page', '10'),
                'date_format' => Setting::get('date_format', 'F j, Y'),
                'time_format' => Setting::get('time_format', 'g:i a'),
                'start_of_week' => Setting::get('start_of_week', '0')
            ];

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
