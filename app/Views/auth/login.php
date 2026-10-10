<?php ob_start(); ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Đăng Nhập <span>SmashZone</span></h1>
        <p class="auth-subtitle">Chào mừng trở lại! Đăng nhập để quản lý lịch đặt sân của bạn.</p>

        <?php if(isset($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/login" method="POST">
            <div class="form-group">
                <label class="form-label">Email / Tên đăng nhập</label>
                <input type="text" name="email" class="form-control" placeholder="email@example.com hoặc admin" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-accent w-100" style="padding:0.85rem; font-size:1rem;">Đăng Nhập</button>
        </form>

        <div class="auth-links">
            <a href="<?= BASE_URL ?>/register">Chưa có tài khoản? Đăng ký ngay</a>
            <a href="<?= BASE_URL ?>/" class="text-muted">Tiếp tục đặt sân không cần đăng nhập</a>
        </div>

        <div class="auth-links" style="margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1.25rem;">
            <a href="<?= BASE_URL ?>/owner/login">Đăng nhập quản trị viên</a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>
