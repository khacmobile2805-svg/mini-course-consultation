<!-- views/home.php -->
<h1>Lab04 - Secure Forms &amp; Session Login Flow</h1>
<p>Trang tổng quan các nhóm chức năng chính của hệ thống. Bấm vào từng thẻ để đi tới đúng chức năng.</p>

<div class="grid">
    <a class="card card-link" href="/consultations/create">
        <h2>Secure Form</h2>
        <p>Đọc input an toàn, escape output chống XSS. → /consultations/create</p>
    </a>

    <a class="card card-link" href="/consultations">
        <h2>Validation + PRG</h2>
        <p>Validate server-side, giữ dữ liệu cũ, redirect tránh submit trùng. → /consultations</p>
    </a>

    <a class="card card-link" href="/consultations/create">
        <h2>Anti-spam</h2>
        <p>Honeypot và rate limit chặn bot tự động. → /consultations/create</p>
    </a>

    <a class="card card-link" href="/login">
        <h2>Login / Session</h2>
        <p>Cookie flags, regenerate ID, timeout, logout sạch. → /login</p>
    </a>
</div>