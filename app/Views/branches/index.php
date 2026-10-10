<?php ob_start(); ?>

<div class="page-header py-5 bg-white border-bottom shadow-sm">
    <div class="container text-center">
        <h1 class="page-title mb-2" style="font-size: 2.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
            Hệ Thống Chi Nhánh Sân Cầu Lông
        </h1>
        <p class="text-muted mb-3 max-w-600 mx-auto" style="font-size: 1.05rem;">
            Khám phá danh sách các cơ sở SmashZone tiêu chuẩn trên toàn quốc. Đặt sân trực tuyến giữ chỗ ngay.
        </p>
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-light rounded border text-accent" style="font-weight: 600; font-size: 0.9rem;">
            <span>Tổng cộng <strong><?= count($branches ?? [1,2,3]) ?></strong> chi nhánh đang hoạt động</span>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Filter Bar -->
    <div class="card p-4 mb-5" style="background: #ffffff; border: 1.5px solid #cbd5e1;">
        <form action="<?= BASE_URL ?>/branches" method="GET" class="row g-3 align-items-center">
            <div class="col-md-7">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên chi nhánh, địa chỉ..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="city" class="form-select">
                    <option value="">Tất cả khu vực</option>
                    <?php 
                    $cities = $cities ?? ['Hà Nội', 'TP.HCM', 'Đà Nẵng', 'Cần Thơ'];
                    foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city) ?>" <?= isset($_GET['city']) && $_GET['city'] === $city ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-accent w-100 py-2.5 font-weight-700">Tìm Kiếm</button>
            </div>
        </form>
    </div>

    <!-- Branch Grid -->
    <?php 
    $branches = $branches ?? [
        ['id' => 1, 'name' => 'SmashZone Quận 1', 'city' => 'TP.HCM', 'address' => '123 Lê Lợi, Phường Bến Nghé, Q.1', 'phone' => '0901234567', 'open_hours' => '05:00 - 22:00', 'courts_count' => 10],
        ['id' => 2, 'name' => 'SmashZone Cầu Giấy', 'city' => 'Hà Nội', 'address' => '45 Xuân Thủy, Dịch Vọng, Cầu Giấy', 'phone' => '0901234568', 'open_hours' => '05:00 - 22:00', 'courts_count' => 10],
        ['id' => 3, 'name' => 'SmashZone Hải Châu', 'city' => 'Đà Nẵng', 'address' => '78 Nguyễn Văn Linh, Hải Châu', 'phone' => '0901234569', 'open_hours' => '05:00 - 22:00', 'courts_count' => 10]
    ];
    ?>

    <?php if (empty($branches)): ?>
        <div class="card text-center py-5 p-4 border-dashed">
            <h3 class="text-dark mb-2">Không tìm thấy chi nhánh phù hợp</h3>
            <p class="text-muted max-w-500 mx-auto mb-4">Chúng tôi không tìm thấy chi nhánh nào theo bộ lọc của bạn. Vui lòng thử từ khóa khác.</p>
            <div>
                <a href="<?= BASE_URL ?>/branches" class="btn btn-outline-accent">Xóa Bộ Lọc</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($branches as $branch): ?>
                <div class="col-md-4">
                    <div class="card h-100 p-4 d-flex flex-column justify-content-between" style="border: 1.5px solid #cbd5e1;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-neutral"><?= htmlspecialchars($branch['city']) ?></span>
                                <span class="badge badge-success"><?= htmlspecialchars($branch['courts_count']) ?> Sân</span>
                            </div>
                            
                            <h3 class="h5 font-weight-700 text-dark mb-3"><?= htmlspecialchars($branch['name']) ?></h3>
                            
                            <div class="text-muted fs-sm mb-4">
                                <div class="mb-1.5"><strong>Địa chỉ:</strong> <?= htmlspecialchars($branch['address']) ?></div>
                                <div class="mb-1.5"><strong>Hotline:</strong> <span class="text-dark font-weight-600"><?= htmlspecialchars($branch['phone']) ?></span></div>
                                <div><strong>Giờ mở cửa:</strong> <?= htmlspecialchars($branch['open_hours'] ?? trim(($branch['open_time'] ?? '05:00:00') . ' - ' . ($branch['close_time'] ?? '22:00:00'))) ?></div>
                            </div>
                        </div>

                        <a href="<?= BASE_URL ?>/branch/<?= htmlspecialchars($branch['id']) ?>" class="btn btn-accent w-100 mt-auto py-2.5 font-weight-700">
                            Xem Chi Tiết & Đặt Sân
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.row { display: flex; flex-wrap: wrap; margin-left: -0.75rem; margin-right: -0.75rem; }
.g-3 { --bs-gutter-y: 1rem; --bs-gutter-x: 1rem; }
.g-4 { --bs-gutter-y: 1.5rem; --bs-gutter-x: 1.5rem; }
.g-3 > *, .g-4 > * { padding-left: calc(var(--bs-gutter-x) * 0.5); padding-right: calc(var(--bs-gutter-x) * 0.5); }
.g-4 > * { margin-top: var(--bs-gutter-y); }
.col-md-7 { width: 58.333333%; }
.col-md-3 { width: 25%; }
.col-md-2 { width: 16.666667%; }
.col-md-4 { width: 33.333333%; }
@media (max-width: 768px) {
    .col-md-7, .col-md-3, .col-md-2, .col-md-4 { width: 100%; }
}
.fs-sm { font-size: 0.9rem; }
.mb-1\.5 { margin-bottom: 0.375rem; }
</style>

<?php 
$content = ob_get_clean();
require ROOT . '/app/Views/layouts/main.php';
?>
