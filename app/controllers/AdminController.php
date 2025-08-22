<?php

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    public function dashboard()
    {
        $this->view('admin/dashboard', [
            'user' => Auth::user(),
            'title' => 'Admin Dashboard'
        ]);
    }
}
