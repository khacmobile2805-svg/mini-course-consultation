<!-- views/auth/login.php -->
<h1>Đăng nhập hệ thống</h1>
<p>Tài khoản demo: <strong>student@example.com</strong> / <strong>123456</strong></p>

<form method="post" action="/login" class="card">
    <div class="form-group">
        <label>Email</label>
        <input name="email" value="<?= h($old['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?><div class="error-text"><?= h($errors['email']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password">
        <?php if (!empty($errors['password'])): ?><div class="error-text"><?= h($errors['password']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="remember_me" value="1"> Remember me</label>
        <small>Lab04 chỉ giới thiệu rủi ro. Không lưu password trong cookie.</small>
    </div>

    <button class="btn primary" type="submit">Login</button>
</form>