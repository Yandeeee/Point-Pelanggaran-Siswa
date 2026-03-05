<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

$username = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT id, nama_siswa, kelas, jurusan, no_telepon, email, nama_orangtua, telp_orangtua, pekerjaan_orangtua, alamat FROM siswa WHERE nis = '$username'");
$data_siswa = mysqli_fetch_assoc($query_user);

if(!$data_siswa) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location.href='../login_app/logout.php';</script>";
    exit;
}

$siswa_id = $data_siswa['id'];

// Ambil statistik data khusus siswa ini
$total_pelanggaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM riwayat_pelanggaran WHERE siswa_id = '$siswa_id'"))['count'];
$total_poin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(p.sanksi_poin) as total FROM riwayat_pelanggaran rp JOIN pelanggaran p ON rp.pelanggaran_id = p.id WHERE rp.siswa_id = '$siswa_id'"))['total'] ?? 0;

// Ambil data 5 riwayat terbaru khusus siswa ini
$query_recent = mysqli_query($conn, "
    SELECT rp.*, p.nama_pelanggaran, p.sanksi_poin
    FROM riwayat_pelanggaran rp
    JOIN pelanggaran p ON rp.pelanggaran_id = p.id
    WHERE rp.siswa_id = '$siswa_id'
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa Dashboard - Point Pelanggaran Siswa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
    <style>
        .stats-grid-siswa {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                <img src="../login_app/asset/gambar/images.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                <h2 style="font-size: 20px; color: #fff; margin: 0; padding: 0;">Panel Siswa</h2>
                <div style="color: #a0aec0; font-size: 14px; margin-top: 5px; font-weight: 500;">Sistem Poin Pelanggaran</div>
            </div>
            <ul>
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php"><i class="fas fa-history"></i> Riwayat Pelanggaran</a></li>
                <li style="margin-top: auto;">
                    <a href="../login_app/logout.php" style="color: #ff6b6b; padding: 15px; display: flex; align-items: center; gap: 10px; transition: all 0.3s ease;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Siswa</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <strong><?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></strong></span>
                </div>
            </div>

            <div style="background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 250px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; color: #333; border-bottom: 2px solid #3498db; padding-bottom: 5px; display: inline-block;">Profil Siswa</h3>
                    <table style="width: 100%; font-size: 14px;">
                        <tr><td style="padding: 5px 0; width: 120px; color: #666; font-weight: bold;">NIS</td><td>: <?php echo htmlspecialchars($username); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">Nama Lengkap</td><td>: <?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">Kelas</td><td>: <?php echo htmlspecialchars($data_siswa['kelas'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">Jurusan</td><td>: <?php echo htmlspecialchars($data_siswa['jurusan'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">No. Telepon</td><td>: <?php echo htmlspecialchars($data_siswa['no_telepon'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">Email</td><td>: <?php echo htmlspecialchars($data_siswa['email'] ?? '-'); ?></td></tr>
                    </table>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; color: #333; border-bottom: 2px solid #2ecc71; padding-bottom: 5px; display: inline-block;">Data Orang Tua / Wali</h3>
                    <table style="width: 100%; font-size: 14px;">
                        <tr><td style="padding: 5px 0; width: 120px; color: #666; font-weight: bold;">Nama</td><td>: <?php echo htmlspecialchars($data_siswa['nama_orangtua'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">No. Telepon</td><td>: <?php echo htmlspecialchars($data_siswa['telp_orangtua'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold;">Pekerjaan</td><td>: <?php echo htmlspecialchars($data_siswa['pekerjaan_orangtua'] ?? '-'); ?></td></tr>
                        <tr><td style="padding: 5px 0; color: #666; font-weight: bold; vertical-align: top;">Alamat</td><td style="padding: 5px 0; line-height: 1.4;">: <?php echo htmlspecialchars($data_siswa['alamat'] ?? '-'); ?></td></tr>
                    </table>
                </div>
            </div>

            <div style="background: white; padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #3498db;">
                <p style="color: #555; font-size: 14px; margin: 0;">Berikut adalah ringkasan data poin pelanggaran Anda di SMK TI Bali Global Denpasar.</p>
            </div>

            <div class="content">
                <!-- Statistik Cards -->
                <div class="stats-grid-siswa">
                    <div class="stat-card warning">
                        <h3>Total Pelanggaran</h3>
                        <div class="number"><?php echo $total_pelanggaran; ?></div>
                    </div>
                    <div class="stat-card danger">
                        <h3>Total Poin</h3>
                        <div class="number"><?php echo $total_poin; ?></div>
                    </div>
                </div>

                <!-- Riwayat Terbaru -->
                <h2>Riwayat Pelanggaran Terbaru Anda</h2>
                <?php if (count($recent_data) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($recent_data as $row): 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_pelanggaran']); ?></td>
                                    <td><span class="badge badge-warning"><?php echo $row['sanksi_poin']; ?> Poin</span></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_pelanggaran'] != '0000-00-00' ? $row['tanggal_pelanggaran'] : $row['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Anda belum memiliki riwayat pelanggaran. Pertahankan prestasimu!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
