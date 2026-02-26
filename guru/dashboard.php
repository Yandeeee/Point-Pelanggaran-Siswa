<?php
session_start();
if (!isset($_SESSION['login']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../login_app/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <style>body{font-family:Segoe UI, Tahoma, sans-serif;background:#f5f5f5;padding:30px} .card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,0.08);max-width:900px;margin:auto}</style>
</head>
<body>
    <div class="card">
        <h1>Dashboard Guru</h1>
        <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
        <p>Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
        <p>Ini adalah halaman dashboard khusus guru. Anda bisa menambahkan menu, link, atau fitur di sini.</p>

        <p>
            <a href="../login_app/dashboard.php">Ke Dashboard Admin (jika punya akses)</a> |
            <a href="../login_app/logout.php">Logout</a>
        </p>
    </div>
</body>
</html>
