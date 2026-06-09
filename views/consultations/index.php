<?php /** @var array $items */ ?>
<!-- views/consultations/index.php -->
<h1>Danh sách lead tư vấn</h1>
<p><a class="btn primary" href="/consultations/create">Tạo lead mới</a></p>

<table class="table">
    <thead>
    <tr>
        <th>ID</th><th>Họ tên</th><th>Email</th><th>Phone</th><th>Course</th><th>Created at</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= h($item['id']) ?></td>
            <td><?= h($item['name']) ?></td>
            <td><?= h($item['email']) ?></td>
            <td><?= h($item['phone']) ?></td>
            <td><?= h($item['course']) ?></td>
            <td><?= h($item['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>