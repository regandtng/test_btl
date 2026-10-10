<?php ob_start(); ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container hero-inner py-6">
        <div class="row align-items-center">
            <div class="col-md-7">
                <span class="hero-eyebrow mb-3">🏸 Nền tảng đặt sân cầu lông hàng đầu Việt Nam</span>
                <h1 class="hero-title mb-3">
                    Đặt Sân Cầu Lông <span>Trực Tuyến</span><br>Nhanh Chóng & Tiện Lợi
                </h1>
                <p class="hero-subtitle mb-4">
                    Tra cứu lịch sân trống, lựa chọn khung giờ linh hoạt và đặt sân tức thì với SmashZone.
                    Trải nghiệm chuyên nghiệp, minh bạch và tiện lợi nhất.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= BASE_URL ?>/branches" class="btn btn-accent btn-lg font-weight-700">
                        Đặt Sân Ngay ➔
                    </a>
                    <a href="<?= BASE_URL ?>/branches" class="btn btn-lg font-weight-600" style="background: rgba(255,255,255,0.12); color:#fff !important; border:1.5px solid rgba(255,255,255,0.3);">
                        Xem Chi Nhánh
                    </a>
                </div>
                <div class="d-flex flex-wrap gap-4 mt-4" style="color:#cbd5e1; font-size:0.9rem; font-weight:600;">
                    <span>✓ Đặt sân 24/7</span>
                    <span>✓ Thanh toán QR / MoMo</span>
                    <span>✓ Giữ chỗ tức thì</span>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-block">
                <div class="glass-card p-4" style="background: rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); border-radius: 20px;">
                    <div style="font-size:0.8rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; color:#6ee7b7;">Hôm nay</div>
                    <div style="font-size:2.4rem; font-weight:900; color:#fff; line-height:1.1;" class="mt-1">130+</div>
                    <div style="color:#cbd5e1; font-weight:600;">sân đang sẵn sàng</div>
                    <div class="progress-bg mt-3" style="background: rgba(255,255,255,0.2);">
                        <div class="progress-bar" style="width: 78%;"></div>
                    </div>
                    <div style="color:#a7f3d0; font-size:0.85rem; margin-top:0.5rem;">78% khung giờ còn trống tuần này</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- High-Contrast Key Metrics Section -->
<div class="stats-section py-5 border-bottom" style="background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%);">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <div class="stat-value"><?= isset($stats['total_branches']) ? $stats['total_branches'] : 13 ?></div>
                    <div class="stat-label">Chi Nhánh Hoạt Động</div>
                    <p class="text-muted small m-0 mt-1">Phủ sóng rộng khắp tại các thành phố lớn</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card h-100">
                    <div class="stat-value"><?= isset($stats['total_courts']) ? $stats['total_courts'] : 130 ?>+</div>
                    <div class="stat-label">Sân Đạt Tiêu Chuẩn</div>
                    <p class="text-muted small m-0 mt-1">Thảm Yonex chống trượt & đèn LED chống chói</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-card h-100">
                    <div class="stat-value"><?= isset($stats['total_bookings']) && $stats['total_bookings'] > 0 ? number_format($stats['total_bookings'], 0, ',', '.') : '15.000' ?>+</div>
                    <div class="stat-label">Lượt Đặt Sân Thành Công</div>
                    <p class="text-muted small m-0 mt-1">Được tin tưởng bởi hàng nghìn vận động viên</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Section -->
<div class="how-it-works py-6 bg-white border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title font-weight-800" style="font-size: 2rem; color: #0f172a;">
                Quy Trình Đặt Sân 4 Bước
            </h2>
            <p class="text-muted" style="font-size: 1rem;">Đơn giản, minh bạch và hoàn tất chỉ trong 1 phút</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-hover p-4 h-100 border text-center">
                    <div class="step-num mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: linear-gradient(135deg,#ecfdf5,#d1fae5); color: #047857; font-weight: 800; font-size: 1.1rem; border-radius: 16px; border: 1px solid #a7f3d0;">
                        01
                    </div>
                    <h3 class="h5 font-weight-700 text-dark mb-2">Chọn Chi Nhánh</h3>
                    <p class="text-muted fs-sm m-0">Lựa chọn cơ sở gần nhất theo thành phố hoặc quận huyện.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-hover p-4 h-100 border text-center">
                    <div class="step-num mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: linear-gradient(135deg,#ecfdf5,#d1fae5); color: #047857; font-weight: 800; font-size: 1.1rem; border-radius: 16px; border: 1px solid #a7f3d0;">
                        02
                    </div>
                    <h3 class="h5 font-weight-700 text-dark mb-2">Chọn Sân & Giờ</h3>
                    <p class="text-muted fs-sm m-0">Kiểm tra khung giờ trống trên sơ đồ thực tế trực quan.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-hover p-4 h-100 border text-center">
                    <div class="step-num mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: linear-gradient(135deg,#ecfdf5,#d1fae5); color: #047857; font-weight: 800; font-size: 1.1rem; border-radius: 16px; border: 1px solid #a7f3d0;">
                        03
                    </div>
                    <h3 class="h5 font-weight-700 text-dark mb-2">Thanh Toán Linh Hoạt</h3>
                    <p class="text-muted fs-sm m-0">Hỗ trợ chuyển khoản QR Code, Ví MoMo hoặc Tiền mặt.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-hover p-4 h-100 border text-center">
                    <div class="step-num mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: linear-gradient(135deg,#ecfdf5,#d1fae5); color: #047857; font-weight: 800; font-size: 1.1rem; border-radius: 16px; border: 1px solid #a7f3d0;">
                        04
                    </div>
                    <h3 class="h5 font-weight-700 text-dark mb-2">Xác Nhận & Trải Nghiệm</h3>
                    <p class="text-muted fs-sm m-0">Nhận mã đặt sân ngay lập tức và bắt đầu trận đấu.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Branches -->
<div class="featured-branches py-6 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <h2 class="section-title font-weight-800 m-0" style="font-size: 1.85rem; color: #0f172a;">
                    Chi Nhánh Tiêu Biểu
                </h2>
                <p class="text-muted m-0 mt-1">Các sân cầu lông được đánh giá cao nhất hệ thống</p>
            </div>
            <a href="<?= BASE_URL ?>/branches" class="btn btn-outline-accent">Xem Tất Cả Chi Nhánh ➔</a>
        </div>

        <div class="row g-4">
            <?php 
            $featuredBranches = $featuredBranches ?? [
                ['id' => 1, 'name' => 'SmashZone Quận 1', 'city' => 'TP.HCM', 'address' => '123 Lê Lợi, Q.1', 'courts_count' => 10, 'open_hours' => '05:00 - 22:00'],
                ['id' => 2, 'name' => 'SmashZone Cầu Giấy', 'city' => 'Hà Nội', 'address' => '45 Xuân Thủy, Cầu Giấy', 'courts_count' => 10, 'open_hours' => '05:00 - 22:00'],
                ['id' => 3, 'name' => 'SmashZone Hải Châu', 'city' => 'Đà Nẵng', 'address' => '78 Nguyễn Văn Linh, Hải Châu', 'courts_count' => 10, 'open_hours' => '05:00 - 22:00']
            ];
            foreach ($featuredBranches as $branch): ?>
                <div class="col-md-4">
                    <div class="card card-hover h-100 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-neutral"><?= htmlspecialchars($branch['city']) ?></span>
                                <span class="badge badge-success"><?= htmlspecialchars($branch['courts_count']) ?> Sân hoạt động</span>
                            </div>
                            <h3 class="h5 font-weight-700 text-dark mb-2"><?= htmlspecialchars($branch['name']) ?></h3>
                            <p class="text-muted fs-sm mb-2">📍 <?= htmlspecialchars($branch['address']) ?></p>
                            <p class="text-muted fs-sm mb-4">⏰ Mở cửa: <?= htmlspecialchars($branch['open_hours'] ?? trim(($branch['open_time'] ?? '05:00:00') . ' - ' . ($branch['close_time'] ?? '22:00:00'))) ?></p>
                        </div>
                        <a href="<?= BASE_URL ?>/branch/<?= htmlspecialchars($branch['id']) ?>" class="btn btn-accent w-100 mt-auto py-2">
                            Chi Tiết & Đặt Sân
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
require ROOT . '/app/Views/layouts/main.php';
?>
