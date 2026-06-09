<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;

class DashboardController
{
    public function index(): void
    {
        require_login();
        view('dashboard', ['view' => 'dashboard']);
    }

    public function sessionDemo(): void
    {
        require_login();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'user_id' => $_SESSION['user_id'] ?? null,
            'user_name' => $_SESSION['user_name'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'login_at' => date('Y-m-d H:i:s', $_SESSION['login_at'] ?? time()),
            'last_activity_at' => date('Y-m-d H:i:s', $_SESSION['last_activity_at'] ?? time()),
            'session_name' => session_name(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}