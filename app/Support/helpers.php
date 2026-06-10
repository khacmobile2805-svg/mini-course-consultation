<?php
// app/Support/helpers.php

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flash_set(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function flash_get(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash_set('error', 'Vui lòng đăng nhập để truy cập dashboard.');
        redirect('/login');
    }
}

function check_session_timeout(): void
{
    $idleLimit = 15 * 60; // demo; nhớ đổi lại 15 * 60 khi xong

    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $last = $_SESSION['last_activity_at'] ?? time();
    if (time() - $last > $idleLimit) {
        $_SESSION = [];                 // xóa dữ liệu user
        session_regenerate_id(true);    // hủy phiên cũ + phát cookie mới
        flash_set('error', 'Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.');
        redirect('/login');
    }

    $_SESSION['last_activity_at'] = time();
}

function check_session_context(): void
{
    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $currentAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $savedAgent = $_SESSION['user_agent'] ?? '';

    if ($savedAgent !== '' && $savedAgent !== $currentAgent) {
        logout_clean();
        session_start();
        flash_set('error', 'Phiên có dấu hiệu bất thường. Vui lòng đăng nhập lại.');
        redirect('/login');
    }
}

function logout_clean(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

function view(string $view, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../../views/layout.php';
}

function view_path(string $view): string
{
    return __DIR__ . '/../../views/' . $view . '.php';
}
