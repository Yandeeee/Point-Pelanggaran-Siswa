<?php
// Mencegah error session double
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aktifkan laporan error agar tidak blank putih jika ada salah ketik
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../database.php"; 

date_default_timezone_set('Asia/Makassar');

// 1. Ambil Input & Bersihkan
$username = isset($_POST['username']) ? trim(mysqli_real_escape_string($conn, $_POST['username'])) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$role     = isset($_POST['role']) ? $_POST['role'] : '';

// 2. Cari di tabel users (Admin/Guru)
$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND role='$role'");
$user = mysqli_fetch_assoc($query);

// 3. Cari di tabel siswa jika di users tidak ada
if (!$user && $role === 'siswa') {
    // Cari berdasarkan NIS
    $query_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$username'");
    $user = mysqli_fetch_assoc($query_siswa);
    
    if ($user) {
        $user['role'] = 'siswa';
        $user['nama'] = $user['nama_siswa'] ?? $user['nama'] ?? 'Siswa';
    }
}

// 4. Proses Validasi
if ($user) {
    $is_valid_password = false;
    
    // Paksa string & trim agar 003 identik
    $input_pass = trim((string)$password);
    $db_pass    = trim((string)$user['password']);

    if ($user['role'] === 'siswa') {
        // Cek teks langsung (003 == 003) ATAU cek hash
        if ($input_pass == $db_pass || password_verify($input_pass, $db_pass)) {
            $is_valid_password = true;
        }
    } else {
        // Admin & Guru pakai hash
        if (password_verify($input_pass, $user['password'])) {
            $is_valid_password = true;
        }
    }

    if ($is_valid_password) {
        $_SESSION['login']    = true;
        $_SESSION['username'] = $username;
        $_SESSION['role']     = $user['role'];
        $_SESSION['nama']     = ($user['nama'] != '') ? $user['nama'] : $username;

        // Redirect otomatis (Pastikan folder siswa/admin sudah ada)
        header("Location: ../../" . $user['role'] . "/dashboard.php");
        exit;
    } else {
        echo "<script>alert('Password salah!');history.back();</script>";
    }
} else {
    // Hapus tanda // di bawah ini untuk melihat apa yang dibaca PHP
    die("DEBUG -> Input: [" . $input_pass . "] | DB: [" . $db_pass . "] | Panjang Input: " . strlen($input_pass) . " | Panjang DB: " . strlen($db_pass));
    echo "<script>alert('User tidak ditemukan! Cek kembali NIS dan pilihan Role Anda.');history.back();</script>";
}
?>