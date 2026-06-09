# Mini Course Consultation Portal

Cổng đăng ký tư vấn khóa học, xây dựng bằng **PHP thuần** (không framework, không database). Dự án thuộc **Lab04 — PHP Secure Forms, PRG, Anti-spam & Session Login Flow**, tập trung vào xử lý form an toàn và luồng đăng nhập/phiên bảo mật.

---

## 1. Tính năng chính

- **Kiến trúc Front Controller + Router**: mọi request đi qua một cửa duy nhất `public/index.php`, phân loại GET/POST và bắt lỗi 404/405 chính xác.
- **Form bảo mật 3 lớp**: đọc input an toàn (`?? ''`), chuẩn hóa (`trim()`), escape output chống XSS (`htmlspecialchars`).
- **Validation phía server theo trình tự**: Required → Format → Logic (in-list, độ dài), gom lỗi và hiển thị ngay dưới từng field, giữ lại dữ liệu cũ (sticky form).
- **PRG (Post/Redirect/Get)**: chống bấm F5 gửi trùng dữ liệu.
- **Flash message**: thông báo chỉ hiện đúng một lần.
- **Anti-spam**: honeypot (bẫy bot) và rate limit (chặn gửi quá nhanh).
- **Bảo mật Session/Login**: cookie flags (HttpOnly, SameSite, Secure), `session_regenerate_id()` chống Session Fixation, idle timeout, clean logout.

---

## 2. Yêu cầu môi trường

- PHP >= 8.0
- Composer

Kiểm tra nhanh:

```bash
php -v
composer -V
```

---

## 3. Cài đặt & chạy

```bash
# 1. Vào thư mục project
cd mini-course-consultation

# 2. Tạo autoload (sinh thư mục vendor/)
composer dump-autoload

# 3. Khởi động server tích hợp của PHP
php -S localhost:8000 -t public
```

Mở trình duyệt tại: **http://localhost:8000/**

Dừng server: nhấn `Ctrl + C` trong terminal.

> Lưu ý: lệnh `composer` phải chạy tại thư mục chứa `composer.json`; lệnh `php -S ... -t public` phải chạy tại thư mục chứa thư mục `public`.

---

## 4. Tài khoản demo

| Email                 | Password |
|-----------------------|----------|
| student@example.com   | 123456   |

---

## 5. Cấu trúc thư mục

```
mini-course-consultation/
├── app/
│   ├── Controllers/        # HomeController, ConsultationController, AuthController, DashboardController
│   ├── Core/               # Router, Response
│   └── Support/            # helpers.php (escape, redirect, flash, session)
├── public/
│   ├── index.php           # Front Controller + cấu hình session
│   └── assets/style.css
├── storage/
│   └── consultations.json  # Lưu lead (thay cho database)
├── views/
│   ├── layout.php
│   ├── home.php
│   ├── consultations/      # create.php, index.php
│   ├── auth/login.php
│   ├── dashboard.php
│   └── errors/             # 404.php, 405.php
├── composer.json
└── README.md
```

---

## 6. Danh sách Route

| Method | URL                       | Controller@Action                  | Mô tả                          |
|--------|---------------------------|------------------------------------|--------------------------------|
| GET    | /                         | HomeController@index               | Trang tổng quan                |
| GET    | /consultations            | ConsultationController@index       | Danh sách lead + flash         |
| GET    | /consultations/create     | ConsultationController@create      | Form đăng ký tư vấn            |
| POST   | /consultations            | ConsultationController@store       | Validate + anti-spam + lưu + PRG |
| GET    | /login                    | AuthController@login               | Form đăng nhập                 |
| POST   | /login                    | AuthController@handleLogin         | Verify + regenerate + redirect |
| POST   | /logout                   | AuthController@logout              | Logout sạch + redirect login   |
| GET    | /dashboard                | DashboardController@index          | Chỉ cho user đã đăng nhập      |
| GET    | /session-demo             | DashboardController@sessionDemo    | JSON debug trạng thái session  |

---

## 7. Các kỹ thuật bảo mật áp dụng

| Kỹ thuật              | Vị trí trong code                              | Mục đích                                  |
|-----------------------|------------------------------------------------|-------------------------------------------|
| Fallback `?? ''`      | `ConsultationController::store()`              | Chống thiếu key khi user xóa field (F12)  |
| Normalize `trim()`    | `store()`, `handleLogin()`                     | Cắt khoảng trắng thừa                      |
| Escape `htmlspecialchars` | helper `h()` dùng ở mọi view               | Chống XSS                                 |
| Validate theo trình tự | `ConsultationController::validate()`          | Required → Format → Logic                 |
| Honeypot              | field ẩn `website` + `validate()`              | Bẫy bot tự động                           |
| Rate limit            | `validate()` (5 giây)                          | Chặn gửi form liên tục                     |
| PRG                   | `store()` redirect sau khi lưu                 | Chống F5 tạo dữ liệu trùng                 |
| Cookie flags          | `public/index.php`                             | HttpOnly, SameSite=Lax, Secure (HTTPS)    |
| Session Fixation      | `session_regenerate_id(true)` trong `handleLogin()` | Đổi ID sau khi xác thực              |
| Idle timeout          | `check_session_timeout()` (15 phút)            | Đăng xuất tab bị bỏ quên                   |
| Clean logout          | `logout_clean()`                               | Xóa biến + cookie + destroy session        |

---

## 8. Kiểm thử

Có thể test bằng trình duyệt hoặc bằng `curl`/Postman. Ví dụ một số case:

```bash
# Gửi form hợp lệ
curl -i -X POST http://localhost:8000/consultations \
  -d "name=Nguyen Van A&email=a@example.com&phone=0901234567&course=web&message=Tu van"

# Gửi thiếu field (sẽ bị redirect về form với lỗi)
curl -i -X POST http://localhost:8000/consultations -d "name=&email=&phone=&course="

# Kích hoạt honeypot (điền field ẩn website)
curl -i -X POST http://localhost:8000/consultations \
  -d "name=Bot&email=b@x.com&phone=0901234567&course=web&website=spam"

# Gọi sai method (route tồn tại nhưng dùng GET cho /logout) -> 405
curl -i http://localhost:8000/logout

# Route không tồn tại -> 404
curl -i http://localhost:8000/khong-ton-tai
```

Bảng test case đầy đủ (14 case) xem trong báo cáo `BaoCao_Lab04.docx`.

---

## 9. Ghi chú kỹ thuật

- Dữ liệu lead lưu vào `storage/consultations.json` bằng `json_encode(..., JSON_UNESCAPED_UNICODE)` để giữ nguyên tiếng Việt.
- Mật khẩu lưu dạng băm bằng `password_hash()`, xác thực bằng `password_verify()` — không lưu plaintext.
- Logic render view gộp vào helper `view()` / `view_path()`.