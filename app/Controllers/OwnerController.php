<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Branch;
use App\Models\Court;
use App\Models\Booking;
use App\Models\PricingRule;
use Core\Session;
use Core\Database;

class OwnerController extends Controller
{
    // ─────────────────── AUTH ───────────────────

    public function loginForm()
    {
        if (Session::get('owner_id')) {
            $this->redirect('/owner/dashboard');
        }
        $this->render('owner/login', [
            'title' => 'Đăng Nhập Quản Trị - SmashZone',
        ]);
    }

    public function login()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            Session::flash('error', 'Vui lòng nhập đầy đủ thông tin.');
            $this->redirect('/owner/login');
        }

        $db = Database::getInstance();
        $owner = $db->query(
            'SELECT * FROM owners WHERE username = ? LIMIT 1',
            [$username]
        )->fetch(\PDO::FETCH_ASSOC);

        if ($owner && password_verify($password, $owner['password'])) {
            Session::set('owner_id',       $owner['id']);
            Session::set('owner_name',     $owner['name']);
            Session::set('owner_is_super', $owner['is_super_admin']);

            // Load managed branches
            if (!$owner['is_super_admin']) {
                $branches = $db->query(
                    'SELECT branch_id FROM owner_branches WHERE owner_id = ?',
                    [$owner['id']]
                )->fetchAll(\PDO::FETCH_COLUMN);
                Session::set('owner_branches', $branches);
            }

            Session::flash('success', 'Đăng nhập thành công! Chào mừng ' . $owner['name']);
            $this->redirect('/owner/dashboard');
        } else {
            Session::flash('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
            $this->redirect('/owner/login');
        }
    }

    public function logout()
    {
        Session::set('owner_id',       null);
        Session::set('owner_name',     null);
        Session::set('owner_is_super', null);
        Session::set('owner_branches', null);
        Session::flash('success', 'Đã đăng xuất.');
        $this->redirect('/owner/login');
    }

    // ─────────────────── DASHBOARD ───────────────────

    public function dashboard()
    {
        $this->requireOwner();

        $db           = Database::getInstance();
        $bookingModel = new Booking();
        $branchModel  = new Branch();

        $todayStr = date('Y-m-d');

        $totalBranches      = $branchModel->count();
        $totalCourts        = 130;
        $todayBookings      = $db->query(
            "SELECT COUNT(*) FROM bookings WHERE booking_date = ? AND status != 'cancelled'",
            [$todayStr]
        )->fetchColumn();
        $todayRevenue       = $db->query(
            "SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE booking_date = ? AND status IN ('confirmed','completed')",
            [$todayStr]
        )->fetchColumn();
        $monthRevenue       = $db->query(
            "SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ? AND status IN ('confirmed','completed')",
            [date('Y-m')]
        )->fetchColumn();

        // Revenue last 7 days for chart
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $d   = date('Y-m-d', strtotime("-{$i} days"));
            $rev = $db->query(
                "SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE booking_date = ? AND status IN ('confirmed','completed')",
                [$d]
            )->fetchColumn();
            $revenueChart[] = ['date' => $d, 'revenue' => (int)$rev];
        }

        $recentBookings = $db->query(
            "SELECT b.*, br.name AS branch_name, c.name AS court_name
             FROM bookings b
             JOIN branches br ON b.branch_id = br.id
             JOIN courts c ON b.court_id = c.id
             ORDER BY b.created_at DESC LIMIT 10"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('owner/dashboard', [
            'title'          => 'Dashboard - Quản Trị SmashZone',
            'totalBranches'  => $totalBranches,
            'totalCourts'    => $totalCourts,
            'todayBookings'  => $todayBookings,
            'todayRevenue'   => $todayRevenue,
            'monthRevenue'   => $monthRevenue,
            'revenueChart'   => $revenueChart,
            'recentBookings' => $recentBookings,
        ]);
    }

    // ─────────────────── BRANCHES ───────────────────

    public function branches()
    {
        $this->requireOwner();

        $db = Database::getInstance();
        $branches = $db->query(
            "SELECT br.*, COUNT(c.id) AS courts_count
             FROM branches br
             LEFT JOIN courts c ON c.branch_id = br.id
             GROUP BY br.id ORDER BY br.id"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('owner/branches', [
            'title'    => 'Quản Lý Chi Nhánh - SmashZone Admin',
            'branches' => $branches,
        ]);
    }

    public function storeBranch()
    {
        $this->requireOwner();

        $name      = trim($_POST['name'] ?? '');
        $address   = trim($_POST['address'] ?? '');
        $city      = trim($_POST['city'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $openTime  = $_POST['open_time'] ?? '05:00';
        $closeTime = $_POST['close_time'] ?? '22:00';
        $desc      = trim($_POST['description'] ?? '');

        if (empty($name) || empty($address) || empty($city)) {
            Session::flash('error', 'Vui lòng điền đầy đủ thông tin chi nhánh.');
            $this->redirect('/owner/branches');
        }

        $db = Database::getInstance();
        $db->query(
            "INSERT INTO branches (name, address, city, phone, description, open_time, close_time, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW())",
            [$name, $address, $city, $phone, $desc, $openTime, $closeTime]
        );
        $branchId = $db->lastInsertId();

        // Auto-create 10 courts for new branch
        for ($i = 1; $i <= 10; $i++) {
            $db->query(
                "INSERT INTO courts (branch_id, name, type, status, created_at) VALUES (?, ?, 'standard', 'active', NOW())",
                [$branchId, 'A' . $i]
            );
        }

        Session::flash('success', "Đã thêm chi nhánh \"{$name}\" với 10 sân.");
        $this->redirect('/owner/branches');
    }

    public function updateBranch()
    {
        $this->requireOwner();

        $id        = $_POST['id'] ?? null;
        $name      = trim($_POST['name'] ?? '');
        $address   = trim($_POST['address'] ?? '');
        $city      = trim($_POST['city'] ?? '');
        $phone     = trim($_POST['phone'] ?? '');
        $isActive  = isset($_POST['is_active']) ? 1 : 0;

        if (!$id || empty($name)) {
            Session::flash('error', 'Dữ liệu không hợp lệ.');
            $this->redirect('/owner/branches');
        }

        $db = Database::getInstance();
        $db->query(
            "UPDATE branches SET name=?, address=?, city=?, phone=?, is_active=? WHERE id=?",
            [$name, $address, $city, $phone, $isActive, $id]
        );

        Session::flash('success', 'Đã cập nhật chi nhánh.');
        $this->redirect('/owner/branches');
    }

    public function deleteBranch()
    {
        $this->requireOwner();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $db->query("UPDATE branches SET is_active = 0 WHERE id = ?", [$id]);
            Session::flash('success', 'Đã vô hiệu hoá chi nhánh.');
        }
        $this->redirect('/owner/branches');
    }

    // ─────────────────── BOOKINGS ───────────────────

    public function bookings()
    {
        $this->requireOwner();

        $db         = Database::getInstance();
        $date       = $_GET['date'] ?? date('Y-m-d');
        $branchId   = $_GET['branch_id'] ?? '';
        $status     = $_GET['status'] ?? '';

        $sql    = "SELECT b.*, br.name AS branch_name, c.name AS court_name
                   FROM bookings b
                   JOIN branches br ON b.branch_id = br.id
                   JOIN courts c ON b.court_id = c.id
                   WHERE 1=1";
        $params = [];

        if ($date) {
            $sql     .= " AND b.booking_date = ?";
            $params[] = $date;
        }
        if ($branchId) {
            $sql     .= " AND b.branch_id = ?";
            $params[] = $branchId;
        }
        if ($status) {
            $sql     .= " AND b.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY b.booking_date DESC, b.start_time ASC";

        $bookings = $db->query($sql, $params)->fetchAll(\PDO::FETCH_ASSOC);
        $branches = $db->query("SELECT id, name FROM branches WHERE is_active = 1 ORDER BY name")->fetchAll(\PDO::FETCH_ASSOC);
        $courts   = $db->query("SELECT id, name, branch_id FROM courts WHERE status = 'active' ORDER BY branch_id, name")->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('owner/bookings', [
            'title'    => 'Quản Lý Lịch Đặt - SmashZone Admin',
            'bookings' => $bookings,
            'branches' => $branches,
            'courts'   => $courts,
            'date'     => $date,
            'branchId' => $branchId,
            'status'   => $status,
        ]);
    }

    public function storeBooking()
    {
        $this->requireOwner();

        $branchId  = (int)($_POST['branch_id'] ?? 0);
        $courtId   = (int)($_POST['court_id'] ?? 0);
        $date      = $_POST['booking_date'] ?? date('Y-m-d');
        $startHour = (int)($_POST['start_hour'] ?? 0);
        $endHour   = (int)($_POST['end_hour'] ?? 0);
        $name      = trim($_POST['customer_name'] ?? '');
        $phone     = trim($_POST['customer_phone'] ?? '');
        $notes     = trim($_POST['notes'] ?? '');
        $status    = in_array($_POST['status'] ?? '', ['pending', 'confirmed', 'completed'])
            ? $_POST['status'] : 'confirmed';

        $backUrl = '/owner/bookings?date=' . urlencode($date) . '&branch_id=' . $branchId;

        if (!$courtId || !$branchId || !$date || $startHour < 5 || $endHour > 22 || $startHour >= $endHour) {
            Session::flash('error', 'Thông tin đặt sân không hợp lệ.');
            $this->redirect($backUrl);
        }

        $db    = Database::getInstance();
        $court = $db->query("SELECT * FROM courts WHERE id = ? LIMIT 1", [$courtId])->fetch(\PDO::FETCH_ASSOC);
        if (!$court || (int)$court['branch_id'] !== $branchId) {
            Session::flash('error', 'Sân không hợp lệ.');
            $this->redirect($backUrl);
        }

        if ($name === '') {
            $name = 'Khách lẻ';
        }

        $bookingModel = new Booking();
        if (!$bookingModel->isSlotAvailable($courtId, $date, $startHour, $endHour)) {
            Session::flash('error', 'Khung giờ này đã được đặt. Vui lòng chọn khung giờ khác.');
            $this->redirect($backUrl);
        }

        $pricingModel = new PricingRule();
        $totalPrice   = $pricingModel->getPrice($branchId, $date, $startHour, $endHour);

        $bookingModel->create([
            'booking_code'   => $bookingModel->generateBookingCode(),
            'user_id'        => null,
            'customer_name'  => $name,
            'customer_phone' => $phone,
            'customer_email' => '',
            'court_id'       => $courtId,
            'branch_id'      => $branchId,
            'booking_date'   => $date,
            'start_time'     => sprintf('%02d:00:00', $startHour),
            'end_time'       => sprintf('%02d:00:00', $endHour),
            'duration_hours' => $endHour - $startHour,
            'total_price'    => $totalPrice,
            'status'         => $status,
            'notes'          => $notes,
        ]);

        Session::flash('success', 'Đã tạo đặt sân cho khách tại sân.');
        $this->redirect($backUrl);
    }

    public function updateBooking()
    {
        $this->requireOwner();

        $id        = $_POST['id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        $allowed   = ['pending', 'confirmed', 'cancelled', 'completed'];

        if ($id && in_array($newStatus, $allowed)) {
            $db = Database::getInstance();
            $db->query("UPDATE bookings SET status = ? WHERE id = ?", [$newStatus, $id]);
            Session::flash('success', 'Đã cập nhật trạng thái đặt sân.');
        }
        $this->redirect('/owner/bookings');
    }

    // ─────────────────── PRICING ───────────────────

    public function pricing()
    {
        $this->requireOwner();

        $db       = Database::getInstance();
        $branches = $db->query("SELECT id, name FROM branches WHERE is_active = 1 ORDER BY name")->fetchAll(\PDO::FETCH_ASSOC);
        $rules    = $db->query(
            "SELECT pr.*, br.name AS branch_name
             FROM pricing_rules pr
             LEFT JOIN branches br ON pr.branch_id = br.id
             ORDER BY pr.branch_id ASC, pr.start_hour ASC"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('owner/pricing', [
            'title'    => 'Quản Lý Giá - SmashZone Admin',
            'rules'    => $rules,
            'branches' => $branches,
        ]);
    }

    private function normalizeTime($value, $default)
    {
        $value = trim((string)$value);
        return preg_match('/^\d{1,2}:\d{2}/', $value) ? substr($value, 0, 5) . ':00' : $default;
    }

    public function storePricing()
    {
        $this->requireOwner();

        $branchId  = !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null; // null = global
        $dayType   = in_array($_POST['day_type'] ?? '', ['weekday', 'weekend', 'all']) ? $_POST['day_type'] : 'all';
        $startHour = $this->normalizeTime($_POST['start_hour'] ?? '', '05:00:00');
        $endHour   = $this->normalizeTime($_POST['end_hour'] ?? '', '22:00:00');
        $price     = (int)($_POST['price_per_hour'] ?? 0);
        $label     = trim($_POST['label'] ?? '');

        if ($price <= 0) {
            Session::flash('error', 'Giá mỗi giờ không hợp lệ.');
            $this->redirect('/owner/pricing');
        }

        $db = Database::getInstance();
        $db->query(
            "INSERT INTO pricing_rules (branch_id, day_type, start_hour, end_hour, price_per_hour, label, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$branchId, $dayType, $startHour, $endHour, $price, $label]
        );

        Session::flash('success', 'Đã thêm quy tắc giá.');
        $this->redirect('/owner/pricing');
    }

    public function updatePricing()
    {
        $this->requireOwner();

        $id        = $_POST['id'] ?? null;
        $branchId  = !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null;
        $dayType   = in_array($_POST['day_type'] ?? '', ['weekday', 'weekend', 'all']) ? $_POST['day_type'] : 'all';
        $startHour = $this->normalizeTime($_POST['start_hour'] ?? '', '05:00:00');
        $endHour   = $this->normalizeTime($_POST['end_hour'] ?? '', '22:00:00');
        $price     = (int)($_POST['price_per_hour'] ?? 0);
        $label     = trim($_POST['label'] ?? '');

        if (!$id || $price <= 0) {
            Session::flash('error', 'Dữ liệu không hợp lệ.');
            $this->redirect('/owner/pricing');
        }

        $db = Database::getInstance();
        $db->query(
            "UPDATE pricing_rules SET branch_id=?, day_type=?, start_hour=?, end_hour=?, price_per_hour=?, label=? WHERE id=?",
            [$branchId, $dayType, $startHour, $endHour, $price, $label, $id]
        );

        Session::flash('success', 'Đã cập nhật quy tắc giá.');
        $this->redirect('/owner/pricing');
    }

    public function deletePricing()
    {
        $this->requireOwner();

        $id = $_POST['id'] ?? null;
        if ($id) {
            $db = Database::getInstance();
            $db->query("DELETE FROM pricing_rules WHERE id = ?", [$id]);
            Session::flash('success', 'Đã xóa quy tắc giá.');
        }
        $this->redirect('/owner/pricing');
    }

    // ─────────────────── REVENUE ───────────────────

    public function revenue()
    {
        $this->requireOwner();

        $db       = Database::getInstance();
        $period   = $_GET['period'] ?? 'month';
        $branchId = $_GET['branch_id'] ?? '';

        // Monthly revenue for current year
        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStr = date('Y') . '-' . sprintf('%02d', $m);
            $sql = "SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ? AND status IN ('confirmed','completed')";
            $params = [$monthStr];
            if ($branchId) {
                $sql .= " AND branch_id = ?";
                $params[] = $branchId;
            }
            $rev = $db->query($sql, $params)->fetchColumn();
            $monthlyRevenue[] = ['month' => $m, 'label' => 'Tháng ' . $m, 'revenue' => (int)$rev];
        }

        // Revenue by branch
        $branchRevenue = $db->query(
            "SELECT br.name, COALESCE(SUM(b.total_price),0) AS revenue
             FROM branches br
             LEFT JOIN bookings b ON b.branch_id = br.id AND b.status IN ('confirmed','completed')
             WHERE br.is_active = 1
             GROUP BY br.id, br.name
             ORDER BY revenue DESC"
        )->fetchAll(\PDO::FETCH_ASSOC);

        // Status breakdown
        $statusBreakdown = $db->query(
            "SELECT status, COUNT(*) AS cnt FROM bookings GROUP BY status"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $branches = $db->query("SELECT id, name FROM branches WHERE is_active = 1 ORDER BY name")->fetchAll(\PDO::FETCH_ASSOC);

        $currentMonth = (int)date('n');
        $monthRevenue = $monthlyRevenue[$currentMonth - 1]['revenue'] ?? 0;
        $yearRevenue  = array_sum(array_column($monthlyRevenue, 'revenue'));

        $sqlB    = "SELECT COUNT(*) FROM bookings WHERE DATE_FORMAT(booking_date,'%Y-%m') = ?";
        $paramsB = [date('Y-m')];
        if ($branchId) {
            $sqlB    .= " AND branch_id = ?";
            $paramsB[] = $branchId;
        }
        $monthBookings = (int)$db->query($sqlB, $paramsB)->fetchColumn();

        $this->render('owner/revenue', [
            'title'          => 'Doanh Thu - SmashZone Admin',
            'monthlyRevenue' => $monthlyRevenue,
            'branchRevenue'  => $branchRevenue,
            'statusBreakdown'=> $statusBreakdown,
            'branches'       => $branches,
            'branchId'       => $branchId,
            'monthRevenue'   => $monthRevenue,
            'yearRevenue'    => $yearRevenue,
            'monthBookings'  => $monthBookings,
        ]);
    }

    // ─────────────────── HELPERS ───────────────────

    private function requireOwner()
    {
        if (!Session::get('owner_id')) {
            Session::flash('error', 'Vui lòng đăng nhập để tiếp tục.');
            $this->redirect('/owner/login');
        }
    }

    private function lastInsertId(): int
    {
        return (int)Database::getInstance()->lastInsertId();
    }
}
