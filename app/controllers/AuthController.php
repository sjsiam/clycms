<?php

class AuthController extends Controller
{
    public function login()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (Auth::attempt($email, $password)) {
                $this->redirect('/admin');
            } else {
                $error = 'Invalid credentials';
            }
        }

        $this->view('admin/auth/login', ['error' => $error ?? null]);
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/admin/login');
    }
}
