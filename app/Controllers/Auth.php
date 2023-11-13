<?php

namespace App\Controllers;

use App\Models\AuthModel;
use IonAuth\Libraries\IonAuth;

class Auth extends BaseController
{
    protected $ionAuth;
    protected $authModel;

    public function __construct()
    {
        helper(['form', 'url', 'html', 'session']);
        $this->ionAuth = new IonAuth();
        $this->authModel = new AuthModel(); // Load the AuthModel
    }
    public function index(): string
    {
        return view('auth/index');
    }

    public function register(): string
    {
        return view('auth/register');
    }

    public function processRegister()
{
    $username = $this->request->getPost('username', FILTER_SANITIZE_STRING);
    $email = $this->request->getPost('email', FILTER_SANITIZE_STRING);
    $password = $this->request->getPost('password', FILTER_SANITIZE_STRING);
    $name = $this->request->getPost('name', FILTER_SANITIZE_STRING);

    // Memvalidasi input
    $validationRules = [
        'username' => 'required|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required',
        'name'     => 'required'
    ];

    if (!$this->validate($validationRules)) {
        // Jika validasi gagal, kembalikan dengan pesan kesalahan
        return redirect()->to(base_url('/register'))->with('error', $this->validator->getErrors());
    }

    $ionAuth = new \IonAuth\Libraries\IonAuth();

    // Hash the password using IonAuth's hashPassword method
    $hashedPassword = $ionAuth->hashPassword($password);

    // Insert the user into the database with the hashed password
    $userModel = new \App\Models\AuthModel();

    try {
        $userModel->registerUser($username, $email, $hashedPassword, $name);

        // Registration success, redirect to login page
        return redirect()->to(base_url('/login'))->with('message', 'Registration successful! Please log in.');
    } catch (\Exception $e) {
        // Registration failed due to duplicate data, show error message
        return redirect()->to(base_url('/register'))->with('error', 'Registration failed. Duplicate username or email.');
    }
}

    public function login(): string
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        if ($this->request->getPost('login')) {
            $username = $this->request->getPost('username', FILTER_SANITIZE_STRING);
            $password = $this->request->getPost('password', FILTER_SANITIZE_STRING);

            $userModel = new \App\Models\AuthModel(); // Sesuaikan dengan model User Anda

            $user = $userModel->where('username', $username)
                ->orWhere('email', $username)
                ->first();

            if ($user && $password) {
                // Buat Session
                session()->start();
                $_SESSION["user"] = $user;

                // Login sukses, alihkan ke halaman yang diinginkan (contoh: halaman register)
                return redirect()->to(base_url('/timeline'))->with('success', 'Login berhasil.');
            }
        }

        // Handle other cases when login fails
        return redirect()->to(base_url('/login'))->with('error', 'Login Gagal. Pastikan username dan password benar! 😉');
    }

    public function logout()
    {
        // Logout using IonAuth
        $this->ionAuth->logout();

        // Redirect to the index page
        return redirect()->to(base_url('/'));
    }

    public function timeline(): string
    {
        return view('auth/timeline');
    }
}
