<?php ob_start(); ?>
<?php $isCash = (($payment['method'] ?? '') === 'cash'); ?>

<div class="page-wrap">
    <div class="container" style="max-width:640px;">
        <div class="card p-5 text-center">
            <div class="success-badge">✓</div>
            <h1 class="h2 text-accent mb-2">GỬI YÊU CẦU THÀNH CÔNG!</h1>
            <p class="text-muted">
                <?php if ($isCash): ?>
                    Yêu cầu đặt sân đã được ghi nhận. Vui lòng thanh toán tiền mặt tại sân khi đến nhận sân.
                <?php else: ?>
                    Yêu cầu thanh toán đã được gửi. Cảm ơn bạn đã đặt sân tại SmashZone.
                <?php endif; ?>
            </p>

            <div class="ticket-code my-3">
                <?= htmlspecialchars($booking['booking_code'] ?? ($bookingCode ?? 'BB-20231010-98765')) ?>
            </div>

            <div class="details-grid">
                <div>
                    <small class="text-muted">Khách hàng</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['customer_name'] ?? ($customerName ?? 'Khách')) ?></div>
                </div>
                <div>
                    <small class="text-muted">Số điện thoại</small>
                    <div class="font-weight-700"><?= htmlspecialchars($booking['customer_phone'] ?? ($customerPhone ?? '—')) ?></div>
                </div>
                <div>
                    <small class="text-muted">Ngày đặt</small>
                    <div class="font-weight-700"><?= htmlspecialchars(isset($booking['booking_date']) ? date('d/m/Y', strtotime($booking['booking_date'])) : ($time ?? '—')) ?></div>
                </div>
                <div>
                    <small class="text-muted">Thời gian</small>
                    <div class="font-weight-700 text-blue"><?= htmlspecialchars(isset($booking['start_time']) ? substr($booking['start_time'],0,5) . ' - ' . substr($booking['end_time'],0,5) : ($time ?? '—')) ?></div>
                </div>
                <div style="grid-column: span 2; text-align: center; margin-top: 0.5rem;">
                    <small class="text-muted"><?= $isCash ? 'Số tiền cần thanh toán tại sân' : 'Tổng tiền yêu cầu' ?></small>
                    <div class="text-accent" style="font-weight:800; font-size:1.5rem;"><?= number_format($booking['total_price'] ?? ($price ?? 0)) ?>đ</div>
                </div>
            </div>

            <?php if ($isCash): ?>
            <div class="cash-notice">
                💵 Quý khách vui lòng thanh toán <strong><?= number_format($booking['total_price'] ?? ($price ?? 0)) ?>đ</strong> bằng tiền mặt tại quầy lễ tân khi đến nhận sân.
            </div>
            <?php endif; ?>

            <p class="text-muted" style="font-style: italic; margin-bottom: 1.5rem;">
                * Vui lòng đến trước 10 phút để nhận sân. Đọc mã đặt sân cho nhân viên tại quầy.
            </p>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="<?= BASE_URL ?>/history" class="btn btn-outline">Xem Lịch Sử</a>
                <a href="<?= BASE_URL ?>/" class="btn btn-accent">Về Trang Chủ</a>
            </div>
        </div>
    </div>
</div>

<style>
.success-badge {
  width: 84px; height: 84px; border-radius: 50%;
  background: var(--accent-light); color: var(--accent);
  border: 2px solid var(--accent-border);
  display: grid; place-items: center; margin: 0 auto 1.25rem;
  font-size: 2.5rem; animation: pop 0.5s ease-out;
}
@keyframes pop { 0% { transform: scale(0.5); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
.cash-notice { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; border-radius: var(--radius); padding: 0.85rem 1rem; font-size: 0.92rem; font-weight: 600; margin-bottom: 1.5rem; text-align: left; }
.details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left; margin: 1.5rem 0; background: #f8fafc; padding: 1.25rem; border-radius: var(--radius); border: 1px solid var(--border); }
</style>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
