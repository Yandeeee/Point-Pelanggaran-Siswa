<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "database.php";

// Debug: Cek struktur tabel pelanggaran
$debug_query = mysqli_query($conn, "DESCRIBE pelanggaran");
$debug_columns = [];
if ($debug_query) {
    while ($col = mysqli_fetch_assoc($debug_query)) {
        $debug_columns[] = $col['Field'];
    }
}

// Ambil data pelanggaran dari database
$query = mysqli_query($conn, "SELECT * FROM pelanggaran WHERE deleted_at IS NULL ORDER BY created_at DESC");
$data = [];
$error = "";
if ($query) {
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row;
        }
    } else {
        $error = "Tidak ada data pelanggaran di database";
    }
} else {
    $error = "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggaran - Point Pelanggaran Siswa</title>
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
            /* background: linear-gradient(135deg, #36d6a6 0%, #36add4 100%); */
            background-color: #1b82c7;
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

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state img {
            width: 100px;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Menu</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="pelanggaran.php" class="active">Data Pelanggaran</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Data Pelanggaran Siswa</h1>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <div class="content">
                <h2>Daftar Pelanggaran</h2>
                
                <!-- Debug Info -->
                <div style="background-color: #e7f3ff; color: #0c5a9e; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 12px;">
                    <strong>Kolom yang tersedia di tabel:</strong> <?php echo implode(', ', $debug_columns); ?>
                </div>
                
                <?php if (isset($error) && !empty($error)): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (count($data) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pelanggaran</th>
                                <th>Nama Pelanggaran</th>
                                <th>Deskripsi Pelanggaran</th>
                                <th>Point Sanksi</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach ($data as $row): 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><span class="badge badge-danger"><?php echo htmlspecialchars($row['kode_pelanggaran'] ?? '-'); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['nama_pelanggaran'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($row['deskripsi_sanksi'] ?? '-'); ?></td>
                                    <td><span class="badge badge-warning"><?php echo ($row['sanksi_poin'] ?? '-'); ?> Poin</span></td>
                                    <td><?php echo isset($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : '-'; ?></td>
                                    <td><?php echo isset($row['updated_at']) ? date('d-m-Y H:i', strtotime($row['updated_at'])) : '-'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Tidak ada data pelanggaran</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
