<?php
session_start();
require_once 'classes/Santri.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $santri = new Santri();
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $asrama = trim($_POST['asrama']);
    $yayasan = trim($_POST['yayasan']);

    if($santri->register($nama, $username, $password, $asrama, $yayasan)) {
        $success = "Pendaftaran berhasil! Silakan login.";
    } else {
        $error = "Pendaftaran gagal atau Username sudah digunakan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Santri - Peduli Sampah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>📝 Daftar Akun Santri</h2>
            <p class="mb-1">Mari berkontribusi untuk lingkungan pesantren.</p>
            
            <?php if($error): ?>
                <div class="alert"><?= $error ?></div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Asrama / Komplek</label>
                    <select name="asrama" class="form-control" required>
                        <option value="">-- Pilih Asrama/Komplek --</option>
                        <option value="K">K</option>
                        <option value="L">L</option>
                        <option value="R1">R1</option>
                        <option value="R2">R2</option>
                        <option value="Q">Q</option>
                        <option value="T">T</option>
                        <option value="Nurussalam">Nurussalam</option>
                        <option value="Asrama Komplek N">Asrama Komplek N</option>
                        <option value="H">H</option>
                        <option value="Asrama Kayu">Asrama Kayu</option>
                        <option value="Hindun">Hindun</option>
                        <option value="Beta">Beta</option>
                        <option value="Asrama Rufaida">Asrama Rufaida</option>
                        <option value="Asrama Komplek Badar">Asrama Komplek Badar</option>
                        <option value="Huffadz">Huffadz</option>
                        <option value="Pusat">Pusat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Yayasan</label>
                    <select name="yayasan" class="form-control" required>
                        <option value="">-- Pilih Yayasan --</option>
                        <option value="Ali Maksum">Ali Maksum</option>
                        <option value="Munawwir">Munawwir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn" style="width:100%; margin-top:10px;">Daftar Akun</button>
            </form>
            <p style="margin-top: 20px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
