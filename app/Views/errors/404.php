<?php
// Simple 404 error page
$title = $title ?? '404 - Không Tìm Thấy Trang';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:#0a0e1a;color:#f1f5f9;font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center}
  .container{padding:2rem}
  .code{font-size:8rem;font-weight:900;background:linear-gradient(135deg,#00e676,#2979ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1}
  h1{font-size:1.5rem;margin:1rem 0;color:#94a3b8}
  p{color:#64748b;margin-bottom:2rem}
  a{display:inline-block;padding:.75rem 2rem;background:linear-gradient(135deg,#00e676,#00b0ff);color:#0a0e1a;text-decoration:none;border-radius:50px;font-weight:700}
  a:hover{opacity:.85}
</style>
</head>
<body>
  <div class="container">
    <div class="code">404</div>
    <h1>Trang Không Tồn Tại</h1>
    <p>Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
    <a href="<?= BASE_URL ?>/">🏸 Về Trang Chủ</a>
  </div>
</body>
</html>
