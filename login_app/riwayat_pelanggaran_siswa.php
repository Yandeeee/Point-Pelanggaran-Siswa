<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "database.php";

// Ambil ID siswa dari URL atau session
$siswa_id = 1 ($_GET['siswa_id']) ? intval($_GET['siswa_id']) : null;
$siswa_data = null;
$pelanggaran_data = [];
$total_poin = 0;

if ($siswa_id) {
    // Ambil data siswa
    $query_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id = $siswa_id");
    if ($query_siswa && mysqli_num_rows($query_siswa) > 0) {
        $siswa_data = mysqli_fetch_assoc($query_siswa);
    }

    // Ambil riwayat pelanggaran siswa
    $query_pelanggaran = mysqli_query($conn, "
        SELECT p.*, rp.created_at as tanggal_pelanggaran
        FROM riwayat_pelanggaran rp
        JOIN pelanggaran p ON rp.pelanggaran_id = p.id
        WHERE rp.siswa_id = $siswa_id AND p.deleted_at IS NULL
        ORDER BY rp.created_at DESC
    ");

    if ($query_pelanggaran) {
        while ($row = mysqli_fetch_assoc($query_pelanggaran)) {
            $pelanggaran_data[] = $row;
            $total_poin += $row['sanksi_poin'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran Siswa - Point Pelanggaran Siswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar ul li a.active {
            background-color: rgba(255, 255, 255, 0.3);
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
        }

        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .content h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .siswa-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: bold;
        }

        .poin-total {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #667eea;
            color: white;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .no-data {
            background-color: #e7d4f5;
            color: #712f7d;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .siswa-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                <img src="asset/gambar/images.png" alt="Logo" style="width: 80px; height: 80px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                <h2 style="font-size: 20px; color: #fff; margin: 0; padding: 0;">Kepsek Panel</h2>
                <div style="color: #a0aec0; font-size: 14px; margin-top: 5px; font-weight: 500;">Sistem Poin Pelanggaran</div>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="pelanggaran.php">Data Pelanggaran</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Riwayat Pelanggaran Siswa</h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <div class="content">
                <a href="siswa.php" class="back-btn">← Kembali</a>

                <?php if ($siswa_data): ?>
                    <h2><?php echo htmlspecialchars($siswa_data['nama_siswa']); ?></h2>
                    
                    <div class="siswa-info">
                        <div class="info-item">
                            <span class="info-label">NIS</span>
                            <span class="info-value"><?php echo htmlspecialchars($siswa_data['nis']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas</span>
                            <span class="info-value"><?php echo htmlspecialchars($siswa_data['kelas']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?php echo htmlspecialchars($siswa_data['email']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon</span>
                            <span class="info-value"><?php echo htmlspecialchars($siswa_data['no_telepon']); ?></span>
                        </div>
                    </div>

                    <?php if (count($pelanggaran_data) > 0): ?>
                        <div class="poin-total">
                            Total Poin Pelanggaran: <span style="font-size: 24px;"><?php echo $total_poin; ?></span> Poin
                        </div>

                        <h3 style="margin-bottom: 15px;">Riwayat Pelanggaran</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pelanggaran</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Poin Sanksi</th>
                                    <th>Deskripsi Sanksi</th>
                                    <th>Tanggal Pelanggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($pelanggaran_data as $row): 
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><span class="badge badge-danger"><?php echo htmlspecialchars($row['kode_pelanggaran']); ?></span></td>
                                        <td><?php echo htmlspecialchars($row['nama_pelanggaran']); ?></td>
                                        <td><span class="badge badge-warning"><?php echo $row['sanksi_poin']; ?> Poin</span></td>
                                        <td><?php echo htmlspecialchars($row['deskripsi_sanksi']); ?></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($row['tanggal_pelanggaran'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="no-data">
                            <p>Siswa ini tidak memiliki riwayat pelanggaran</p>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state">
                        <p>Silakan pilih siswa terlebih dahulu atau siswa tidak ditemukan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
