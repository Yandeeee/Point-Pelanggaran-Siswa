<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

// Debug: Cek struktur tabel pelanggaran
$debug_query = mysqli_query($conn, "DESCRIBE pelanggaran");
$debug_columns = [];
if ($debug_query) {
    while ($col = mysqli_fetch_assoc($debug_query)) {
        $debug_columns[] = $col['Field'];
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Ambil data pelanggaran
$query = mysqli_query($conn, "SELECT * FROM pelanggaran ORDER BY created_at DESC");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
} else {
    $error = "Error query database: " . mysqli_error($conn);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'add') {
        $kode_pelanggaran = mysqli_real_escape_string($conn, $_POST['kode_pelanggaran']);
        $nama_pelanggaran = mysqli_real_escape_string($conn, $_POST['nama_pelanggaran']);
        $sanksi_poin = intval($_POST['sanksi_poin']);
        $deskripsi_sanksi = mysqli_real_escape_string($conn, $_POST['deskripsi_sanksi']);

        $insert = mysqli_query($conn, "
            INSERT INTO pelanggaran (kode_pelanggaran, nama_pelanggaran, sanksi_poin, deskripsi_sanksi, created_at, updated_at)
            VALUES ('$kode_pelanggaran', '$nama_pelanggaran', $sanksi_poin, '$deskripsi_sanksi', NOW(), NOW())
        ");

        if ($insert) {
            $message = "Data pelanggaran berhasil ditambahkan!";
            header("Location: pelanggaran.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id']);
        $kode_pelanggaran = mysqli_real_escape_string($conn, $_POST['kode_pelanggaran']);
        $nama_pelanggaran = mysqli_real_escape_string($conn, $_POST['nama_pelanggaran']);
        $sanksi_poin = intval($_POST['sanksi_poin']);
        $deskripsi_sanksi = mysqli_real_escape_string($conn, $_POST['deskripsi_sanksi']);

        $update = mysqli_query($conn, "
            UPDATE pelanggaran SET kode_pelanggaran='$kode_pelanggaran', nama_pelanggaran='$nama_pelanggaran', sanksi_poin=$sanksi_poin, deskripsi_sanksi='$deskripsi_sanksi', updated_at=NOW()
            WHERE id=$id
        ");

        if ($update) {
            $message = "Data pelanggaran berhasil diperbarui!";
            header("Location: pelanggaran.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Get edit data
$edit_data = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $edit_query = mysqli_query($conn, "SELECT * FROM pelanggaran WHERE id=$id");
    if ($edit_query && mysqli_num_rows($edit_query) > 0) {
        $edit_data = mysqli_fetch_assoc($edit_query);
    }
}

// Handle delete
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM pelanggaran WHERE id=$id");
    if ($delete) {
        header("Location: pelanggaran.php?message=deleted");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggaran - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php">Riwayat Pelanggaran</a></li>
                <li><a href="pelanggaran.php" class="active">Data Pelanggaran</a></li>
                <li><a href="guru.php">Guru</a></li>
                <li><a href="siswa.php">Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Data Pelanggaran</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['username'] == 'guru1' ? 'Drs. I Gusti Made Murjana,M.Pd' : $_SESSION['username']); ?></span>
                </div>
            </div>

            <div class="content">
                <?php if ($action == 'add' || $action == 'edit'): ?>
                    <!-- Form Tambah/Edit -->
                    <h2><?php echo $action == 'add' ? 'Tambah Pelanggaran Baru' : 'Edit Data Pelanggaran'; ?></h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" style="max-width: 500px;">
                        <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>">
                        
                        <div class="form-group">
                            <label>Kode Pelanggaran</label>
                            <input type="text" name="kode_pelanggaran" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['kode_pelanggaran']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Nama Pelanggaran</label>
                            <input type="text" name="nama_pelanggaran" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_pelanggaran']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Poin Sanksi</label>
                            <input type="number" name="sanksi_poin" required value="<?php echo $edit_data ? $edit_data['sanksi_poin'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Deskripsi Sanksi</label>
                            <textarea name="deskripsi_sanksi" required><?php echo $edit_data ? htmlspecialchars($edit_data['deskripsi_sanksi']) : ''; ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="pelanggaran.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- Daftar Pelanggaran -->

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div style="margin-bottom: 20px;">
                        <a href="pelanggaran.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pelanggaran</a>
                    </div>

                    <?php if (count($data) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
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
                                        <td><span class="badge badge-warning"><?php echo ($row['sanksi_poin'] ?? '-'); ?> Poin</span></td>
                                        <td><?php echo htmlspecialchars($row['deskripsi_sanksi'] ?? '-'); ?></td>
                                        <td>
                                            <a href="pelanggaran.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-small">Edit</a>
                                            <a href="pelanggaran.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Tidak ada data pelanggaran</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
