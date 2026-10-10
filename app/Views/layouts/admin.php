<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - Admin' : 'SmashZone Admin' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg-dark: #0a0e1a;
            --bg-darker: #050812;
            --accent-green: #00e676;
            --accent-blue: #2979ff;
            --text-main: #ffffff;
            --text-muted: #a0aec0;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.05);
            --sidebar-width: 260px;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-dark); color: var(--text-main); margin: 0; padding: 0; display: flex; height: 100vh; overflow: hidden; }
        a { text-decoration: none; color: inherit; }
        
        /* Glass styling */
        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); }
        
        /* Sidebar */
        .admin-sidebar { width: var(--sidebar-width); height: 100vh; display: flex; flex-direction: column; border-right: 1px solid var(--glass-border); }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--glass-border); }
        .logo { font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; color: #fff; }
        .logo-text { letter-spacing: -0.5px; }
        .text-accent { color: var(--accent-green); }
        
        .sidebar-nav { flex: 1; padding: 1.5rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; overflow-y: auto; }
        .nav-item { padding: 0.8rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.75rem; font-weight: 500; color: var(--text-muted); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-item.active { background: rgba(41, 121, 255, 0.1); color: var(--accent-blue); border: 1px solid rgba(41, 121, 255, 0.2); }
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-icon { width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; }
        .ico { width: 20px; height: 20px; flex: none; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .logo-icon { display: inline-flex; }
        .logo-icon .ico { width: 26px; height: 26px; color: var(--accent-green); }
        .avatar { display: inline-flex; align-items: center; }
        .avatar .ico { width: 18px; height: 18px; color: var(--accent-green); }
        
        .mt-auto { margin-top: auto; }
        .text-danger { color: #ff5252 !important; }
        .text-danger:hover { background: rgba(255, 82, 82, 0.1); }
        
        /* Main Area */
        .admin-main { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }
        .admin-topbar { padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--glass-border); z-index: 10; }
        
        .glass-pill { background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 50px; padding: 6px 16px; display: flex; align-items: center; gap: 8px; }
        .admin-content-area { flex: 1; overflow-y: auto; padding: 2rem; }

        .admin-flash { padding: 12px 18px; border-radius: 8px; margin-bottom: 18px; font-weight: 600; font-size: 14px; display: flex; justify-content: space-between; align-items: center; }
        .admin-flash.flash-success { background: rgba(0, 230, 118, 0.15); border: 1px solid #00e676; color: #00e676; }
        .admin-flash.flash-error { background: rgba(255, 77, 77, 0.15); border: 1px solid #ff4d4d; color: #ff6b6b; }
        .admin-flash button { background: none; border: none; color: inherit; font-size: 18px; cursor: pointer; line-height: 1; }
        
        /* Typography */
        .m-0 { margin: 0; }
        .mb-4 { margin-bottom: 1.5rem; }
    </style>
</head>
<body class="theme-dark admin-layout">
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="i-shuttle" viewBox="0 0 24 24">
                <path d="M12 2.5 5.5 12h13L12 2.5Z"/><path d="M12 2.5V12"/><path d="M8.75 7.25h6.5"/>
                <circle cx="12" cy="15.5" r="3.2"/>
            </symbol>
            <symbol id="i-dashboard" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/>
                <rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>
            </symbol>
            <symbol id="i-building" viewBox="0 0 24 24">
                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>
            </symbol>
            <symbol id="i-calendar" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                <path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/>
            </symbol>
            <symbol id="i-tag" viewBox="0 0 24 24">
                <path d="M12.6 2.6A2 2 0 0 0 11.2 2H4a2 2 0 0 0-2 2v7.2a2 2 0 0 0 .6 1.4l8.7 8.7a2.4 2.4 0 0 0 3.4 0l6.6-6.6a2.4 2.4 0 0 0 0-3.4Z"/>
                <circle cx="7.5" cy="7.5" r="1.2"/>
            </symbol>
            <symbol id="i-trending" viewBox="0 0 24 24">
                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
            </symbol>
            <symbol id="i-logout" viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/>
                <line x1="21" x2="9" y1="12" y2="12"/>
            </symbol>
            <symbol id="i-crown" viewBox="0 0 24 24">
                <path d="M11.6 3.3a.5.5 0 0 1 .8 0l3 5.6a1 1 0 0 0 1.5.3l4.3-3.7a.5.5 0 0 1 .8.5l-2.8 10.2a1 1 0 0 1-1 .7H5.8a1 1 0 0 1-1-.7L2 5.9a.5.5 0 0 1 .8-.5l4.3 3.7a1 1 0 0 0 1.5-.3Z"/>
                <path d="M5 21h14"/>
            </symbol>
        </defs>
    </svg>
    <!-- Sidebar -->
    <aside class="admin-sidebar glass-panel">
        <div class="sidebar-header">
            <a href="<?= BASE_URL ?>/owner/dashboard" class="logo">
                <span class="logo-icon"><svg class="ico"><use href="#i-shuttle"/></svg></span> <span class="logo-text">Smash<span class="text-accent">Admin</span></span>
            </a>
        </div>
        <nav class="sidebar-nav">
            <?php $currentPath = parse_url($_SERVER["REQUEST_URI"] ?? '/', PHP_URL_PATH); ?>
            <a href="<?= BASE_URL ?>/owner/dashboard" class="nav-item <?= str_contains($currentPath, 'dashboard') ? 'active' : '' ?>">
                <span class="nav-icon"><svg class="ico"><use href="#i-dashboard"/></svg></span> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/owner/branches" class="nav-item <?= str_contains($currentPath, 'branches') ? 'active' : '' ?>">
                <span class="nav-icon"><svg class="ico"><use href="#i-building"/></svg></span> Chi Nhánh
            </a>
            <a href="<?= BASE_URL ?>/owner/bookings" class="nav-item <?= str_contains($currentPath, 'bookings') ? 'active' : '' ?>">
                <span class="nav-icon"><svg class="ico"><use href="#i-calendar"/></svg></span> Lịch Đặt
            </a>
            <a href="<?= BASE_URL ?>/owner/pricing" class="nav-item <?= str_contains($currentPath, 'pricing') ? 'active' : '' ?>">
                <span class="nav-icon"><svg class="ico"><use href="#i-tag"/></svg></span> Quản Lý Giá
            </a>
            <a href="<?= BASE_URL ?>/owner/revenue" class="nav-item <?= str_contains($currentPath, 'revenue') ? 'active' : '' ?>">
                <span class="nav-icon"><svg class="ico"><use href="#i-trending"/></svg></span> Doanh Thu
            </a>
            <a href="<?= BASE_URL ?>/owner/logout" class="nav-item mt-auto text-danger">
                <span class="nav-icon"><svg class="ico"><use href="#i-logout"/></svg></span> Đăng Xuất
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <header class="admin-topbar glass-panel">
            <div class="page-title">
                <h2 class="m-0" style="font-size: 1.25rem; font-weight: 600;"><?= htmlspecialchars($title ?? 'Dashboard') ?></h2>
            </div>
            <div class="topbar-actions">
                <div class="owner-badge glass-pill">
                    <span class="avatar"><svg class="ico"><use href="#i-crown"/></svg></span>
                    <span class="name font-weight-500"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Super Admin') ?></span>
                </div>
            </div>
        </header>

        <div class="admin-content-area">
            <?php if ($fs = \Core\Session::getFlash('success')): ?>
                <div class="admin-flash flash-success">
                    <span><?= htmlspecialchars($fs) ?></span>
                    <button type="button" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?php if ($fe = \Core\Session::getFlash('error')): ?>
                <div class="admin-flash flash-error">
                    <span><?= htmlspecialchars($fe) ?></span>
                    <button type="button" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?= $content ?? '' ?>
        </div>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/admin.js"></script>
</body>
</html>
