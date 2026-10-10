<?php ob_start(); ?>
<style>
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
.panel { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; color: #fff; }
.stat-box { text-align: center; padding: 20px; border-radius: 8px; background: rgba(0,0,0,0.3); }
.stat-box .title { color: #aaa; font-size: 14px; margin-bottom: 10px; text-transform: uppercase; }
.stat-box .val { color: #00e676; font-size: 28px; font-weight: bold; }
.rev-table { width: 100%; border-collapse: collapse; }
.rev-table th, .rev-table td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
.rev-table th { color: #2979ff; font-size: 13px; text-transform: uppercase; }
.progress-bg { background: rgba(255,255,255,0.1); height: 8px; border-radius: 4px; width: 100%; overflow: hidden; margin-top: 5px; }
.progress-bar { background: #00e676; height: 100%; }
.filter-select { padding: 10px; background: rgba(0,0,0,0.3); color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; }
.empty { color: #888; text-align: center; padding: 20px; }
</style>

<div style="font-family: 'Inter', sans-serif;">
    <h2 style="color: #fff; margin-top: 0; margin-bottom: 20px;">Báo Cáo Doanh Thu</h2>

    <form method="GET" action="<?= BASE_URL ?>/owner/revenue" class="panel" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <select name="branch_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Tất cả chi nhánh</option>
            <?php foreach ($branches as $br): ?>
                <option value="<?= (int)$br['id'] ?>" <?= ((string)$branchId === (string)$br['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($br['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span style="color:#aaa; font-size:14px;">Năm <?= date('Y') ?> · Chỉ tính đơn đã xác nhận/hoàn thành</span>
    </form>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px;">
        <div class="stat-box">
            <div class="title">Doanh Thu Tháng <?= (int)date('n') ?></div>
            <div class="val"><?= number_format($monthRevenue ?? 0) ?>đ</div>
        </div>
        <div class="stat-box">
            <div class="title">Doanh Thu Năm <?= date('Y') ?></div>
            <div class="val" style="color: #2979ff;"><?= number_format($yearRevenue ?? 0) ?>đ</div>
        </div>
        <div class="stat-box">
            <div class="title">Tổng Lượt Đặt (Tháng)</div>
            <div class="val" style="color: #fff;"><?= number_format($monthBookings ?? 0) ?></div>
        </div>
    </div>

    <div class="grid-2">
        <div class="panel">
            <h3 style="margin-top:0;">Biểu Đồ Doanh Thu Theo Tháng</h3>
            <canvas id="barChart" height="200"></canvas>
        </div>
        <div class="panel">
            <h3 style="margin-top:0;">Xếp Hạng Chi Nhánh</h3>
            <?php
            $ranking   = $branchRevenue ?? [];
            $maxRev    = 0;
            foreach ($ranking as $r) { if ((int)$r['revenue'] > $maxRev) $maxRev = (int)$r['revenue']; }
            ?>
            <?php if (empty($ranking)): ?>
                <p class="empty">Chưa có dữ liệu doanh thu.</p>
            <?php else: ?>
            <table class="rev-table">
                <thead>
                    <tr>
                        <th>Hạng</th>
                        <th>Chi Nhánh</th>
                        <th>Doanh Thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ranking as $index => $r): $percent = $maxRev > 0 ? round((int)$r['revenue'] / $maxRev * 100) : 0; ?>
                    <tr>
                        <td>#<?= $index + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($r['name']) ?>
                            <div class="progress-bg"><div class="progress-bar" style="width: <?= $percent ?>%;"></div></div>
                        </td>
                        <td style="color: #00e676; font-weight: bold;"><?= number_format((int)$r['revenue']) ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel" style="margin-top: 20px;">
        <h3 style="margin-top:0;">Tình Trạng Đặt Sân</h3>
        <?php
        $statusMap = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'];
        $breakdown = $statusBreakdown ?? [];
        ?>
        <?php if (empty($breakdown)): ?>
            <p class="empty">Chưa có dữ liệu.</p>
        <?php else: ?>
        <table class="rev-table">
            <thead><tr><th>Trạng Thái</th><th>Số Lượng</th></tr></thead>
            <tbody>
                <?php foreach ($breakdown as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($statusMap[$s['status']] ?? $s['status']) ?></td>
                    <td style="color:#00e676; font-weight:bold;"><?= (int)$s['cnt'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('barChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
            label: 'Doanh thu',
            data: <?= json_encode(array_map(fn($m) => (int)$m['revenue'], $monthlyRevenue ?? [])) ?>,
            backgroundColor: '#00e676',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#aaa' } },
            x: { grid: { display: false }, ticks: { color: '#aaa' } }
        }
    }
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php'; ?>
