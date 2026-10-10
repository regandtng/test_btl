<?php ob_start(); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Lịch Sử Đặt Sân</h1>
            <p class="subtitle">Tra cứu và quản lý toàn bộ lượt đặt sân của bạn</p>
        </div>

        <?php if(!isset($_SESSION['user_id']) && !isset($_GET['phone'])): ?>
        <div class="card p-4 mb-4">
            <p class="font-weight-600 text-dark mb-2">Nhập số điện thoại để tra cứu lịch sử đặt sân:</p>
            <form class="search-form m-0" action="<?= BASE_URL ?>/history" method="GET">
                <input type="tel" name="phone" class="form-control" style="max-width:340px;" placeholder="Nhập số điện thoại..." required>
                <button type="submit" class="btn btn-accent">Tra Cứu</button>
            </form>
        </div>
        <?php endif; ?>

        <div class="card p-4">
            <?php if(empty($bookings)): ?>
                <div class="text-center py-5">
                    <div style="font-size:3rem; opacity:0.5;">📋</div>
                    <p class="text-muted mb-0">Không tìm thấy lịch sử đặt sân nào.</p>
                </div>
            <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Mã đặt sân</th>
                            <th>Chi nhánh</th>
                            <th>Sân</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $statusMap = [
                            'pending'   => ['badge-warning', 'Chờ xác nhận'],
                            'confirmed' => ['badge-success', 'Đã xác nhận'],
                            'cancelled' => ['badge-danger', 'Đã hủy'],
                        ];
                        foreach($bookings as $b):
                            $st = strtolower($b['status']);
                            $badge = $statusMap[$st] ?? ['badge-neutral', $b['status']];
                        ?>
                        <tr>
                            <td class="font-weight-700" style="font-family: ui-monospace, monospace;"><?= htmlspecialchars($b['code']) ?></td>
                            <td><?= htmlspecialchars($b['branch_name']) ?></td>
                            <td><?= htmlspecialchars($b['court_name']) ?></td>
                            <td><?= htmlspecialchars($b['booking_date']) ?></td>
                            <td><?= htmlspecialchars($b['start_hour'].':00 - '.$b['end_hour'].':00') ?></td>
                            <td class="text-accent font-weight-700"><?= number_format($b['total_price']) ?>đ</td>
                            <td><span class="badge <?= $badge[0] ?>"><?= $badge[1] ?></span></td>
                            <td><a href="<?= BASE_URL ?>/booking/detail/<?= $b['id'] ?>" class="btn btn-outline btn-sm">Xem chi tiết</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
