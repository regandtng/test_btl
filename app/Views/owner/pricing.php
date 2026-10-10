<?php ob_start(); ?>
<style>
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn-primary { background: #00e676; color: #0a0e1a; padding: 10px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .btn-action { border: none; border-radius: 6px; padding: 6px 12px; font-size: 13px; cursor: pointer; font-weight: 600; }
        .btn-edit { background: #2979ff; color: #fff; }
        .btn-danger { background: #ff4d4d; color: #fff; }
        .table-container { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; overflow: hidden; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; color: #fff; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        th { background: rgba(0,0,0,0.3); color: #2979ff; font-size: 14px; text-transform: uppercase; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: rgba(255,255,255,0.1); }
        .reference-card { background: linear-gradient(135deg, rgba(41, 121, 255, 0.1), rgba(0, 230, 118, 0.1)); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; color: #fff; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: #1a2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; width: 520px; max-width: 92%; padding: 25px; color: #fff; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-size: 18px; font-weight: 700; }
        .close-modal { background: transparent; border: none; color: #aaa; font-size: 22px; cursor: pointer; }
        .form-group { margin-bottom: 12px; }
        .form-group label { display: block; color: #ccc; margin-bottom: 6px; font-size: 13px; }
        .form-control { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.25); color: #fff; box-sizing: border-box; }
    </style>

<div style="font-family: 'Inter', sans-serif;">
    <div class="header-actions">
        <h2 style="color: #fff; margin: 0;">Quản Lý Bảng Giá</h2>
        <button type="button" class="btn-primary" onclick="openModal('addPriceModal')">+ Thêm Quy Tắc Giá</button>
    </div>

    <div class="reference-card" style="margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #00e676;">Quy tắc tính giá mặc định:</h3>
        <p>- <strong>Giá bình thường (5h-8h, 14h-17h):</strong> 80,000 VND / giờ</p>
        <p>- <strong>Giá vắng (8h-14h, 17h-22h):</strong> 150,000 VND / giờ</p>
        <p>- <strong>Cuối tuần (T7, CN):</strong> Phụ thu +20% trên giá gốc</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Áp dụng cho</th>
                    <th>Loại Ngày</th>
                    <th>Khung Giờ</th>
                    <th>Giá / Giờ</th>
                    <th>Nhãn / Ghi chú</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rules = $rules ?? [
                    ['target'=>'Toàn hệ thống', 'days'=>'Ngày thường', 'time'=>'05:00 - 08:00', 'price'=>80000, 'label'=>'Giá vắng'],
                    ['target'=>'Toàn hệ thống', 'days'=>'Ngày thường', 'time'=>'17:00 - 22:00', 'price'=>150000, 'label'=>'Giá vắng']
                ];
                foreach($rules as $r): 
                ?>
                <tr>
                    <td style="font-weight: bold;"><?= htmlspecialchars($r['branch_name'] ?? ($r['target'] ?? 'Toàn hệ thống')) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($r['day_type'] === 'weekend' ? 'Cuối tuần' : ($r['day_type'] === 'all' ? 'Tất cả các ngày' : 'Ngày thường')) ?></span></td>
                    <td><?= htmlspecialchars(isset($r['start_hour']) ? substr($r['start_hour'],0,5) . ' - ' . substr($r['end_hour'],0,5) : ($r['time'] ?? '-')) ?></td>
                    <td style="color: #00e676; font-weight: bold;"><?= number_format($r['price_per_hour'] ?? ($r['price'] ?? 0)) ?>đ/giờ</td>
                    <td><?= htmlspecialchars($r['label'] ?? '') ?></td>
                    <td>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <button type="button" class="btn-action btn-edit"
                                data-id="<?= (int)$r['id'] ?>"
                                data-branch="<?= htmlspecialchars($r['branch_id'] ?? '', ENT_QUOTES) ?>"
                                data-day="<?= htmlspecialchars($r['day_type'] ?? 'all', ENT_QUOTES) ?>"
                                data-start="<?= htmlspecialchars(substr($r['start_hour'] ?? '05:00', 0, 5), ENT_QUOTES) ?>"
                                data-end="<?= htmlspecialchars(substr($r['end_hour'] ?? '22:00', 0, 5), ENT_QUOTES) ?>"
                                data-price="<?= (int)($r['price_per_hour'] ?? 0) ?>"
                                data-label="<?= htmlspecialchars($r['label'] ?? '', ENT_QUOTES) ?>"
                                onclick="editPrice(this)">Sửa</button>
                            <form method="POST" action="<?= BASE_URL ?>/owner/pricing/delete" onsubmit="return confirm('Xóa quy tắc giá này?');" style="display:inline;">
                                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn-action btn-danger">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- add Modal -->
<div class="modal" id="addPriceModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Thêm Quy Tắc Giá</span>
            <button class="close-modal" onclick="closeModal('addPriceModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/owner/pricing/store">
            <div class="form-group">
                <label>Áp dụng cho (chi nhánh)</label>
                <select name="branch_id" class="form-control">
                    <option value="">Toàn hệ thống</option>
                    <?php foreach (($branches ?? []) as $br): ?>
                        <option value="<?= (int)$br['id'] ?>"><?= htmlspecialchars($br['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Loại ngày</label>
                <select name="day_type" class="form-control">
                    <option value="weekday">Ngày thường</option>
                    <option value="weekend">Cuối tuần</option>
                    <option value="all">Tất cả các ngày</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label>Giá bắt đầu (HH:MM)</label>
                    <input type="time" name="start_hour" class="form-control" value="05:00" step="3600">
                </div>
                <div class="form-group">
                    <label>Giá kết thúc (HH:MM)</label>
                    <input type="time" name="end_hour" class="form-control" value="22:00" step="3600">
                </div>
            </div>
            <div class="form-group">
                <label>Giá mỗi giờ (VND)</label>
                <input type="number" name="price_per_hour" class="form-control" placeholder="80000" required>
            </div>
            <div class="form-group">
                <label>Nhãn/Ghi chú</label>
                <input type="text" name="label" class="form-control" placeholder="Ví dụ: Giá vắng">
            </div>
            <button type="submit" class="btn-primary" style="width:100%; margin-top:8px;">Lưu</button>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal" id="editPriceModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Sửa Quy Tắc Giá</span>
            <button class="close-modal" onclick="closeModal('editPriceModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/owner/pricing/update">
            <input type="hidden" name="id" id="price_id">
            <div class="form-group">
                <label>Áp dụng cho (chi nhánh)</label>
                <select name="branch_id" class="form-control" id="price_branch">
                    <option value="">Toàn hệ thống</option>
                    <?php foreach (($branches ?? []) as $br): ?>
                        <option value="<?= (int)$br['id'] ?>"><?= htmlspecialchars($br['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Loại ngày</label>
                <select name="day_type" class="form-control" id="price_day">
                    <option value="weekday">Ngày thường</option>
                    <option value="weekend">Cuối tuần</option>
                    <option value="all">Tất cả các ngày</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div class="form-group">
                    <label>Giá bắt đầu</label>
                    <input type="time" name="start_hour" class="form-control" id="price_start" step="3600">
                </div>
                <div class="form-group">
                    <label>Giá kết thúc</label>
                    <input type="time" name="end_hour" class="form-control" id="price_end" step="3600">
                </div>
            </div>
            <div class="form-group">
                <label>Giá mỗi giờ</label>
                <input type="number" name="price_per_hour" class="form-control" id="price_val" required>
            </div>
            <div class="form-group">
                <label>Nhãn/Ghi chú</label>
                <input type="text" name="label" class="form-control" id="price_label">
            </div>
            <button type="submit" class="btn-primary" style="width:100%; margin-top:8px;">Cập nhật</button>
        </form>
    </div>
</div>

<script>
function openModal(id){ document.getElementById(id).classList.add('active'); }
function closeModal(id){ document.getElementById(id).classList.remove('active'); }
function editPrice(btn){
    document.getElementById('price_id').value = btn.dataset.id;
    document.getElementById('price_branch').value = btn.dataset.branch || '';
    document.getElementById('price_day').value = btn.dataset.day || 'all';
    document.getElementById('price_start').value = btn.dataset.start || '05:00';
    document.getElementById('price_end').value = btn.dataset.end || '22:00';
    document.getElementById('price_val').value = btn.dataset.price || 0;
    document.getElementById('price_label').value = btn.dataset.label || '';
    openModal('editPriceModal');
}
window.onclick = function(e){ if (e.target.classList.contains('modal')) e.target.classList.remove('active'); }
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php'; ?>
