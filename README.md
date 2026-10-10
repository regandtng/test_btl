# SmashZone — He Thong Dat San Cau Long Online

> Website dat san cau long truc tuyen voi 13 chi nhanh, 130 san, ho tro dat san 24/7.

---

## Tinh Nang

### Khach Hang
- Xem danh sach 13 chi nhanh voi bo loc theo thanh pho va tim kiem
- Xem chi tiet 10 san tai moi chi nhanh
- **Dat san theo gio** voi timeline truc quan (san trong / da dat)
- Xac nhan thong tin khach hang
- Thanh toan qua **QR ngan hang**, **MoMo** hoac **tien mat tai san**
- Nhan **ma dat san** unique (BB-YYYYMMDD-XXXXX)
- Xem lich su dat san (theo tai khoan hoac so dien thoai)
- **Ho so ca nhan**: Cap nhat ten/email/SDT, doi mat khau
- **Thanh toan co thoi han 3 phut**: Countdown timer, qua han tu huy + giai phong san

### Quan Tri (Admin / Owner)
- Dashboard thong ke: luot dat hom nay, doanh thu, bieu do 7 ngay
- Quan ly chi nhanh va san (CRUD)
- Quan ly lich dat: loc theo ngay/chi nhanh/trang thai, cap nhat trang thai
- Quan ly bang gia theo khung gio va loai ngay
- Xem doanh thu theo thang, theo chi nhanh (Chart.js)
- **Workflow trang thai ro rang**: Pending (cho thanh toan) -> Confirmed (da xac nhan) -> Cancelled (tu dong khi het gio)

---

## Logic Trang Thai Booking

```
PENDING (3 phut) 
    | Chon san, dien thong tin, cho thanh toan
    | Countdown 3:00 -> 0:00
    | Het gio = AUTO CANCEL + Giai phong san
    v
CONFIRMED
    | Da thanh toan (QR/MoMo/COD)
    | San khoa chac chan
    v
CANCELLED (Tu dong khi het gio)
    | Het gio choi (end_time < NOW)
    | Cron chay 5-10p/l tu chuyen confirmed -> cancelled
    | San duoc GIAI PHONG, khach khac co the dat lai
```

---

## Cron Job (Tu dong hoa)

File: `cron.php` — Chay dinh ky 5-10 phut/lan qua Windows Task Scheduler

| Chuc nang | SQL |
|-----------|-----|
| **Auto-expire** | `confirmed` + `end_time < NOW()` -> `cancelled` (giai phong san) |
| **Cleanup pending** | `pending` + `expires_at < NOW()` -> `cancelled` |

**Cai dat Windows Task Scheduler:**
- Program: `C:\xampp\php\php.exe`
- Arguments: `C:\xampp\htdocs\test_btl\cron.php --all`
- Start in: `C:\xampp\htdocs\test_btl`
- Trigger: Daily -> Repeat every **5 minutes** -> Duration: **1 day**

Test thu cong:
```bash
C:\xampp\php\php.exe C:\xampp\htdocs\test_btl\cron.php --all
```

---

## Kien Truc Du An

```
BadmintonBooking/
├── app/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── AuthController.php      (Login, Register, Profile, ChangePassword)
│   │   ├── BranchController.php
│   │   ├── BookingController.php
│   │   ├── PaymentController.php   (Countdown 3p, auto expire)
│   │   └── OwnerController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Branch.php
│   │   ├── Court.php
│   │   ├── Booking.php             (isSlotAvailable bo qua pending het han)
│   │   ├── Payment.php
│   │   └── PricingRule.php
│   └── Views/
│       ├── layouts/ (main.php, admin.php)
│       ├── home/
│       ├── branches/
│       ├── bookings/ (create, confirm, payment, success, history, detail)
│       ├── auth/     (login, register, profile)
│       └── owner/
├── core/
│   ├── Database.php   (PDO Singleton)
│   ├── Session.php    (Session + CSRF + Flash)
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
│   └── assets/css/, js/, images/ (qr-bank.png o day)
├── database/
│   └── schema.sql
├── tests/
│   └── BookingTest.php
└── cron.php           (Auto-complete + Cleanup)
```

---

## Yeu Cau He Thong

- **PHP** >= 8.1
- **MySQL** >= 8.0
- **Apache** voi `mod_rewrite` enabled (hoac Nginx)
- **PHP Extensions**: `pdo`, `pdo_mysql`, `mbstring`, `json`

---

## Cai Dat

### 1. Clone / Copy du an

Du an **portable** — khong hardcode duong dan. Ban co the dat thu muc o bat ky vi tri nao.

```bash
# Cach 1: Clone qua Git (khuyen nghi) vao web root
cd C:/xampp/htdocs
git clone <your-repo-url> BadmintonBooking

# Cach 2: Copy thu muc du an vao web root
cp -r BadmintonBooking/ C:/xampp/htdocs/
```

> Ten thu muc (`BadmintonBooking`) co the doi tuy y. Ung dung tu nhan dien `BASE_URL` tu request.

### 2. Database (tu dong cai dat)

Khong can lam gi! O lan truy cap **dau tien**, ung dung se tu:
1. Tao file `.env` tu `.env.example` (neu chua co).
2. Tao database va import `database/schema.sql`.

Dieu kien: MySQL dang chay, thong tin trong `.env` dung (mac dinh XAMPP: `root` / mat khau rong).

Neu DB dung mat khau khac:
```env
DB_HOST=localhost
DB_DATABASE=badminton_booking
DB_USERNAME=root
DB_PASSWORD=your_password
AUTO_INSTALL=true      # dat false de tat tu cai dat
```

> Sau khi cai xong, file `.installed` duoc tao. Muon cai lai: xoa file `.installed` roi tai lai trang.

### 3. Cau hinh Web Server

**Apache (XAMPP):**
```apache
LoadModule rewrite_module modules/mod_rewrite.so
AllowOverride All
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. QR Code Ngan Hang

Dat file QR code vao:
```
public/assets/images/qr-bank.png
```
- Format: PNG/JPG, kich thuoc 200x200px (vuong)
- Noi dung: Ma QR VietQR/VNPAY cua tai khoan SmashZone

### 5. Truy cap ung dung

```
http://localhost/<ten-thu-muc>/public/
# Vi du: http://localhost/BadmintonBooking/public/
```

---

## Tai Khoan Mac Dinh

| Loai | Username | Password |
|------|----------|----------|
| Super Admin | `admin` | `admin123` |
| Customer (test) | `test@smashzone.vn` | `password123` |

Dang nhap admin: `/owner/login`

---

## Bang Gia San

| Khung Gio | Ngay Thuong | Cuoi Tuan |
|-----------|-------------|-----------|
| Thap diem (5:00-8:00, 14:00-17:00) | 80,000 d/h | 96,000 d/h |
| Cao diem (8:00-14:00, 17:00-22:00) | 150,000 d/h | 180,000 d/h |

---

## Chay Tests

```bash
# Tu thu muc goc du an
php tests/BookingTest.php
```

---

## Danh Sach Chi Nhanh (13 chi nhanh, 130 san)

| # | Ten Chi Nhanh | Thanh Pho |
|---|---------------|-----------|
| 1-5 | SmashZone Cau Giay, Dong Da, Tay Ho, Hoang Mai, Thanh Xuan | Ha Noi |
| 6-9 | SmashZone Quan 1, Quan 7, Binh Thanh, Go Vap | TP. HCM |
| 10-11 | SmashZone Hai Chau, Thanh Khe | Da Nang |
| 12 | SmashZone Le Chan | Hai Phong |
| 13 | SmashZone Ninh Kieu | Can Tho |

---

## Cong Nghe Su Dung

- **Backend**: PHP 8.1 (Custom MVC Framework, no dependencies)
- **Database**: MySQL 8.0
- **Frontend**: HTML5, CSS3 (Vanilla), JavaScript (ES6+)
- **Charts**: Chart.js
- **Icons**: Unicode + SVG inline
- **Fonts**: Inter (Google Fonts)

---

## Thong Tin Lien He

- **Hotline**: 0976779516
- **Email**: support@smashzone.vn
- **Tru so**: So 4B, ngach 71, ngo 83 Tan Trieu, Ha Noi

---

## License

MIT License — Tu do su dung cho muc dich hoc tap va thuong mai.

---

## Changelog (Latest)

- [x] Ho so ca nhan khach hang (cap nhat info, doi mat khau)
- [x] Dropdown menu user tren header
- [x] Thanh toan co countdown 3 phut, auto expire + giai phong san
- [x] Booking Model: bo qua pending het han khi check slot
- [x] Cron job: auto-expire + cleanup expired pending
- [x] Admin workflow: Pending -> Confirmed -> Cancelled (tu dong khi het gio, giai phong san)
- [x] QR Code ngan hang dong tu file anh
- [x] MoMo phone: 0981622131
- [x] Footer: Hotline 0976779516, Tru so Ha Noi