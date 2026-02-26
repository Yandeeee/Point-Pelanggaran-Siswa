<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

// Ambil statistik data
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM siswa"))['count'];
$total_pelanggaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM pelanggaran"))['count'];
$total_riwayat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM riwayat_pelanggaran"))['count'];
$total_poin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sanksi_poin) as total FROM riwayat_pelanggaran JOIN pelanggaran ON riwayat_pelanggaran.pelanggaran_id = pelanggaran.id"))['total'] ?? 0;

// Ambil data 5 riwayat terbaru
$query_recent = mysqli_query($conn, "
    SELECT rp.*, s.nama_siswa, p.nama_pelanggaran, p.sanksi_poin
    FROM riwayat_pelanggaran rp
    JOIN siswa s ON rp.siswa_id = s.id
    JOIN pelanggaran p ON rp.pelanggaran_id = p.id
    ORDER BY rp.created_at DESC
    LIMIT 5
");
$recent_data = [];
if ($query_recent) {
    while ($row = mysqli_fetch_assoc($query_recent)) {
        $recent_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Point Pelanggaran Siswa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php">Riwayat Pelanggaran</a></li>
                <li><a href="pelanggaran.php">Data Pelanggaran</a></li>
                <li><a href="guru.php">Guru</a></li>
                <li><a href="siswa.php">Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php" style="color: #fff;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Admin</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['username'] == 'guru1' ? 'Drs. I Gusti Made Murjana,M.Pd' : $_SESSION['username']); ?></span>
                </div>
            </div>
            <div style="background: white; padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #2c3e50;">
                <p style="color: #555; font-size: 14px; margin: 0;">Berikut adalah ringkasan data dan statistik sistem manajemen poin pelanggaran siswa/i <br>SMK TI Bali Global Denpasar.</br></p>
            </div>

            <div class="content">
                <!-- Statistik Cards -->
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <h3>Total Siswa</h3>
                        <div class="number"><?php echo $total_siswa; ?></div>
                    </div>
                    <div class="stat-card success">
                        <h3>Total Pelanggaran</h3>
                        <div class="number"><?php echo $total_pelanggaran; ?></div>
                    </div>
                    <div class="stat-card warning">
                        <h3>Total Riwayat</h3>
                        <div class="number"><?php echo $total_riwayat; ?></div>
                    </div>
                    <div class="stat-card danger">
                        <h3>Total Poin Diberikan</h3>
                        <div class="number"><?php echo $total_poin; ?></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
                    <a href="siswa.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Siswa</a>
                    <a href="pelanggaran.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pelanggaran</a>
                    <a href="riwayat_pelanggaran.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Catat Pelanggaran</a>
                </div>

                <!-- Riwayat Terbaru -->
                <h2>Riwayat Pelanggaran Terbaru</h2>
                <?php if (count($recent_data) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($recent_data as $row): 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_pelanggaran']); ?></td>
                                    <td><span class="badge badge-warning"><?php echo $row['sanksi_poin']; ?> Poin</span></td>
                                    <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Tidak ada riwayat pelanggaran</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
