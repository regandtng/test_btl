<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - SmashZone' : 'SmashZone - Hệ Thống Đặt Sân Cầu Lông' ?></title>
    <meta name="description" content="SmashZone - Nền tảng đặt sân cầu lông trực tuyến hàng đầu.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="theme-light d-flex flex-column min-vh-100">
    <!-- SVG Icons -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="i-user" viewBox="0 0 24 24">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </symbol>
            <symbol id="i-lock" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </symbol>
            <symbol id="i-calendar" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </symbol>
            <symbol id="i-logout" viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </symbol>
        </defs>
    </svg>
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
                    <div class="user-menu d-flex align-items-center gap-3" id="user-menu">
                        <div class="user-dropdown">
                            <button class="user-toggle btn btn-outline-accent d-flex align-items-center gap-2" onclick="toggleUserDropdown()">
                                <span class="user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'K', 0, 1)) ?></span>
                                <span class="user-greeting text-dark"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Khách') ?></span>
                                <span class="dropdown-arrow">▼</span>
                            </button>
                            <div class="dropdown-menu" id="user-dropdown-menu">
                                <a href="<?= BASE_URL ?>/profile" class="dropdown-item">
                                    <svg class="ico"><use href="#i-user"/></svg>
                                    Hồ sơ cá nhân
                                </a>
                                <a href="<?= BASE_URL ?>/history" class="dropdown-item">
                                    <svg class="ico"><use href="#i-calendar"/></svg>
                                    Lịch sử đặt sân
                                </a>
                                <hr class="dropdown-divider">
                                <a href="<?= BASE_URL ?>/logout" class="dropdown-item text-danger">
                                    <svg class="ico"><use href="#i-logout"/></svg>
                                    Đăng xuất
                                </a>
                            </div>
                        </div>
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
                        <li style="margin-bottom: 0.5rem;">Trụ sở: Số 4B, ngách 71, ngõ 83 Tân triều, Thanh Xuân, Hà Nội</li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-4 pt-3" style="border-top: 1px solid #1e293b; color: #64748b; font-size: 0.85rem;">
                <p>&copy; <?= date('Y') ?> SmashZone Badminton Management. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- User Dropdown Styles & Script -->
    <style>
        .user-dropdown { position: relative; }
        .user-toggle { background: none; border: 1px solid rgba(255,255,255,0.15); padding: 8px 12px; border-radius: 10px; cursor: pointer; transition: all 0.2s; }
        .user-toggle:hover { background: rgba(255,255,255,0.05); }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #00e676, #00b35f); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #000; font-size: 0.9rem; }
        .user-greeting { font-size: 0.9rem; font-weight: 500; }
        .dropdown-arrow { font-size: 0.7rem; color: #888; transition: transform 0.2s; }
        .user-toggle[aria-expanded="true"] .dropdown-arrow { transform: rotate(180deg); }
        .dropdown-menu { position: absolute; top: calc(100% + 8px); right: 0; min-width: 200px; background: rgba(15, 20, 35, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 8px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all 0.2s; z-index: 100; }
        .dropdown-menu.show { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #e2e8f0; font-size: 0.9rem; transition: all 0.15s; text-decoration: none; }
        .dropdown-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .dropdown-item .ico { width: 18px; height: 18px; flex: none; color: #94a3b8; }
        .dropdown-item:hover .ico { color: #00e676; }
        .dropdown-item.text-danger { color: #ff6b6b; }
        .dropdown-item.text-danger .ico { color: #ff6b6b; }
        .dropdown-item.text-danger:hover { background: rgba(255, 107, 107, 0.1); }
        .dropdown-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 6px 0; }
    </style>

    <script>
        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            const toggle = document.querySelector('.user-toggle');
            const isOpen = menu.classList.toggle('show');
            toggle.setAttribute('aria-expanded', isOpen);
        }
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('user-dropdown-menu');
            const toggle = document.querySelector('.user-toggle');
            if (menu && !menu.contains(e.target) && toggle && !toggle.contains(e.target)) {
                menu.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    </script>

    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
