<?php ob_start(); ?>
<style>
.filter-bar { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; align-items: flex-end; }
.form-group { margin-bottom: 0; }
.form-group label { display: block; color: #ccc; font-size: 12px; margin-bottom: 5px; }
.form-control { padding: 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: #fff; min-width: 150px; outline: none; }
.btn-primary { background: #00e676; color: #0a0e1a; padding: 10px 20px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
.table-container { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; color: #fff; white-space: nowrap; }
th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
th { background: rgba(0,0,0,0.3); color: #2979ff; font-size: 13px; text-transform: uppercase; }
.status-select { background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 5px; border-radius: 4px; outline: none; }
.status-select.pending { color: #ffb300; border-color: #ffb300; }
.status-select.confirmed { color: #00e676; border-color: #00e676; }
.status-select.cancelled { color: #ff4d4d; border-color: #ff4d4d; }

.header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); z-index: 1000; align-items: center; justify-content: center; }
.modal.active { display: flex; }
.modal-content { background: #1a2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; width: 560px; max-width: 94%; max-height: 92vh; overflow-y: auto; padding: 25px; color: #fff; }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; font-size: 18px; font-weight: 700; }
.close-modal { background: transparent; border: none; color: #aaa; font-size: 22px; cursor: pointer; }
.modal .form-group { margin-bottom: 12px; }
.modal .form-group label { display: block; color: #ccc; margin-bottom: 6px; font-size: 13px; }
.modal .form-control { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.25); color: #fff; box-sizing: border-box; min-width: 0; }
.modal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.walkin-total { background: rgba(0,230,118,0.1); border: 1px solid rgba(0,230,118,0.4); border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; align-items: center; margin: 4px 0 12px; }
.walkin-total span { color: #ccc; font-size: 13px; }
.walkin-total strong { color: #00e676; font-size: 18px; }
</style>

<div style="font-family: 'Inter', sans-serif;">
    <div class="header-actions">
        <h2 style="color: #fff; margin: 0;">Quản Lý Đặt Sân</h2>
        <button type="button" class="btn-primary" onclick="openModal('walkInModal')">+ Đặt Sân Tại Quầy</button>
    </div>

    <form class="filter-bar" method="GET" action="<?= BASE_URL ?>/owner/bookings">
        <div class="form-group">
            <label>Ngày</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="form-group">
            <label>Chi nhánh</label>
            <select name="branch_id" class="form-control">
                <option value="">Tất cả chi nhánh</option>
                <?php foreach (($branches ?? []) as $br): ?>
                    <option value="<?= (int)$br['id'] ?>" <?= ((string)($branchId ?? '') === (string)$br['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($br['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="">Tất cả</option>
                <option value="pending" <?= (($status ?? '') === 'pending') ? 'selected' : '' ?>>Đang chờ xử lý</option>
                <option value="confirmed" <?= (($status ?? '') === 'confirmed') ? 'selected' : '' ?>>Đã xác nhận</option>
                <option value="cancelled" <?= (($status ?? '') === 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
            </select>
        </div>
        <button type="submit" class="btn-primary">Lọc Dữ Liệu</button>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Mã Đặt Sân</th>
                    <th>Chi Nhánh</th>
                    <th>Sân</th>
                    <th>Khách Hàng</th>
                    <th>SĐT</th>
                    <th>Ngày</th>
                    <th>Giờ</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $bookings = $bookings ?? [
                    ['id'=>1, 'code'=>'BB-111', 'branch'=>'Q1', 'court'=>'A1', 'customer'=>'Nguyen A', 'phone'=>'0901', 'date'=>'10/10/2023', 'time'=>'18:00 - 19:00', 'price'=>150000, 'status'=>'pending']
                ];
                foreach($bookings as $b): 
                ?>
                <tr>
                    <td style="font-family: monospace; font-weight: bold; color: #2979ff;"><?= htmlspecialchars($b['booking_code'] ?? $b['code']) ?></td>
                    <td><?= htmlspecialchars($b['branch_name'] ?? ($b['branch'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['court_name'] ?? ($b['court'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['customer_name'] ?? ($b['customer'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['customer_phone'] ?? ($b['phone'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars(isset($b['booking_date']) ? date('d/m/Y', strtotime($b['booking_date'])) : ($b['date'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars((isset($b['start_time']) ? substr($b['start_time'],0,5) . ' - ' . substr($b['end_time'],0,5) : ($b['time'] ?? '—'))) ?></td>
                    <td style="color: #00e676; font-weight: bold;"><?= number_format($b['total_price'] ?? ($b['price'] ?? 0)) ?>đ</td>
                    <td>
                        <form action="<?= BASE_URL ?>/owner/booking/update" method="POST" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $b['id'] ?>">
                            <select name="status" class="status-select <?= strtolower($b['status']) ?>" onchange="this.form.submit()">
                                <option value="pending" <?= $b['status']=='pending'?'selected':'' ?>>Đang chờ xử lý</option>
                                <option value="confirmed" <?= $b['status']=='confirmed'?'selected':'' ?>>Đã xác nhận</option>
                                <option value="cancelled" <?= $b['status']=='cancelled'?'selected':'' ?>>Đã hủy</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Walk-in Booking Modal -->
<div class="modal" id="walkInModal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Đặt Sân Tại Quầy</span>
            <button type="button" class="close-modal" onclick="closeModal('walkInModal')">&times;</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/owner/booking/store" id="walkInForm">
            <div class="form-group">
                <label>Chi nhánh</label>
                <select name="branch_id" id="wi_branch" class="form-control" required>
                    <option value="">-- Chọn chi nhánh --</option>
                    <?php foreach (($branches ?? []) as $br): ?>
                        <option value="<?= (int)$br['id'] ?>" <?= ((string)($branchId ?? '') === (string)$br['id']) ? 'selected' : '' ?>><?= htmlspecialchars($br['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Sân</label>
                <select name="court_id" id="wi_court" class="form-control" required>
                    <option value="">-- Chọn sân --</option>
                </select>
            </div>
            <div class="modal-grid">
                <div class="form-group">
                    <label>Ngày</label>
                    <input type="date" name="booking_date" id="wi_date" class="form-control" value="<?= htmlspecialchars($date ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="confirmed" selected>Đã xác nhận</option>
                        <option value="pending">Đang chờ xử lý</option>
                    </select>
                </div>
            </div>
            <div class="modal-grid">
                <div class="form-group">
                    <label>Giờ bắt đầu</label>
                    <select name="start_hour" id="wi_start" class="form-control" required>
                        <?php for ($h = 5; $h <= 21; $h++): ?>
                            <option value="<?= $h ?>"><?= sprintf('%02d:00', $h) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Giờ kết thúc</label>
                    <select name="end_hour" id="wi_end" class="form-control" required>
                        <?php for ($h = 6; $h <= 22; $h++): ?>
                            <option value="<?= $h ?>" <?= $h == 7 ? 'selected' : '' ?>><?= sprintf('%02d:00', $h) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="walkin-total">
                <span>Giá tạm tính:</span>
                <strong id="wi_total">0đ</strong>
            </div>
            <div class="modal-grid">
                <div class="form-group">
                    <label>Tên khách</label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Khách lẻ">
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="customer_phone" class="form-control" placeholder="09xxxxxxxx">
                </div>
            </div>
            <div class="form-group">
                <label>Ghi chú</label>
                <input type="text" name="notes" class="form-control" placeholder="Ví dụ: khách quen, thuê vợt...">
            </div>
            <button type="submit" class="btn-primary" style="width:100%; margin-top:8px;">Tạo Đặt Sân</button>
        </form>
    </div>
</div>

<script>
const ALL_COURTS = <?= json_encode($courts ?? []) ?>;

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function fillCourts() {
    const branchId = document.getElementById('wi_branch').value;
    const courtSelect = document.getElementById('wi_court');
    courtSelect.innerHTML = '<option value="">-- Chọn sân --</option>';
    ALL_COURTS
        .filter(c => String(c.branch_id) === String(branchId))
        .forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            courtSelect.appendChild(opt);
        });
}

function calcTotal() {
    const dateVal = document.getElementById('wi_date').value;
    const start = parseInt(document.getElementById('wi_start').value, 10);
    const end = parseInt(document.getElementById('wi_end').value, 10);
    if (!dateVal || isNaN(start) || isNaN(end) || end <= start) {
        document.getElementById('wi_total').textContent = '0đ';
        return;
    }
    const day = new Date(dateVal + 'T00:00:00').getDay();
    const weekend = (day === 0 || day === 6);
    let total = 0;
    for (let h = start; h < end; h++) {
        if (weekend) { total += 180000; continue; }
        const peak = (h >= 17 || (h >= 8 && h < 14));
        total += peak ? 150000 : 80000;
    }
    document.getElementById('wi_total').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
}

document.getElementById('wi_branch').addEventListener('change', fillCourts);
document.getElementById('wi_date').addEventListener('change', calcTotal);
document.getElementById('wi_start').addEventListener('change', calcTotal);
document.getElementById('wi_end').addEventListener('change', calcTotal);
document.getElementById('walkInForm').addEventListener('submit', function(e) {
    if (parseInt(document.getElementById('wi_end').value, 10) <= parseInt(document.getElementById('wi_start').value, 10)) {
        e.preventDefault();
        alert('Giờ kết thúc phải lớn hơn giờ bắt đầu.');
    }
});
window.onclick = function(e) { if (e.target.classList.contains('modal')) e.target.classList.remove('active'); }

fillCourts();
calcTotal();
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php'; ?>
