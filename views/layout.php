<?php
/** @var string $view */
/** @var string|null $success */
/** @var string|null $error */
?>
<!-- views/layout.php -->
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lab04 Secure Portal</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav class="navbar">
    <strong>PHP Lab04 Secure Portal</strong>
    <a href="/">Home</a>
    <a href="/consultations">Consultations</a>
    <a href="/consultations/create">Create Lead</a>
    <a href="/dashboard">Dashboard</a>
    <a href="/login">Login</a>
    <form method="post" action="/logout" class="inline-form">
        <button type="submit" class="link-button">Logout</button>
    </form>
</nav>

<main class="container">
    <?php if ($success = flash_get('success')): ?>
        <div class="alert success"><?= h($success) ?></div>
    <?php endif; ?>

    <?php if ($error = flash_get('error')): ?>
        <div class="alert error"><?= h($error) ?></div>
    <?php endif; ?>

    <?php require view_path($view); ?>
</main>
</body>
</html>