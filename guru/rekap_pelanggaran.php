<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

$query = mysqli_query($conn, "
    SELECT s.id, s.nis, s.nama_siswa, s.kelas,
           COUNT(rp.id) as total_pelanggaran, 
           SUM(p.sanksi_poin) as total_poin
    FROM siswa s
    JOIN riwayat_pelanggaran rp ON s.id = rp.siswa_id
    JOIN pelanggaran p ON rp.pelanggaran_id = p.id
    WHERE s.deleted_at IS NULL
    GROUP BY s.id
    ORDER BY total_poin DESC
");

$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pelanggaran - Guru Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
    <style>
        .badge-danger { background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; }
        .badge-warning { background-color: #ffc107; color: black; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                <img src="../login_app/asset/gambar/images.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                <h2 style="font-size: 20px; color: #fff; margin: 0; padding: 0;">Guru Panel</h2>
                <div style="color: #c4c8ceff; font-size: 14px; margin-top: 10px; font-weight: 500;">Sistem Poin Pelanggaran</div>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php">Riwayat Pelanggaran</a></li>
                <li><a href="rekap_pelanggaran.php" class="active">Rekap Pelanggaran</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php" style="color: #fff;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Rekap Siswa Melanggar</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </div>
            </div>

            <div class="content">
                <div style="background: white; padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; border-left: 4px solid #2c3e50;">
                    <p style="color: #555; font-size: 14px; margin: 0;">Berikut adalah daftar siswa yang pernah melakukan pelanggaran, diurutkan berdasarkan total poin tertinggi.</p>
                </div>

                <?php if (count($data) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Total Frekuensi<br>Pelanggaran</th>
                                <th>Total Poin<br>Pelanggaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($data as $row): 
                                $poin = $row['total_poin'];
                                $badge_class = $poin >= 50 ? 'badge-danger' : 'badge-warning';
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nis']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                                    <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                                    <td><?php echo $row['total_pelanggaran']; ?> kali</td>
                                    <td><span class="badge <?php echo $badge_class; ?>"><?php echo $poin; ?> Poin</span></td>
                                    <td>
                                        <a href="riwayat_pelanggaran.php?siswa_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-small">Lihat Detail Riwayat</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Belum ada data siswa yang melakukan pelanggaran.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
