<?php
// Script untuk setup database otomatis agar tidak error
$host = "localhost";
$user = "root";
$pass = ""; // Default XAMPP password is empty

// Koneksi awal tanpa memilih database
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error . " (Pastikan MySQL di XAMPP sudah menyala dan password benar)");
}

// 1. Buat database
$sql_create_db = "CREATE DATABASE IF NOT EXISTS db_peduli_sampah_krapyak DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($sql_create_db) === TRUE) {
    echo "Database 'db_peduli_sampah_krapyak' berhasil dibuat (atau sudah ada).<br>";
} else {
    die("Error membuat database: " . $conn->error);
}

// 2. Pilih database
$conn->select_db("db_peduli_sampah_krapyak");

// 3. Import isi tabel dari schema.sql
$schema_file = __DIR__ . '/database/schema.sql';
if (file_exists($schema_file)) {
    $sql_contents = file_get_contents($schema_file);
    
    // Pecah query berdasarkan titik koma (;)
    $queries = explode(';', $sql_contents);
    
    $success = true;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if (!$conn->query($query)) {
                // Abaikan error jika tabel sudah ada, tapi tampilkan error lain
                if(!str_contains($conn->error, "already exists")) {
                    echo "Error pada query: " . $conn->error . "<br>";
                    $success = false;
                }
            }
        }
    }
    if($success){
        echo "<b>Setup berhasil!</b> Tabel dan data awal (Admin & Santri default) sudah siap.<br>";
        echo "<br><a href='index.php'>Klik di sini untuk kembali ke Halaman Login/Dashboard</a>";
    }
} else {
    echo "File database/schema.sql tidak ditemukan.";
}

$conn->close();
?>
