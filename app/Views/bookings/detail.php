<?php ob_start(); ?>

<div class="page-wrap">
    <div class="container" style="max-width:760px;">
        <div class="mb-3">
            <a href="<?= BASE_URL ?>/history" class="btn btn-back">← Quay lại lịch sử đặt sân</a>
        </div>

        <div class="card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <h1 class="page-title m-0" style="font-size:1.6rem;">Chi Tiết Đặt Sân</h1>
                <?php
                $statusMap = [
                    'pending_payment' => ['badge-warning', 'Chờ thanh toán'],
                    'confirmed'       => ['badge-success', 'Đã xác nhận'],
                    'cancelled'       => ['badge-danger', 'Đã hủy'],
                ];
                $st = strtolower($booking['status'] ?? '');
                $badge = $statusMap[$st] ?? ['badge-neutral', $booking['status'] ?? ''];
                ?>
                <span class="badge <?= $badge[0] ?>"><?= $badge[1] ?></span>
            </div>

            <div class="ticket-info text-center mb-4 p-4 rounded" style="background: var(--accent-light); border: 1px dashed var(--accent-border); border-radius: var(--radius);">
                <div class="text-muted small font-weight-600" style="text-transform:uppercase; letter-spacing:0.08em;">Mã đặt sân</div>
                <div class="font-weight-800 text-accent" style="font-size:1.7rem; letter-spacing:0.06em; font-family: ui-monospace, monospace;"><?= htmlspecialchars($booking['code'] ?? $booking['booking_code'] ?? '—') ?></div>
            </div>

            <div class="details-grid">
                <div>
                    <small class="text-muted">Chi nhánh</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['branch_name'] ?? '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Sân</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['court_name'] ?? '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Ngày đặt</small>
                    <div class="font-weight-700"><?= htmlspecialchars(isset($booking['booking_date']) ? date('d/m/Y', strtotime($booking['booking_date'])) : '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Khung giờ</small>
                    <div class="font-weight-700 text-blue"><?= sprintf('%02d:00 - %02d:00', $booking['start_hour'] ?? 0, $booking['end_hour'] ?? 0) ?></div>
                </div>
                <div>
                    <small class="text-muted">Thời lượng</small>
                    <div class="font-weight-700"><?= ($booking['duration_hours'] ?? 0) ?> giờ</div>
                </div>
                <div>
                    <small class="text-muted">Người đặt</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['customer_name'] ?? '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Số điện thoại</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['customer_phone'] ?? '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Email</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['customer_email'] ?? '—') ?></div>
                </div>
                <div>
                    <small class="text-muted">Phương thức thanh toán</small>
                    <div class="font-weight-700">
                        <?php
                        $methodMap = ['qr' => 'Chuyển khoản QR', 'momo' => 'Ví MoMo', 'cash' => 'Tiền mặt tại sân'];
                        echo htmlspecialchars($methodMap[$payment['method'] ?? ''] ?? '—');
                        ?>
                        <?php if (!empty($payment['status'])): ?>
                            <span class="badge <?= ($payment['status'] === 'paid') ? 'badge-success' : 'badge-warning' ?>">
                                <?= ($payment['status'] === 'paid') ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="grid-column: span 2;">
                    <small class="text-muted">Ghi chú</small>
                    <div class="font-weight-500"><?= htmlspecialchars($booking['notes'] ?? '—') ?></div>
                </div>
            </div>

            <div class="summary-total border-top mt-3 pt-3">
                <span>Tổng tiền</span>
                <span><?= number_format($booking['total_price'] ?? 0) ?>đ</span>
            </div>

            <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                <?php if (strtolower($booking['status'] ?? '') === 'pending'): ?>
                    <a href="<?= BASE_URL ?>/payment/<?= (int) $booking['id'] ?>" class="btn btn-accent">Thanh Toán Ngay</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/booking/<?= (int) $booking['court_id'] ?>" class="btn btn-outline">Đặt Thêm Giờ</a>
                <a href="<?= BASE_URL ?>/history" class="btn btn-back">Xem Lịch Sử</a>
            </div>
        </div>
    </div>
</div>

<style>
.details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.5rem; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem; }
@media (max-width: 640px) { .details-grid { grid-template-columns: 1fr; } }
</style>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>