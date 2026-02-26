<?php
session_start();
include "../database.php";

// set timezone for logging
date_default_timezone_set('Asia/Makassar');

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];
$role     = $_POST['role'];

$query = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE username='$username' 
    AND role='$role'
");

$user = mysqli_fetch_assoc($query);

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = isset($user['nama']) && $user['nama'] !== '' ? $user['nama'] : $user['username'];

        // only log admin logins; guru logins won't be recorded
        if (isset($user['role']) && $user['role'] === 'admin') {
            // ensure login_logs table exists
            $createTable = "CREATE TABLE IF NOT EXISTS login_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100),
                role VARCHAR(50),
                ip_address VARCHAR(45),
                user_agent TEXT,
                logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            mysqli_query($conn, $createTable);

            // insert a log record for admin only
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = isset($_SERVER['HTTP_USER_AGENT']) ? mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']) : '';
            $usernameEsc = mysqli_real_escape_string($conn, $user['username']);
            $roleEsc = mysqli_real_escape_string($conn, $user['role']);
            $insertLog = "INSERT INTO login_logs (username, role, ip_address, user_agent) VALUES ('{$usernameEsc}', '{$roleEsc}', '" . mysqli_real_escape_string($conn, $ip) . "', '{$ua}')";
            mysqli_query($conn, $insertLog);
        }

        // redirect user based on role
        if (isset($user['role']) && $user['role'] === 'admin') {
            header("Location: ../../admin/dashboard.php");
            exit;
        } elseif (isset($user['role']) && $user['role'] === 'guru') {
            // relative path from login_app/verifikasi to /guru/dashboard.php
            header("Location: ../../guru/dashboard.php");
            exit;
        } elseif (isset($user['role']) && $user['role'] === 'siswa') {
            header("Location: ../siswa.php");
            exit;
        } else {
            header("Location: ../../admin/dashboard.php");
            exit;
        }
    } else {
        echo "<script>alert('Password salah');history.back();</script>";
    }
} else {
    echo "<script>alert('User tidak ditemukan');history.back();</script>";
}
?>
