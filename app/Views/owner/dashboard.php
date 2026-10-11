<?php ob_start(); ?>
<style>
.dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
.stat-card { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; color: #fff; position: relative; overflow: hidden; }
.stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
.stat-card.blue::before { background: #2979ff; }
.stat-card.green::before { background: #00e676; }
.stat-card.yellow::before { background: #ffb300; }
.stat-card.purple::before { background: #d500f9; }
.stat-title { font-size: 14px; color: #aaa; margin-bottom: 10px; text-transform: uppercase; }
.stat-value { font-size: 32px; font-weight: bold; }
.chart-container { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; margin-bottom: 30px; }
.table-container { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 20px; overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); color: #fff; }
th { color: #2979ff; font-size: 14px; text-transform: uppercase; }
.badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
.badge.pending_payment { background: rgba(255, 179, 0, 0.2); color: #ffb300; }
.badge.confirmed { background: rgba(0, 230, 118, 0.2); color: #00e676; }
</style>

<div style="font-family: 'Inter', sans-serif;">
    <h2 style="color: #fff; margin-top: 0; margin-bottom: 25px;">Tổng Quan</h2>
    
    <div class="dashboard-grid">
        <div class="stat-card blue">
            <div class="stat-title">Tổng Chi Nhánh</div>
            <div class="stat-value"><?= $totalBranches ?? 13 ?></div>
        </div>
        <div class="stat-card green">
            <div class="stat-title">Tổng Sân</div>
            <div class="stat-value"><?= $totalCourts ?? 130 ?></div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-title">Lượt Đặt Hôm Nay</div>
            <div class="stat-value"><?= $todayBookings ?? 45 ?></div>
        </div>
        <div class="stat-card purple">
            <div class="stat-title">Doanh Thu Hôm Nay</div>
            <div class="stat-value"><?= number_format($todayRevenue ?? 4500000) ?>đ</div>
        </div>
    </div>

    <div class="chart-container">
        <h3 style="color: #fff; margin-top: 0;">Doanh Thu 7 Ngày Gần Nhất</h3>
        <canvas id="revenueChart" height="80"></canvas>
    </div>

    <div class="table-container">
        <h3 style="color: #fff; margin-top: 0;">Lượt Đặt Mới Nhất</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã Đặt</th>
                    <th>Chi Nhánh</th>
                    <th>Sân</th>
                    <th>Khách Hàng</th>
                    <th>Thời Gian</th>
                    <th>Giá</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $recent = $recentBookings ?? [
                    ['code'=>'BB-123', 'branch'=>'Q1', 'court'=>'A1', 'customer'=>'Nguyen A', 'time'=>'18:00 10/10', 'price'=>150000, 'status'=>'confirmed']
                ];
                foreach($recent as $b): 
                ?>
                <tr>
                    <td><?= htmlspecialchars($b['booking_code'] ?? ($b['code'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['branch_name'] ?? ($b['branch'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['court_name'] ?? ($b['court'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars($b['customer_name'] ?? ($b['customer'] ?? '—')) ?></td>
                    <td><?= htmlspecialchars(isset($b['start_time']) ? substr($b['start_time'],0,5) . ' ' . date('d/m', strtotime($b['booking_date'])) : ($b['time'] ?? '—')) ?></td>
                    <td style="color:#00e676;"><?= number_format($b['total_price'] ?? ($b['price'] ?? 0)) ?>đ</td>
                    <td><span class="badge <?= strtolower($b['status']) ?>"><?= htmlspecialchars($b['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const gradient = ctx.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(41, 121, 255, 0.5)');
gradient.addColorStop(1, 'rgba(41, 121, 255, 0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels ?? ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN']) ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?= json_encode($chartData ?? [1200000, 1900000, 1500000, 2200000, 1800000, 3500000, 4000000]) ?>,
            borderColor: '#2979ff',
            backgroundColor: gradient,
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#aaa' } },
            x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#aaa' } }
        }
    }
});
</script>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php'; ?>
