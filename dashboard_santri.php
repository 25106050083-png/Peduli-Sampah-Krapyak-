<?php
session_start();
require_once 'classes/Santri.php';
require_once 'classes/Sampah.php';
require_once 'classes/Riwayat.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'santri') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$santri = new Santri();
$sampahObj = new Sampah();
$riwayatObj = new Riwayat();
$profile = $santri->getProfile($id_user);

// Handle Delete
if(isset($_GET['hapus'])) {
    if($sampahObj->hapusSampah($id_user, $_GET['hapus'])) {
        header("Location: dashboard_santri.php?msg=del_success");
        exit();
    }
}

// Handle Insert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sampah'])) {
    $jenis = $_POST['jenis_sampah'];
    $berat = $_POST['berat'];
    $tanggal = date('Y-m-d');
    
    $sampahObj->tambahSampah($id_user, $jenis, $berat, $tanggal, $profile['asrama_komplek'], $profile['yayasan']);
    header("Location: dashboard_santri.php?msg=add_success");
    exit();
}

$history_sampah = $sampahObj->getSampahByUser($id_user);
$timeline = $riwayatObj->getRiwayatByUser($id_user);

$total_setoran = count($history_sampah);
$badge = "";
if($total_setoran >= 10) {
    $badge = "<span class='badge badge-sangat-aktif'>🏅 Sangat Aktif</span>";
} elseif($total_setoran >= 1) {
    $badge = "<span class='badge badge-aktif'>✅ Aktif</span>";
} else {
    $badge = "<span class='badge'>Belum Aktif</span>";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri - Peduli Sampah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">🌿 Krapyak</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_santri.php" class="active">🏠 Dashboard</a></li>
                <li><a href="logout.php" style="color: #ffcdd2;">🚪 Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?> <?= $badge ?></h2>
                <span class="badge">Asrama <?= htmlspecialchars($profile['asrama_komplek'] ?? '') ?> - <?= htmlspecialchars($profile['yayasan'] ?? '') ?></span>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <?php if($_GET['msg']=='add_success'): ?>
                    <div class="alert alert-success mb-1">🎉 Data sampah berhasil ditambahkan!</div>
                <?php elseif($_GET['msg']=='del_success'): ?>
                    <div class="alert alert-success mb-1">🗑️ Data sampah berhasil dihapus!</div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="card-grid">
                <!-- Form Kiri -->
                <div class="stat-card" style="grid-column: span 1; text-align: left;">
                    <h3 class="mb-1" style="color:var(--primary-color)">Input Sampah Baru</h3>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Jenis Sampah</label>
                            <select name="jenis_sampah" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <optgroup label="Organik">
                                    <option value="Sisa makanan">Sisa makanan</option>
                                    <option value="Daun kering">Daun kering</option>
                                    <option value="Rumput">Rumput</option>
                                    <option value="Kayu kecil">Kayu kecil</option>
                                    <option value="Kulit buah">Kulit buah</option>
                                    <option value="Ampas kopi/teh">Ampas kopi/teh</option>
                                </optgroup>
                                <optgroup label="Plastik">
                                    <option value="Botol plastik">Botol plastik</option>
                                    <option value="Kantong plastik">Kantong plastik</option>
                                    <option value="Sedotan">Sedotan</option>
                                    <option value="Bungkus snack">Bungkus snack</option>
                                    <option value="Plastik keras">Plastik keras</option>
                                    <option value="Styrofoam">Styrofoam</option>
                                </optgroup>
                                <optgroup label="Kertas">
                                    <option value="Kertas HVS">Kertas HVS</option>
                                    <option value="Karton">Karton</option>
                                    <option value="Kardus">Kardus</option>
                                    <option value="Buku bekas">Buku bekas</option>
                                    <option value="Majalah">Majalah</option>
                                    <option value="Tisu bersih">Tisu bersih</option>
                                </optgroup>
                                <optgroup label="Logam">
                                    <option value="Kaleng minuman">Kaleng minuman</option>
                                    <option value="Besi kecil">Besi kecil</option>
                                    <option value="Aluminium foil">Aluminium foil</option>
                                    <option value="Tutup botol logam">Tutup botol logam</option>
                                    <option value="Kawat">Kawat</option>
                                    <option value="Paku bekas">Paku bekas</option>
                                </optgroup>
                                <optgroup label="Kaca">
                                    <option value="Botol kaca">Botol kaca</option>
                                    <option value="Pecahan kaca">Pecahan kaca</option>
                                    <option value="Gelas kaca">Gelas kaca</option>
                                </optgroup>
                                <optgroup label="B3">
                                    <option value="Baterai bekas">Baterai bekas</option>
                                    <option value="Lampu neon">Lampu neon</option>
                                    <option value="Obat kadaluarsa">Obat kadaluarsa</option>
                                    <option value="Cairan kimia">Cairan kimia</option>
                                    <option value="Masker medis bekas">Masker medis bekas</option>
                                </optgroup>
                                <optgroup label="Lain-lain">
                                    <option value="Kain bekas">Kain bekas</option>
                                    <option value="Sepatu rusak">Sepatu rusak</option>
                                    <option value="Elektronik kecil rusak">Elektronik kecil rusak</option>
                                    <option value="Campuran residu">Campuran residu</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Berat (Kg)</label>
                            <input type="number" step="0.1" name="berat" class="form-control" required>
                        </div>
                        <button type="submit" name="submit_sampah" class="btn btn-accent">Simpan</button>
                    </form>

                    <h3 class="mb-1" style="margin-top:30px;">Tabel Data Setoran</h3>
                    <table>
                        <thead>
                            <tr><th>Tgl</th><th>Jenis</th><th>Kg</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($history_sampah as $row): ?>
                            <tr>
                                <td><?= date('d/m', strtotime($row['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($row['jenis_sampah']) ?></td>
                                <td><?= htmlspecialchars($row['berat']) ?></td>
                                <td><a href="?hapus=<?= $row['id_sampah'] ?>" class="btn btn-danger btn-small" onclick="return confirm('Hapus data ini?');">Hapus</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Timeline Kanan -->
                <div class="stat-card" style="grid-column: span 1; text-align: left;">
                    <h3 class="mb-1">Riwayat Kegiatan (Activity Log)</h3>
                    <ul class="timeline">
                        <?php foreach($timeline as $log): 
                            $iconClass = '';
                            if($log['aktivitas'] == 'input') $iconClass = 'icon-input';
                            if($log['aktivitas'] == 'edit') $iconClass = 'icon-edit';
                            if($log['aktivitas'] == 'hapus') $iconClass = 'icon-hapus';
                        ?>
                        <li class="timeline-item">
                            <div class="timeline-icon <?= $iconClass ?>"></div>
                            <div class="timeline-content">
                                <span class="time"><?= date('d M Y H:i', strtotime($log['waktu'])) ?></span>
                                <p><?= htmlspecialchars($log['detail_aktivitas']) ?></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                        <?php if(count($timeline) === 0): ?>
                            <p class="text-center">Belum ada aktivitas.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
