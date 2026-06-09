<!-- views/dashboard.php -->
<h1>Dashboard</h1>
<div class="card">
    <h2>Xin chào, <?= h($_SESSION['user_name'] ?? '') ?></h2>
    <p>Role: <strong><?= h($_SESSION['role'] ?? '') ?></strong></p>
    <p>Login at: <?= h(date('Y-m-d H:i:s', $_SESSION['login_at'] ?? time())) ?></p>
    <p>Last activity: <?= h(date('Y-m-d H:i:s', $_SESSION['last_activity_at'] ?? time())) ?></p>
    <p><a class="btn secondary" href="/session-demo">Xem session debug JSON</a></p>
</div>