<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị - SmashZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', sans-serif; background: #0a0e1a; color: #fff; display: flex; align-items: center; justify-content: center; min-height: 100vh; background-image: radial-gradient(circle at top right, rgba(41, 121, 255, 0.1), transparent 40%), radial-gradient(circle at bottom left, rgba(0, 230, 118, 0.1), transparent 40%); }
        .admin-card { background: rgba(10, 14, 26, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 50px 40px; width: 100%; max-width: 400px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); text-align: center; }
        .logo { font-size: 32px; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px; }
        .logo span { color: #00e676; }
        .subtitle { color: #aaa; margin-bottom: 40px; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; }
        .form-group { margin-bottom: 25px; text-align: left; }
        .form-control { width: 100%; padding: 15px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #fff; outline: none; transition: 0.3s; font-size: 15px; box-sizing: border-box; }
        .form-control:focus { border-color: #00e676; background: rgba(255,255,255,0.05); }
        .btn-primary { background: linear-gradient(135deg, #00e676, #00b359); color: #0a0e1a; font-weight: 800; border: none; border-radius: 10px; padding: 15px; cursor: pointer; width: 100%; font-size: 16px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 230, 118, 0.3); }
        .back-link { display: inline-block; margin-top: 30px; color: #2979ff; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="admin-card">
        <div class="logo"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#00e676" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-4px; margin-right:6px;"><path d="M12 2.5 5.5 12h13L12 2.5Z"/><path d="M12 2.5V12"/><path d="M8.75 7.25h6.5"/><circle cx="12" cy="15.5" r="3.2"/></svg>Smash<span>Zone</span></div>
        <div class="subtitle">Hệ Thống Quản Trị</div>
        
        <?php if(isset($error)): ?>
            <div style="background: rgba(255,77,77,0.1); color: #ff4d4d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid rgba(255,77,77,0.2);">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/owner/login" method="POST">
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập (VD: admin)" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            </div>
            
            <button type="submit" class="btn-primary">Đăng Nhập</button>
        </form>

        <a href="<?= BASE_URL ?>/" class="back-link"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px; margin-right:4px;"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>Quay lại trang khách hàng</a>
    </div>
</body>
</html>
