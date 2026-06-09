<?php
// app/Controllers/ConsultationController.php
namespace App\Controllers;

class ConsultationController
{
    private array $allowedCourses = ['web', 'design', 'marketing'];

    public function create(): void
    {
        $old = flash_get('old', []);
        $errors = flash_get('errors', []);

        view('consultations/create', [
            'view' => 'consultations/create',
            'old' => $old,
            'errors' => $errors,
            'allowedCourses' => $this->allowedCourses,
        ]);
    }

    public function index(): void
    {
        view('consultations/index', [
            'view' => 'consultations/index',
            'items' => $this->loadConsultations(),
        ]);
    }

    public function store(): void
    {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'course' => trim($_POST['course'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
            'website' => trim($_POST['website'] ?? ''),
        ];

        $errors = $this->validate($data);

        if (!empty($errors)) {
            flash_set('errors', $errors);
            flash_set('old', [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'course' => $data['course'],
                'message' => $data['message'],
            ]);
            redirect('/consultations/create');
        }

        $this->saveConsultation($data);
        $_SESSION['last_consultation_submit_at'] = time();

        flash_set('success', 'Đăng ký tư vấn thành công. PRG đã redirect về trang danh sách.');
        redirect('/consultations');
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['website'] !== '') {
            $errors['_global'] = 'Yêu cầu bị từ chối do phát hiện hành vi giống bot.';
        }

        $lastSubmit = $_SESSION['last_consultation_submit_at'] ?? 0;
        if ($lastSubmit && time() - $lastSubmit < 5) {
            $errors['_global'] = 'Bạn gửi quá nhanh. Vui lòng thử lại sau vài giây.';
        }

        if ($data['name'] === '') {
            $errors['name'] = 'Vui lòng nhập họ tên.';
        } elseif (mb_strlen($data['name']) < 2) {
            $errors['name'] = 'Họ tên phải có ít nhất 2 ký tự.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Vui lòng nhập email.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if ($data['phone'] === '') {
            $errors['phone'] = 'Vui lòng nhập số điện thoại.';
        } elseif (!preg_match('/^[0-9+\-\s]{9,15}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại chỉ gồm số, khoảng trắng, + hoặc -, dài 9-15 ký tự.';
        }

        if ($data['course'] === '' || !in_array($data['course'], $this->allowedCourses, true)) {
            $errors['course'] = 'Vui lòng chọn khóa học hợp lệ.';
        }

        if ($data['message'] !== '' && mb_strlen($data['message']) > 300) {
            $errors['message'] = 'Nội dung không được vượt quá 300 ký tự.';
        }

        return $errors;
    }

    private function storageFile(): string
    {
        return __DIR__ . '/../../storage/consultations.json';
    }

    private function loadConsultations(): array
    {
        $json = file_get_contents($this->storageFile());
        return json_decode($json, true) ?: [];
    }

    private function saveConsultation(array $data): void
    {
        $items = $this->loadConsultations();
        $items[] = [
            'id' => 'L' . str_pad((string) (count($items) + 1), 3, '0', STR_PAD_LEFT),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'course' => $data['course'],
            'message' => $data['message'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        file_put_contents($this->storageFile(), json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}