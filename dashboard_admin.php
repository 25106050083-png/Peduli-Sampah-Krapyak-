<?php
session_start();
require_once 'classes/Admin.php';
require_once 'classes/Sampah.php';
require_once 'classes/Riwayat.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin = new Admin();
$sampahObj = new Sampah();
$riwayatObj = new Riwayat();

$stats = $admin->getStatistik();
$allSampah = $sampahObj->getAllSampah();
$grafikData = $sampahObj->getGrafikJenisSampah();
$allRiwayat = $riwayatObj->getAllRiwayat();

// Prepare chart labels and values
$chartLabels = [];
$chartData = [];
foreach($grafikData as $g) {
    $chartLabels[] = $g['jenis_sampah'];
    $chartData[] = $g['total_berat'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Peduli Sampah</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">🌿 Admin Panel</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_admin.php" class="active">📊 Overview</a></li>
                <li><a href="#audit">📜 Audit Log</a></li>
                <li><a href="logout.php" style="color: #ffcdd2;">🚪 Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?></h2>
            </div>

            <div class="card-grid">
                <div class="stat-card">
                    <h3>Total Sampah Hari Ini</h3>
                    <div class="value"><?= number_format($stats['total_sampah_hari_ini'], 2) ?> Kg</div>
                </div>
                <div class="stat-card">
                    <h3>Total Sampah Keseluruhan</h3>
                    <div class="value"><?= number_format($stats['total_sampah_keseluruhan'], 2) ?> Kg</div>
                </div>
            </div>

            <div class="card-grid">
                <div class="stat-card" style="grid-column: span 2;">
                    <h3 class="mb-1">Grafik Jenis Sampah Terkumpul</h3>
                    <canvas id="sampahChart" style="max-height: 250px;"></canvas>
                </div>
                
                <div class="stat-card">
                    <h3 class="mb-1">Top 5 Santri Terbersih</h3>
                    <table style="font-size: 0.9rem;">
                        <thead>
                            <tr><th>Nama</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($stats['top_santri'] as $top): ?>
                            <tr>
                                <td><?= htmlspecialchars($top['nama']) ?></td>
                                <td><?= number_format($top['total_berat'], 2) ?> Kg</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="stat-card" style="text-align: left; margin-bottom:30px;" id="audit">
                <h3 class="mb-1">📜 Audit Log (Semua Riwayat Kegiatan Santri)</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Nama Santri</th>
                            <th>Aktivitas</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($allRiwayat as $log): 
                            $color = '#333';
                            if($log['aktivitas'] == 'input') $color = 'var(--primary-color)';
                            if($log['aktivitas'] == 'edit') $color = 'var(--accent-color)';
                            if($log['aktivitas'] == 'hapus') $color = '#d32f2f';
                        ?>
                        <tr>
                            <td><?= $log['waktu'] ?></td>
                            <td><?= htmlspecialchars($log['nama']) ?></td>
                            <td style="color: <?= $color ?>; font-weight:bold; text-transform:uppercase;"><?= htmlspecialchars($log['aktivitas']) ?></td>
                            <td><?= htmlspecialchars($log['detail_aktivitas']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('sampahChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Total Berat (Kg)',
                    data: <?= json_encode($chartData) ?>,
                    backgroundColor: '#81c784',
                    borderColor: '#2e7d32',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
