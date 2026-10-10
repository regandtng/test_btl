<?php ob_start(); ?>
<style>
.header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.btn { padding: 10px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; color: #0a0e1a; }
.btn-primary { background: #00e676; }
.btn-primary:hover { box-shadow: 0 0 10px rgba(0, 230, 118, 0.5); }
.btn-sm { padding: 6px 12px; font-size: 13px; margin-right: 5px; }
.btn-edit { background: #2979ff; color: #fff; }
.btn-danger { background: #ff4d4d; color: #fff; }
.table-container { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; overflow: hidden; }
table { width: 100%; border-collapse: collapse; color: #fff; }
th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
th { background: rgba(0,0,0,0.3); color: #2979ff; font-size: 14px; text-transform: uppercase; }
tr:hover { background: rgba(255,255,255,0.02); }
.status-badge { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; }
.status-active { background: rgba(0, 230, 118, 0.2); color: #00e676; border: 1px solid #00e676; }
.status-inactive { background: rgba(255, 77, 77, 0.2); color: #ff4d4d; border: 1px solid #ff4d4d; }

/* Modal Styles */
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: #1a2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; width: 500px; max-width: 90%; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
.modal-header { display: flex; justify-content: space-between; margin-bottom: 20px; color: #fff; font-size: 20px; font-weight: bold; }
.close-modal { cursor: pointer; color: #aaa; font-size: 24px; border: none; background: transparent; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; color: #ccc; margin-bottom: 5px; font-size: 14px; }
.form-control { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: #fff; box-sizing: border-box; }
.form-control:focus { border-color: #00e676; outline: none; }
</style>

<div style="font-family: 'Inter', sans-serif;">
    <div class="header-actions">
        <h2 style="color: #fff; margin: 0;">Quản Lý Chi Nhánh</h2>
        <button class="btn btn-primary" onclick="openModal('addModal')">+ Thêm Chi Nhánh</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Chi Nhánh</th>
                    <th>Thành Phố</th>
                    <th>Địa Chỉ</th>
                    <th>Số Sân</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $branches = $branches ?? [
                    ['id'=>1, 'name'=>'SmashZone Quận 1', 'city'=>'Hồ Chí Minh', 'address'=>'123 Lê Lợi', 'courts'=>10, 'status'=>'active']
                ];
                foreach($branches as $index => $b): 
                ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td style="font-weight: bold;"><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['city']) ?></td>
                    <td><?= htmlspecialchars($b['address']) ?></td>
                    <td style="color: #00e676;"><?= ($b['courts_count'] ?? $b['courts'] ?? 0) ?> sân</td>
                    <td><span class="status-badge status-<?= (($b['is_active'] ?? $b['status'] ?? 'active') == 1 || ($b['status'] ?? '') == 'active') ? 'active' : 'inactive' ?>"><?= ((($b['is_active'] ?? $b['status'] ?? 'active') == 1) || ($b['status'] ?? '') == 'active') ? 'Hoạt động' : 'Tạm dừng' ?></span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-edit"
                            data-id="<?= (int)$b['id'] ?>"
                            data-name="<?= htmlspecialchars($b['name'] ?? '', ENT_QUOTES) ?>"
                            data-city="<?= htmlspecialchars($b['city'] ?? '', ENT_QUOTES) ?>"
                            data-address="<?= htmlspecialchars($b['address'] ?? '', ENT_QUOTES) ?>"
                            data-phone="<?= htmlspecialchars($b['phone'] ?? '', ENT_QUOTES) ?>"
                            data-active="<?= (($b['is_active'] ?? 1) == 1) ? 1 : 0 ?>"
                            onclick="editBranch(this)">Sửa</button>
                        <form method="POST" action="<?= BASE_URL ?>/owner/branch/update" style="display:inline;" onsubmit="return confirm('Xác nhận đổi trạng thái chi nhánh?');">
                            <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($b['name'] ?? '', ENT_QUOTES) ?>">
                            <input type="hidden" name="city" value="<?= htmlspecialchars($b['city'] ?? '', ENT_QUOTES) ?>">
                            <input type="hidden" name="address" value="<?= htmlspecialchars($b['address'] ?? '', ENT_QUOTES) ?>">
                            <input type="hidden" name="phone" value="<?= htmlspecialchars($b['phone'] ?? '', ENT_QUOTES) ?>">
                            <?php if (($b['is_active'] ?? 1) == 1): ?>
                                <button type="submit" class="btn btn-sm btn-danger">Tắt</button>
                            <?php else: ?>
                                <input type="hidden" name="is_active" value="1">
                                <button type="submit" class="btn btn-sm" style="background:#00e676;color:#0a0e1a;">Bật</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Thêm Chi Nhánh -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Thêm Chi Nhánh Mới</span>
            <button class="close-modal" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/owner/branch/store" method="POST">
            <div class="form-group">
                <label>Tên chi nhánh</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Thành phố</label>
                <input type="text" name="city" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ chi tiết</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Giờ mở cửa</label>
                    <input type="time" name="open_time" class="form-control" value="05:00">
                </div>
                <div class="form-group">
                    <label>Giờ đóng cửa</label>
                    <input type="time" name="close_time" class="form-control" value="22:00">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Lưu Chi Nhánh</button>
        </form>
    </div>
</div>

<!-- Modal Sửa Chi Nhánh -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Sửa Chi Nhánh</span>
            <button type="button" class="close-modal" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form action="<?= BASE_URL ?>/owner/branch/update" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Tên chi nhánh</label>
                <input type="text" name="name" class="form-control" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Thành phố</label>
                <input type="text" name="city" class="form-control" id="edit_city" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ chi tiết</label>
                <input type="text" name="address" class="form-control" id="edit_address" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control" id="edit_phone">
            </div>
            <div class="form-group" style="display:flex; align-items:center; gap:10px;">
                <input type="checkbox" name="is_active" id="edit_active" value="1" style="width:16px;height:16px;">
                <label for="edit_active">Đang hoạt động</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Lưu Thay Đổi</button>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editBranch(btn) {
    document.getElementById('edit_id').value = btn.dataset.id;
    document.getElementById('edit_name').value = btn.dataset.name;
    document.getElementById('edit_city').value = btn.dataset.city;
    document.getElementById('edit_address').value = btn.dataset.address;
    document.getElementById('edit_phone').value = btn.dataset.phone;
    document.getElementById('edit_active').checked = (btn.dataset.active === '1');
    openModal('editModal');
}
window.onclick = function(e) {
    if (e.target.classList.contains('modal')) { e.target.classList.remove('active'); }
}
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php'; ?>
