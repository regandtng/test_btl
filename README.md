# 🏸 SmashZone — Hệ Thống Đặt Sân Cầu Lông Online

> Website đặt sân cầu lông trực tuyến với 13 chi nhánh, 130 sân, hỗ trợ đặt sân 24/7.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## ✨ Tính Năng

### 👥 Khách Hàng
- Xem danh sách 13 chi nhánh với bộ lọc theo thành phố và tìm kiếm
- Xem chi tiết 10 sân tại mỗi chi nhánh
- **Đặt sân theo giờ** với timeline trực quan (sân trống / đã đặt)
- Xác nhận thông tin khách hàng
- Thanh toán qua **QR ngân hàng**, **MoMo** hoặc **tiền mặt tại sân**
- Nhận **mã đặt sân** unique (BB-YYYYMMDD-XXXXX)
- Xem lịch sử đặt sân (theo tài khoản hoặc số điện thoại)

### 🔧 Quản Trị (Admin / Owner)
- Dashboard thống kê: lượt đặt hôm nay, doanh thu, biểu đồ 7 ngày
- Quản lý chi nhánh và sân (CRUD)
- Quản lý lịch đặt: lọc theo ngày/chi nhánh/trạng thái, cập nhật trạng thái
- Quản lý bảng giá theo khung giờ và loại ngày
- Xem doanh thu theo tháng, theo chi nhánh (Chart.js)

---

## 🏗️ Kiến Trúc Dự Án

```
BadmintonBooking/
├── app/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── BranchController.php
│   │   ├── BookingController.php
│   │   ├── PaymentController.php
│   │   └── OwnerController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Branch.php
│   │   ├── Court.php
│   │   ├── Booking.php
│   │   ├── Payment.php
│   │   └── PricingRule.php
│   └── Views/
│       ├── layouts/ (main.php, admin.php)
│       ├── home/
│       ├── branches/
│       ├── bookings/
│       ├── auth/
│       └── owner/
├── core/
│   ├── Database.php   (PDO Singleton)
│   ├── Session.php    (Session + CSRF)
│   ├── Router.php     (URL Router)
│   ├── Controller.php (Base Controller)
│   └── Model.php      (Base Model)
├── config/
│   └── database.php
├── routes/
│   └── web.php
├── public/
│   ├── index.php      (Entry point)
│   ├── .htaccess
│   └── assets/css/, js/, images/
├── database/
│   └── schema.sql
└── tests/
    └── BookingTest.php
```

---

## ⚙️ Yêu Cầu Hệ Thống

- **PHP** >= 8.1
- **MySQL** >= 8.0
- **Apache** với `mod_rewrite` enabled (hoặc Nginx)
- **PHP Extensions**: `pdo`, `pdo_mysql`, `mbstring`, `json`

---

## 🚀 Cài Đặt

### 1. Clone / Copy dự án

Dự án **portable** — không hardcode đường dẫn. Bạn có thể đặt thư mục ở bất kỳ
vị trí nào, với bất kỳ tên nào mà không cần sửa code.

```bash
# Cách 1: Clone qua Git (khuyến nghị) vào web root
cd C:/xampp/htdocs
git clone <your-repo-url> BadmintonBooking

# Cách 2: Copy thư mục dự án vào web root
cp -r BadmintonBooking/ C:/xampp/htdocs/
```

> ℹ️ Tên thư mục (`BadmintonBooking`) có thể đổi tùy ý. Ứng dụng tự nhận diện
> `BASE_URL` từ request nên không bị sai đường dẫn khi đổi máy/thư mục.

### 2. Database (tự động cài đặt) ✅

Không cần làm gì! Ở lần truy cập **đầu tiên**, ứng dụng sẽ tự:
1. Tạo file `.env` từ `.env.example` (nếu chưa có).
2. Tạo database và import `database/schema.sql`.

Điều kiện duy nhất: MySQL phải đang chạy và thông tin trong `.env` đúng
(mặc định XAMPP là `root` / mật khẩu rỗng).

Nếu database của bạn dùng **mật khẩu khác**, hoặc muốn dùng tên DB khác,
chỉnh `.env` trước khi chạy:
```env
DB_HOST=localhost
DB_DATABASE=badminton_booking
DB_USERNAME=root
DB_PASSWORD=your_password
AUTO_INSTALL=true      # đặt false để tắt tự cài đặt
```

> ℹ️ Sau khi cài xong, file `.installed` được tạo để bỏ qua bước này ở các lần sau.
> Muốn cài lại/tạo lại DB: xoá file `.installed` rồi tải lại trang.
> `APP_URL` **không bắt buộc** — chỉ cần khi chạy ngoài web entry point (CLI).

<details>
<summary>Nếu muốn import thủ công (không dùng tự động)</summary>

```bash
# Tạo database trước, sau đó import (schema KHÔNG tự tạo database)
mysql -u root -p -e "CREATE DATABASE badminton_booking CHARACTER SET utf8mb4"
mysql -u root -p badminton_booking < database/schema.sql
```

Hoặc qua phpMyAdmin: tạo database `badminton_booking` → Import → chọn `database/schema.sql`.
</details>

### 3. Cấu hình Web Server

**Apache (XAMPP):** Đảm bảo `mod_rewrite` đã bật:
```apache
# Trong httpd.conf
LoadModule rewrite_module modules/mod_rewrite.so

# Trong VirtualHost hoặc Directory
AllowOverride All
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Truy cập ứng dụng

```
http://localhost/<tên-thư-mục-của-bạn>/public/
# Ví dụ: http://localhost/BadmintonBooking/public/
```

---

## 🔑 Tài Khoản Mặc Định

| Loại | Username | Password |
|------|----------|----------|
| Super Admin | `admin` | `admin123` |
| Customer (test) | `test@smashzone.vn` | `password123` |

Đường dẫn đăng nhập admin: `/owner/login`

---

## 💰 Bảng Giá Sân

| Khung Giờ | Ngày Thường | Cuối Tuần |
|-----------|-------------|-----------|
| Thấp điểm (5:00-8:00, 14:00-17:00) | 80,000 đ/h | 96,000 đ/h |
| Cao điểm (8:00-14:00, 17:00-22:00) | 150,000 đ/h | 180,000 đ/h |

---

## 🧪 Chạy Tests

```bash
# Từ thư mục gốc dự án
php tests/BookingTest.php
```

---

## 📍 Danh Sách Chi Nhánh

| # | Tên Chi Nhánh | Thành Phố |
|---|---------------|-----------|
| 1-5 | SmashZone Cầu Giấy, Đống Đa, Tây Hồ, Hoàng Mai, Thanh Xuân | Hà Nội |
| 6-9 | SmashZone Quận 1, Quận 7, Bình Thạnh, Gò Vấp | TP. HCM |
| 10-11 | SmashZone Hải Châu, Thanh Khê | Đà Nẵng |
| 12 | SmashZone Lê Chân | Hải Phòng |
| 13 | SmashZone Ninh Kiều | Cần Thơ |

---

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: PHP 8.1 (Custom MVC Framework)
- **Database**: MySQL 8.0
- **Frontend**: HTML5, CSS3 (Vanilla), JavaScript (ES6+)
- **Charts**: Chart.js
- **Icons**: Emoji + Unicode
- **Fonts**: Inter (Google Fonts)

---

## 📄 License

MIT License — Tự do sử dụng cho mục đích học tập và thương mại.

---

*Built with ❤️ by SmashZone Team*
