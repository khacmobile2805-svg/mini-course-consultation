# Mini Course Consultation Portal

Cổng đăng ký tư vấn khóa học, xây dựng bằng **PHP thuần** (không framework, không database). Dự án thuộc **Lab04 — PHP Secure Forms, PRG, Anti-spam & Session Login Flow**, tập trung vào xử lý form an toàn và luồng đăng nhập/phiên bảo mật.

---

## 1. Tính năng chính

- **Front Controller + Router**: mọi request qua `public/index.php`, phân loại GET/POST và bắt lỗi 404/405.
- **Form bảo mật 3 lớp**: đọc input an toàn (`?? ''`), chuẩn hóa (`trim()`), escape output chống XSS (`htmlspecialchars`).
- **Validation server-side theo trình tự**: Required → Format → Logic (in-list, độ dài), lỗi hiển thị từng field, giữ dữ liệu cũ (sticky form).
- **PRG (Post/Redirect/Get)**: chống bấm F5 gửi trùng.
- **Flash message**: thông báo chỉ hiện một lần.
- **Anti-spam**: honeypot và rate limit.
- **Bảo mật Session/Login**: cookie flags (HttpOnly, SameSite, Secure), `session_regenerate_id()` chống Session Fixation, idle timeout, clean logout.

## 2. Yêu cầu môi trường

- PHP >= 8.0
- Composer

## 3. Cài đặt & chạy

```bash
cd mini-course-consultation
composer dump-autoload
php -S localhost:8000 -t public
```

Mở: http://localhost:8000/ — Tài khoản demo: `student@example.com` / `123456`

> Lưu ý: chạy lệnh tại thư mục chứa `composer.json` và thư mục `public`.

## 4. Danh sách Route

| Method | URL | Controller@Action |
|--------|-----|-------------------|
| GET | / | HomeController@index |
| GET | /consultations | ConsultationController@index |
| GET | /consultations/create | ConsultationController@create |
| POST | /consultations | ConsultationController@store |
| GET | /login | AuthController@login |
| POST | /login | AuthController@handleLogin |
| POST | /logout | AuthController@logout |
| GET | /dashboard | DashboardController@index |
| GET | /session-demo | DashboardController@sessionDemo |

URL không tồn tại → 404; route có nhưng sai method → 405.

## 5. Kỹ thuật bảo mật (vị trí trong code)

| Kỹ thuật | Vị trí | Mục đích |
|----------|--------|----------|
| Fallback `?? ''` + `trim()` | `ConsultationController::store()` | Đọc input an toàn, chuẩn hóa |
| Escape `h()` | mọi view | Chống XSS |
| Validate theo trình tự | `ConsultationController::validate()` | Required → Format → Logic |
| Honeypot + Rate limit | `validate()` | Chống bot, chống gửi liên tục |
| PRG | `store()` redirect | Chống F5 trùng |
| Cookie flags | `public/index.php` | HttpOnly, SameSite=Lax, Secure (HTTPS) |
| Session Fixation | `session_regenerate_id(true)` trong `handleLogin()` | Đổi ID sau xác thực |
| Idle timeout (15 phút) | `check_session_timeout()` | Hết hạn tab bỏ quên |
| Clean logout | `logout_clean()` | Xóa biến + cookie + destroy |

## 6. Kiểm thử

16 test bắt buộc (T01–T16) được kiểm tra trên `http://localhost:8000`, ảnh minh chứng và mô tả nằm trong báo cáo. Có thể test bằng `curl` hoặc Postman, ví dụ:

```bash
# Gửi form hợp lệ
curl -i -X POST http://localhost:8000/consultations \
  -d "name=Nguyen Van A&email=a@example.com&phone=0901234567&course=web&message=Tu van"

# Sai method (route chỉ nhận POST) -> 405
curl -i http://localhost:8000/logout

# Route không tồn tại -> 404
curl -i http://localhost:8000/khong-ton-tai
```

## 7. Phạm vi (giới hạn có chủ đích)

Lab04 dùng file JSON thay database và chưa có CSRF token (SameSite=Lax chỉ giảm CSRF, chưa chặn hoàn toàn). Đây là các điểm sẽ nâng cấp khi mở rộng thành dự án thật (PDO/database, CSRF token, logging, RBAC).

## 8. Ghi chú kỹ thuật

- Dữ liệu lưu vào `storage/consultations.json` với `JSON_UNESCAPED_UNICODE` để giữ tiếng Việt.
- Mật khẩu băm bằng `password_hash()`, xác thực bằng `password_verify()`.Get-Content .\mini-course-consultation\README.md