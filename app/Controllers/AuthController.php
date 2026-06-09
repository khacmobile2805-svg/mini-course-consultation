<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

class AuthController
{
    private array $users;

    public function __construct()
    {
        $this->users = [
            'student@example.com' => [
                'id' => 1,
                'name' => 'Nguyễn Văn Student',
                'role' => 'student',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
            ],
        ];
    }

    public function login(): void
    {
        if (is_logged_in()) {
            redirect('/dashboard');
        }

        view('auth/login', [
            'view' => 'auth/login',
            'old' => flash_get('old', []),
            'errors' => flash_get('errors', []),
        ]);
    }

    public function handleLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = ($_POST['remember_me'] ?? '') === '1';

        $errors = [];

        if ($email === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập password.';
        }

        $user = $this->users[$email] ?? null;
        if (empty($errors) && (!$user || !password_verify($password, $user['password_hash']))) {
            $errors['password'] = 'Email hoặc password không đúng.';
        }

        if (!empty($errors)) {
            flash_set('errors', $errors);
            flash_set('old', ['email' => $email]);
            redirect('/login');
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_at'] = time();
        $_SESSION['last_activity_at'] = time();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if ($remember) {
            flash_set('success', 'Đăng nhập thành công. Remember me cần token riêng; Lab04 không lưu password trong cookie.');
        } else {
            flash_set('success', 'Đăng nhập thành công. Session ID đã được regenerate.');
        }

        redirect('/dashboard');
    }

    public function logout(): void
    {
        logout_clean();

        session_start();
        flash_set('success', 'Đăng xuất thành công. Session cũ đã được xóa.');
        redirect('/login');
    }
}