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
        $post = new Post();
        $user = new User();

        $stats = [
            'total_posts' => count($post->all()),
            'published_posts' => count($post->where('status', 'published')),
            'draft_posts' => count($post->where('status', 'draft')),
            'total_users' => count($user->all())
        ];

        $recent_posts = array_slice($post->all(), 0, 5);

        $this->view('admin/dashboard', [
            'stats' => $stats,
            'recent_posts' => $recent_posts,
            'current_user' => Auth::user()
        ]);
    }
}
