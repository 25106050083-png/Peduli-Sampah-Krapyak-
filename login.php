<?php
session_start();
require_once 'classes/User.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userObj = new User();
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $login = $userObj->login($username, $password);
    if ($login) {
        $_SESSION['user_id'] = $login['id_user'];
        $_SESSION['nama'] = $login['nama'];
        $_SESSION['role'] = $login['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Peduli Sampah Krapyak</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>🌿 Peduli Sampah Krapyak</h2>
            <p class="mb-2">Silakan login untuk masuk ke sistem.</p>
            
            <?php if($error): ?>
                <div class="alert"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-accent" style="width:100%; margin-top:10px;">Login</button>
            </form>
            <p style="margin-top: 20px;">Belum punya akun? <a href="register.php">Daftar Santri</a></p>
        </div>
    </div>
</body>
</html>
