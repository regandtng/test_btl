<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - SmashZone' : 'SmashZone - Hệ Thống Đặt Sân Cầu Lông' ?></title>
    <meta name="description" content="SmashZone - Nền tảng đặt sân cầu lông trực tuyến hàng đầu Việt Nam.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="theme-light d-flex flex-column min-vh-100">
    <!-- Header -->
    <header class="glass-header sticky-top">
        <div class="container nav-container">
            <a href="<?= BASE_URL ?>/" class="logo">
                <span class="logo-mark">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2c-1.7 0-3 1.3-3 3v5.2L5.4 16c-.5.8-.6 1.8-.2 2.6.4.9 1.3 1.4 2.3 1.4h9c1 0 1.9-.5 2.3-1.4.4-.8.3-1.8-.2-2.6L15 10.2V5c0-1.7-1.3-3-3-3z" fill="#fff" fill-opacity="0.95"/>
                        <path d="M9.6 5h4.8M9.6 7.4h4.8M10 9.8h4" stroke="#059669" stroke-width="1" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="logo-text">
                    <span class="wordmark">SMASH<span>ZONE</span></span>
                    <span class="logo-tag">Badminton</span>
                </span>
            </a>
            
            <nav class="nav-links" id="main-nav">
                <a href="<?= BASE_URL ?>/" class="nav-link">Trang Chủ</a>
                <a href="<?= BASE_URL ?>/branches" class="nav-link">Chi Nhánh Sân</a>
                <a href="<?= BASE_URL ?>/history" class="nav-link">Lịch Sử Đặt Sân</a>
            </nav>

            <div class="nav-actions">
                <button class="mobile-toggle" aria-label="Menu" onclick="document.getElementById('main-nav').classList.toggle('active')">☰</button>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu d-flex align-items-center gap-3">
                        <span class="user-greeting text-muted">Xin chào, <strong class="text-dark"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Khách') ?></strong></span>
                        <a href="<?= BASE_URL ?>/logout" class="btn btn-outline-accent btn-sm">Đăng Xuất</a>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="btn btn-outline-accent">Đăng Nhập</a>
                    <a href="<?= BASE_URL ?>/register" class="btn btn-accent">Đăng Ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
        <div class="flash-message flash-success">
            <span><?= htmlspecialchars($flashSuccess) ?></span>
            <button class="flash-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <?php if ($flashError = \Core\Session::getFlash('error')): ?>
        <div class="flash-message flash-error">
            <span><?= $flashError ?></span>
            <button class="flash-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="flex-grow-1" style="min-height: 80vh;">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="glass-footer">
        <div class="container">
            <div class="footer-grid d-flex justify-content-between flex-wrap gap-4">
                <div style="max-width: 400px;">
                    <div class="logo mb-3" style="color: #ffffff; font-size: 1.25rem;">
                        <span>SMASH<span style="color: #34d399;">ZONE</span></span>
                    </div>
                    <p style="color: #94a3b8; font-size: 0.9rem; line-height: 1.6;">Hệ thống quản lý và đặt sân cầu lông chuyên nghiệp. Đảm bảo minh bạch, tiện lợi và hỗ trợ nhanh chóng 24/7.</p>
                </div>
                <div class="footer-links">
                    <h4 class="footer-heading">Liên Kết Nhanh</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>/">Trang Chủ</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>/branches">Danh Sách Chi Nhánh</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>/history">Lịch Sử Đặt Sân</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="<?= BASE_URL ?>/owner/login">Dành Cho Chủ Sân</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4 class="footer-heading">Hỗ Trợ Khách Hàng</h4>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: #cbd5e1;">
                        <li style="margin-bottom: 0.5rem;">Hotline: 0976779516</li>
                        <li style="margin-bottom: 0.5rem;">Email: support@smashzone.vn</li>
                        <li style="margin-bottom: 0.5rem;">Trụ sở: Số 4B, ngách 71, ngõ 83 Tân triều, Hà Nội</li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-4 pt-3" style="border-top: 1px solid #1e293b; color: #64748b; font-size: 0.85rem;">
                <p>&copy; <?= date('Y') ?> SmashZone Badminton Management. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
