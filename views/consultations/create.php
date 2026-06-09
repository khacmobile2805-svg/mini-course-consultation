<!-- views/consultations/create.php -->
<h1>Form đăng ký tư vấn</h1>
<p>Form này submit đến <strong>POST /consultations</strong>.</p>

<?php if (!empty($errors['_global'])): ?>
    <div class="alert error"><?= h($errors['_global']) ?></div>
<?php endif; ?>

<form method="post" action="/consultations" class="card">
    <div class="form-group">
        <label>Họ tên</label>
        <input name="name" value="<?= h($old['name'] ?? '') ?>">
        <?php if (!empty($errors['name'])): ?><div class="error-text"><?= h($errors['name']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input name="email" value="<?= h($old['email'] ?? '') ?>">
        <?php if (!empty($errors['email'])): ?><div class="error-text"><?= h($errors['email']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Số điện thoại</label>
        <input name="phone" value="<?= h($old['phone'] ?? '') ?>">
        <?php if (!empty($errors['phone'])): ?><div class="error-text"><?= h($errors['phone']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Khóa học quan tâm</label>
        <select name="course">
            <option value="">-- Chọn khóa học --</option>
            <option value="web" <?= (($old['course'] ?? '') === 'web') ? 'selected' : '' ?>>Web Development</option>
            <option value="design" <?= (($old['course'] ?? '') === 'design') ? 'selected' : '' ?>>Digital Design</option>
            <option value="marketing" <?= (($old['course'] ?? '') === 'marketing') ? 'selected' : '' ?>>Digital Marketing</option>
        </select>
        <?php if (!empty($errors['course'])): ?><div class="error-text"><?= h($errors['course']) ?></div><?php endif; ?>
    </div>

    <div class="form-group">
        <label>Nội dung cần tư vấn</label>
        <textarea name="message"><?= h($old['message'] ?? '') ?></textarea>
        <?php if (!empty($errors['message'])): ?><div class="error-text"><?= h($errors['message']) ?></div><?php endif; ?>
    </div>

    <div class="honeypot">
        <label>Website</label>
        <input name="website" tabindex="-1" autocomplete="off">
    </div>

    <button class="btn primary" type="submit">Gửi đăng ký</button>
    <a class="btn secondary" href="/consultations">Quay lại</a>
</form>