<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

$username = $_SESSION['username'];
$query_user = mysqli_query($conn, "SELECT id, nama_siswa FROM siswa WHERE nis = '$username'");
$data_siswa = mysqli_fetch_assoc($query_user);

if(!$data_siswa) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location.href='../login_app/logout.php';</script>";
    exit;
}

$siswa_id = $data_siswa['id'];

// Pagination and Search
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = "WHERE rp.siswa_id = '$siswa_id'";
if ($search) {
    $where_clause .= " AND p.nama_pelanggaran LIKE '%$search%'";
}

// Get total for pagination
$total_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM riwayat_pelanggaran rp JOIN pelanggaran p ON rp.pelanggaran_id = p.id $where_clause");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

// Get data
$query = "
    SELECT rp.*, p.nama_pelanggaran, p.sanksi_poin
    FROM riwayat_pelanggaran rp
    JOIN pelanggaran p ON rp.pelanggaran_id = p.id
    $where_clause
    ORDER BY rp.created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - Point Pelanggaran Siswa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        tr:hover {
            background-color: #fcfcfc;
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
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php" class="active"><i class="fas fa-history"></i> Riwayat Pelanggaran</a></li>
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
                <h1>Riwayat Pelanggaran</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <strong><?php echo htmlspecialchars($data_siswa['nama_siswa']); ?></strong></span>
                </div>
            </div>

            <div class="content">
                <div class="table-container" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                        <h2 style="margin: 0;">Daftar Pelanggaran Anda</h2>
                        <form method="GET" action="" style="display: flex; gap: 10px;">
                            <input type="text" name="search" placeholder="Cari pelanggaran..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; min-width: 250px; outline: none;">
                            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;"><i class="fas fa-search"></i> Cari</button>
                            <?php if($search): ?>
                                <a href="riwayat_pelanggaran.php" class="btn btn-secondary" style="padding: 10px 20px; background: #95a5a6; color: white; text-decoration: none; border-radius: 5px;">Reset</a>
                            <?php endif; ?>
                        </form>
                    </div>

                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <div style="overflow-x: auto;">
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
                                    $no = $offset + 1;
                                    while($row = mysqli_fetch_assoc($result)): 
                                    ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_pelanggaran']); ?></td>
                                            <td><span class="badge badge-warning" style="background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?php echo $row['sanksi_poin']; ?> Poin</span></td>
                                            <td><?php echo date('d-m-Y', strtotime($row['tanggal_pelanggaran'] != '0000-00-00' ? $row['tanggal_pelanggaran'] : $row['created_at'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <div class="pagination" style="display: flex; gap: 5px; margin-top: 20px; justify-content: flex-end;">
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search='.$search : ''; ?>" 
                                   class="page-link <?php echo $page == $i ? 'active' : ''; ?>"
                                   style="padding: 8px 12px; border: 1px solid #3498db; color: <?php echo $page == $i ? 'white' : '#3498db'; ?>; background: <?php echo $page == $i ? '#3498db' : 'white'; ?>; text-decoration: none; border-radius: 4px; transition: all 0.3s ease;">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="empty-state" style="text-align: center; padding: 40px; color: #666;">
                            <i class="fas fa-check-circle" style="font-size: 48px; color: #2ecc71; margin-bottom: 15px;"></i>
                            <p style="font-size: 16px; margin: 0;">Anda belum memiliki riwayat pelanggaran. Pertahankan prestasimu!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
