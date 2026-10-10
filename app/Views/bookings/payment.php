<?php ob_start(); ?>

<div class="page-wrap">
    <div class="container" style="max-width:1000px;">
        <div class="step-indicator">
            <span class="step done">1. Chọn sân ✓</span>
            <span class="step done">2. Thông tin ✓</span>
            <span class="step active">3. Thanh toán</span>
            <span class="step">4. Hoàn tất</span>
        </div>

        <div class="checkout-grid">
            <!-- Left: Summary -->
            <div class="card p-4">
                <h3 class="h5 text-accent mb-3">Thông tin đặt sân</h3>
                <ul class="summary-list">
                    <li><span class="text-muted">Mã đặt sân</span> <strong><?= htmlspecialchars($booking['booking_code'] ?? ('#TMP-' . rand(1000,9999))) ?></strong></li>
                    <li><span class="text-muted">Khách hàng</span> <strong><?= htmlspecialchars($booking['customer_name'] ?? '') ?></strong></li>
                    <li><span class="text-muted">SĐT</span> <strong><?= htmlspecialchars($booking['customer_phone'] ?? '') ?></strong></li>
                </ul>
                <div class="summary-total">
                    <span>Tổng thanh toán</span>
                    <span><?= number_format($booking['total_price'] ?? 0) ?>đ</span>
                </div>
            </div>

            <!-- Right: Payment Methods -->
            <div class="card p-4">
                <h3 class="h5 mb-3">Phương thức thanh toán</h3>
                <form action="<?= BASE_URL ?>/payment/process" method="POST">
                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['id'] ?? '') ?>">
                    <label>
                        <input type="radio" name="payment_method" value="qr" style="display:none;" required>
                        <div class="payment-card">
                            <span class="pay-icon pay-icon-bank">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#fff" d="M12 2.4 2.4 8.6h19.2L12 2.4z"/>
                                    <rect x="5" y="11" width="3.2" height="7" rx="0.6" fill="#fff"/>
                                    <rect x="10.4" y="11" width="3.2" height="7" rx="0.6" fill="#fff"/>
                                    <rect x="15.8" y="11" width="3.2" height="7" rx="0.6" fill="#fff"/>
                                    <path fill="#fff" d="M3 20.6h18v-2H3v2z"/>
                                </svg>
                            </span>
                            <div class="pay-label">
                                <div class="pay-title">Chuyển khoản QR Code</div>
                                <div class="pay-desc">Quét mã QR qua app ngân hàng</div>
                            </div>
                        </div>
                        <div class="payment-details">
                            <p>Quét mã QR để thanh toán. Giao dịch sẽ tự động xác nhận.</p>
                            <div style="width: 200px; height: 200px; background: #fff; margin: 1rem auto 0; display:flex; align-items:center; justify-content:center; color:#000; border:1px solid var(--border); border-radius:8px;">
                                [MÃ QR SVG/IMG]
                            </div>
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="payment_method" value="momo" style="display:none;">
                        <div class="payment-card">
                            <span class="pay-icon pay-icon-momo">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#fff" d="M6.5 18V8.2L12 14.1l5.5-5.9V18h-2.2v-3.9L12 18H8.7V18H6.5z"/>
                                </svg>
                            </span>
                            <div class="pay-label">
                                <div class="pay-title">Ví điện tử MoMo</div>
                                <div class="pay-desc">Chuyển khoản đến số 0901234567</div>
                            </div>
                        </div>
                        <div class="payment-details">
                            <p class="m-0">Chuyển tiền đến số MoMo: <strong>0901234567</strong></p>
                            <p class="m-0">Nội dung: <strong>Thanh toan san BB123</strong></p>
                        </div>
                    </label>

                    <label>
                        <input type="radio" name="payment_method" value="cash" style="display:none;">
                        <div class="payment-card">
                            <span class="pay-icon pay-icon-cash">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#fff" d="M2.5 8A1.5 1.5 0 0 1 4 6.5h16A1.5 1.5 0 0 1 21.5 8v8a1.5 1.5 0 0 1-1.5 1.5H4A1.5 1.5 0 0 1 2.5 16V8z"/>
                                    <circle cx="12" cy="12" r="3" fill="#0f172a" opacity="0.15"/>
                                    <circle cx="12" cy="12" r="3" fill="none" stroke="#0f172a" stroke-width="1.5" opacity="0.7"/>
                                    <circle cx="16" cy="9.5" r="1.2" fill="#0f172a" opacity="0.7"/>
                                    <circle cx="8" cy="14.5" r="1.2" fill="#0f172a" opacity="0.7"/>
                                </svg>
                            </span>
                            <div class="pay-label">
                                <div class="pay-title">Tiền mặt tại sân</div>
                                <div class="pay-desc">Thanh toán khi đến nhận sân</div>
                            </div>
                        </div>
                        <div class="payment-details">
                            <p class="m-0 text-accent font-weight-600">Bạn có thể thanh toán trực tiếp tại quầy khi đến sân.</p>
                            <p class="m-0 mt-1">Vui lòng đến trước 10 phút để nhận sân.</p>
                        </div>
                    </label>

                    <button type="submit" class="btn btn-accent w-100 mt-4" style="padding:0.9rem; font-size:1.05rem;">Hoàn Tất Đặt Sân</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.payment-card { background: #fff; border: 2px solid var(--border-strong); border-radius: var(--radius); padding: 0.9rem 1rem; margin-bottom: 0.5rem; cursor: pointer; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; }
.payment-card:hover { border-color: var(--accent-border); background: var(--accent-light); }
.payment-card.selected { border-color: var(--accent); background: var(--accent-light); box-shadow: 0 0 0 3px var(--accent-glow); }
.payment-details { display: none; margin: 0.5rem 0 1rem; padding: 1rem; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-secondary); }
input[name="payment_method"]:checked + .payment-card + .payment-details { display: block; }
.pay-icon { width: 46px; height: 46px; border-radius: 13px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm); }
.pay-icon svg { width: 27px; height: 27px; display: block; }
.pay-icon-bank { background: linear-gradient(135deg, #1e40af, #3b82f6); }
.pay-icon-momo { background: linear-gradient(135deg, #d4145a, #a50064); }
.pay-icon-cash { background: linear-gradient(135deg, #047857, #10b981); }
.pay-label { display: flex; flex-direction: column; gap: 2px; }
.pay-title { font-size: 15.5px; font-weight: 700; color: var(--text-primary); }
.pay-desc { font-size: 13px; color: var(--text-muted); }
</style>

<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-card').forEach(card => card.classList.remove('selected'));
        this.nextElementSibling.classList.add('selected');
    });
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
