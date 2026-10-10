<?php ob_start(); ?>

<div class="page-wrap">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Hồ Sơ Cá Nhân</h1>
            <p class="subtitle">Quản lý thông tin tài khoản và bảo mật</p>
        </div>

        <div class="profile-grid">
            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <span><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                    </div>
                    <h3 class="profile-name"><?= htmlspecialchars($user['name']) ?></h3>
                    <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                    <span class="badge badge-accent">Khách hàng</span>
                </div>
                <nav class="profile-nav">
                    <a href="#thong-tin" class="nav-link active" data-tab="thong-tin">
                        <svg class="ico"><use href="#i-user"/></svg>
                        <span>Thông tin cá nhân</span>
                    </a>
                    <a href="#doi-mat-khau" class="nav-link" data-tab="doi-mat-khau">
                        <svg class="ico"><use href="#i-lock"/></svg>
                        <span>Đổi mật khẩu</span>
                    </a>
                    <a href="<?= BASE_URL ?>/history" class="nav-link">
                        <svg class="ico"><use href="#i-calendar"/></svg>
                        <span>Lịch sử đặt sân</span>
                    </a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="profile-content">
                <!-- Tab: Thông tin cá nhân -->
                <section id="thong-tin" class="tab-pane active">
                    <div class="card">
                        <div class="card-header">
                            <h2>Thông Tin Cá Nhân</h2>
                        </div>
                        <div class="card-body">
                            <form action="<?= BASE_URL ?>/profile/update" method="POST" class="profile-form">
                                <div class="form-group">
                                    <label for="name">Họ và tên <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Số điện thoại <span class="required">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Ngày tạo tài khoản</label>
                                    <div class="form-control-plaintext"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></div>
                                </div>
                                <button type="submit" class="btn btn-accent">Lưu Thay Đổi</button>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Tab: Đổi mật khẩu -->
                <section id="doi-mat-khau" class="tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h2>Đổi Mật Khẩu</h2>
                        </div>
                        <div class="card-body">
                            <form action="<?= BASE_URL ?>/profile/change-password" method="POST" class="profile-form">
                                <div class="form-group">
                                    <label for="current_password">Mật khẩu hiện tại <span class="required">*</span></label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Mật khẩu mới <span class="required">*</span></label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required minlength="6" autocomplete="new-password">
                                    <small class="form-text">Tối thiểu 6 ký tự</small>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Xác nhận mật khẩu mới <span class="required">*</span></label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required autocomplete="new-password">
                                </div>
                                <button type="submit" class="btn btn-accent">Đổi Mật Khẩu</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<style>
.profile-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    margin-top: 30px;
}

.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.profile-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-green), #00b35f);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 8px 24px rgba(0, 230, 118, 0.3);
}

.profile-name {
    margin: 0 0 6px;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-main);
}

.profile-email {
    margin: 0 0 12px;
    font-size: 0.85rem;
    color: var(--text-muted);
}

.profile-nav {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.profile-nav .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    color: var(--text-muted);
    font-weight: 500;
    transition: all 0.2s;
}

.profile-nav .nav-link:hover,
.profile-nav .nav-link.active {
    background: rgba(41, 121, 255, 0.1);
    color: var(--accent-blue);
}

.profile-nav .ico {
    width: 20px;
    height: 20px;
    flex: none;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--card-border);
    background: rgba(255,255,255,0.02);
}

.card-header h2 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-main);
}

.card-body {
    padding: 24px;
}

.profile-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-main);
}

.required {
    color: #ff6b6b;
}

.form-control {
    background: var(--input-bg);
    border: 1px solid var(--input-border);
    border-radius: 10px;
    padding: 12px 16px;
    color: var(--text-main);
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-blue);
    box-shadow: 0 0 0 3px rgba(41, 121, 255, 0.15);
}

.form-control-plaintext {
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--input-border);
    border-radius: 10px;
    padding: 12px 16px;
    color: var(--text-muted);
    font-size: 1rem;
}

.form-text {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-accent {
    background: var(--accent-green);
    color: #000;
}

.btn-accent:hover {
    background: #00b35f;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 230, 118, 0.3);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 900px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .profile-sidebar {
        flex-direction: row;
        flex-wrap: wrap;
    }
    .profile-card {
        flex: 1;
        min-width: 280px;
    }
    .profile-nav {
        flex: 1;
        min-width: 280px;
        flex-direction: row;
    }
    .profile-nav .nav-link {
        justify-content: center;
    }
}

@media (max-width: 600px) {
    .profile-sidebar {
        flex-direction: column;
    }
    .profile-nav {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.profile-nav .nav-link[data-tab]');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const tabId = link.dataset.tab;
            
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            
            tabPanes.forEach(pane => {
                pane.classList.toggle('active', pane.id === tabId);
            });
        });
    });
});
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/main.php'; ?>