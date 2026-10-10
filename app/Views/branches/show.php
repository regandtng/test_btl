<?php ob_start(); ?>

<?php
// Mockup data
$branch = $branch ?? [
    'id' => 1,
    'name' => 'SmashZone Quận 1',
    'city' => 'TP.HCM',
    'address' => '123 Lê Lợi, Phường Bến Nghé, Quận 1',
    'phone' => '0901234567',
    'open_hours' => '05:00 - 22:00',
    'description' => 'Cơ sở hiện đại bậc nhất ngay trung tâm thành phố với 10 sân đạt tiêu chuẩn thi đấu quốc tế. Hệ thống thảm Yonex chống trượt, đèn LED chống chói tiêu chuẩn Olympic.'
];

$courts = $courts ?? [];
if (empty($courts)) {
    for ($i = 1; $i <= 10; $i++) {
        $courts[] = [
            'id' => $i,
            'name' => 'Sân A' . $i,
            'status' => 'active',
            'type' => $i <= 2 ? 'VIP' : 'Standard'
        ];
    }
}
$selectedDate = $_GET['date'] ?? date('Y-m-d');
?>

<div class="branch-hero py-4 bg-white border-bottom shadow-sm">
    <div class="container">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="<?= BASE_URL ?>/branches" class="btn btn-back">
                <span>← Quay lại danh sách chi nhánh</span>
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-4 mb-3">
            <div>
                <span class="badge badge-neutral mb-2" style="font-size: 0.85rem;">
                    Khu vực: <?= htmlspecialchars($branch['city']) ?>
                </span>
                <h1 class="page-title m-0 text-dark" style="font-size: 2.25rem; font-weight: 800; color: #0f172a;">
                    <?= htmlspecialchars($branch['name']) ?>
                </h1>
                <p class="text-muted mt-2 mb-0" style="font-size: 1.05rem;">
                    <strong>Địa chỉ:</strong> <?= htmlspecialchars($branch['address']) ?>
                </p>
            </div>
            
            <div class="branch-quick-info bg-light p-3 border rounded-3" style="min-width: 280px; border-color: #cbd5e1 !important;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="text-muted">Giờ mở cửa:</span>
                    <strong class="text-dark"><?= htmlspecialchars($branch['open_hours'] ?? trim(($branch['open_time'] ?? '05:00:00') . ' - ' . ($branch['close_time'] ?? '22:00:00'))) ?></strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted">Hotline tư vấn:</span>
                    <strong class="text-accent font-weight-bold" style="font-size: 1.1rem;"><?= htmlspecialchars($branch['phone']) ?></strong>
                </div>
            </div>
        </div>

        <p class="text-muted" style="font-size: 1rem; line-height: 1.6; border-left: 4px solid var(--accent-green); padding-left: 1rem; margin-top: 1rem;">
            <?= htmlspecialchars($branch['description']) ?>
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="layout-grid">
        <!-- Main Content: Courts -->
        <div class="main-column">
            <div class="card p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3" style="border-radius: 12px; background: #ffffff; border: 1.5px solid #cbd5e1;">
                <h2 class="m-0 font-weight-800" style="font-size: 1.4rem; color: #0f172a;">
                    Sơ Đồ & Danh Sách Sân Đặt
                </h2>
                
                <!-- Date Selector -->
                <form class="date-selector d-flex align-items-center gap-3">
                    <label for="date-select" class="text-dark m-0 whitespace-nowrap font-weight-600">Ngày đặt sân:</label>
                    <input type="date" id="date-select" name="date" class="form-control px-3 py-2" value="<?= htmlspecialchars($selectedDate) ?>" min="<?= date('Y-m-d') ?>" onchange="this.form.submit()" style="border: 1.5px solid #cbd5e1; border-radius: 8px; font-weight: 600; color: #0f172a; cursor: pointer; max-width: 180px;">
                </form>
            </div>

            <!-- Court Legend -->
            <div class="d-flex gap-4 mb-4 text-sm px-1">
                <div class="d-flex align-items-center gap-2"><span style="width: 14px; height: 14px; background: #dcfce7; border: 1.5px solid #86efac; border-radius: 4px;"></span> <span class="text-dark font-weight-600">Hoạt động (Sẵn sàng)</span></div>
                <div class="d-flex align-items-center gap-2"><span style="width: 14px; height: 14px; background: #fef3c7; border: 1.5px solid #fde68a; border-radius: 4px;"></span> <span class="text-dark font-weight-600">Sân VIP</span></div>
                <div class="d-flex align-items-center gap-2"><span style="width: 14px; height: 14px; background: #fee2e2; border: 1.5px solid #fca5a5; border-radius: 4px;"></span> <span class="text-muted font-weight-500">Đang bảo trì</span></div>
            </div>

            <div class="courts-grid">
                <?php foreach ($courts as $court): ?>
                    <?php 
                    $st = strtolower($court['status'] ?? '');
                    $isActive = ($st === 'active' || $st === 'hoạt động' || $st === 'sẵn sàng' || $st === '' || empty($st));
                    $isVIP = strtoupper($court['type'] ?? '') === 'VIP';
                    
                    $cardBorder = !$isActive ? '#fca5a5' : ($isVIP ? '#fcd34d' : '#cbd5e1');
                    $cardBg = !$isActive ? '#fff5f5' : '#ffffff';
                    ?>
                    <div class="court-card card d-flex flex-column" style="border: 1.5px solid <?= $cardBorder ?>; background: <?= $cardBg ?>; border-radius: 12px; overflow: hidden;">
                        <div class="court-header p-3 d-flex justify-content-between align-items-center" style="background: #f8fafc; border-bottom: 1px solid #cbd5e1;">
                            <h3 class="court-name m-0" style="font-size: 1.15rem; font-weight: 700; color: #0f172a;"><?= htmlspecialchars($court['name']) ?></h3>
                            <div class="badges d-flex gap-2">
                                <?php if ($isVIP): ?>
                                    <span class="badge badge-warning">VIP</span>
                                <?php endif; ?>
                                <?php if ($isActive): ?>
                                    <span class="badge badge-success">Sẵn sàng</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Tạm bảo trì</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="court-body p-4 text-center flex-grow-1 d-flex flex-column justify-content-center">
                            <div class="badge badge-neutral mb-3 mx-auto" style="font-size: 0.85rem;">
                                <?= $isVIP ? 'Thảm Yonex cao cấp' : 'Thảm tiêu chuẩn thi đấu' ?>
                            </div>

                            <?php if ($isActive): ?>
                                <a href="<?= BASE_URL ?>/booking/<?= htmlspecialchars($court['id']) ?>?date=<?= urlencode($selectedDate) ?>" class="btn btn-accent w-100 mt-auto py-2.5 font-weight-700">
                                    Đặt Sân Ngay
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 mt-auto py-2.5" style="cursor: not-allowed; opacity: 0.6;" disabled>Tạm Bảo Trì</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sidebar: Pricing Info -->
        <div class="sidebar-column">
            <div class="card p-0 sticky-top overflow-hidden" style="top: 90px; border-radius: 12px; background: #ffffff; border: 1.5px solid #cbd5e1;">
                <div class="p-4" style="background: #f8fafc; border-bottom: 1px solid #cbd5e1;">
                    <h3 class="m-0 font-weight-800" style="font-size: 1.2rem; color: #0f172a;">
                        Bảng Giá Thuê Sân
                    </h3>
                </div>
                
                <div class="p-4">
                    <div class="pricing-tier mb-4 pb-3 border-bottom">
                        <div class="tier-header d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-dark" style="font-size: 1.05rem;">Giờ Thấp Điểm</strong>
                            <span class="price font-weight-800 text-accent" style="font-size: 1.3rem; color: #047857 !important;">80.000đ<small class="text-muted" style="font-size: 0.8rem;">/h</small></span>
                        </div>
                        <ul class="text-muted small m-0 pl-3">
                            <li>• Khung giờ sáng: 05:00 - 08:00</li>
                            <li>• Khung giờ chiều: 14:00 - 17:00</li>
                        </ul>
                    </div>

                    <div class="pricing-tier mb-4 pb-3 border-bottom">
                        <div class="tier-header d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-dark" style="font-size: 1.05rem;">Giờ Cao Điểm</strong>
                            <span class="price font-weight-800 text-accent" style="font-size: 1.3rem; color: #047857 !important;">150.000đ<small class="text-muted" style="font-size: 0.8rem;">/h</small></span>
                        </div>
                        <ul class="text-muted small m-0 pl-3">
                            <li>• Khung giờ ban ngày: 08:00 - 14:00</li>
                            <li>• Khung giờ buổi tối: 17:00 - 22:00</li>
                        </ul>
                    </div>

                    <div class="weekend-surcharge p-3 text-center rounded border" style="background: #fef3c7; border-color: #fde68a !important;">
                        <strong style="color: #92400e;" class="d-block mb-1">Phụ thu Cuối tuần & Lễ</strong>
                        <span class="font-weight-800 text-dark" style="font-size: 1.1rem;">+20%</span> <span class="text-muted small">trên giá thuê cơ bản</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.layout-grid { display: grid; grid-template-columns: 2.5fr 1fr; gap: 2rem; }
.courts-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
@media (max-width: 992px) {
    .layout-grid { grid-template-columns: 1fr; }
    .courts-grid { grid-template-columns: 1fr; }
}
</style>

<?php 
$content = ob_get_clean();
require ROOT . '/app/Views/layouts/main.php';
?>
