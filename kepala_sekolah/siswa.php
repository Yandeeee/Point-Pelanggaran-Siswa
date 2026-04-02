<?php
session_start();
if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['admin', 'kepala_sekolah'])) {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

// Tambahkan kolom orang tua jika belum ada
$alter1 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS nama_orangtua VARCHAR(150) NULL";
$alter2 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS telp_orangtua VARCHAR(15) NULL";
$alter3 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS pekerjaan_orangtua VARCHAR(100) NULL";
$alter4 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS alamat TEXT NULL";
$alter5 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS jurusan VARCHAR(50) NULL";
mysqli_query($conn, $alter1);
mysqli_query($conn, $alter2);
mysqli_query($conn, $alter3);
mysqli_query($conn, $alter4);
mysqli_query($conn, $alter5);

// Debug: Cek struktur tabel siswa
$debug_query = mysqli_query($conn, "DESCRIBE siswa");
$debug_columns = [];
if ($debug_query) {
    while ($col = mysqli_fetch_assoc($debug_query)) {
        $debug_columns[] = $col['Field'];
    }
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Ambil data siswa
$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY created_at DESC");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    if ($action == 'add') {
        $nis = mysqli_real_escape_string($conn, $_POST['nis']);
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
        $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan'] ?? '');
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
        $nama_orangtua = mysqli_real_escape_string($conn, $_POST['nama_orangtua'] ?? '');
        $telp_orangtua = mysqli_real_escape_string($conn, $_POST['telp_orangtua'] ?? '');
        $pekerjaan_orangtua = mysqli_real_escape_string($conn, $_POST['pekerjaan_orangtua'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');

        $insert = mysqli_query($conn, "
            INSERT INTO siswa (nis, nama_siswa, kelas, jurusan, email, no_telepon, nama_orangtua, telp_orangtua, pekerjaan_orangtua, alamat, created_at, updated_at)
            VALUES ('$nis', '$nama_siswa', '$kelas', '$jurusan', '$email', '$no_telepon', '$nama_orangtua', '$telp_orangtua', '$pekerjaan_orangtua', '$alamat', NOW(), NOW())");

        if ($insert) {
            $message = "Data siswa berhasil ditambahkan!";
            header("Location: siswa.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id']);
        $nis = mysqli_real_escape_string($conn, $_POST['nis']);
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
        $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan'] ?? '');
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
        $nama_orangtua = mysqli_real_escape_string($conn, $_POST['nama_orangtua'] ?? '');
        $telp_orangtua = mysqli_real_escape_string($conn, $_POST['telp_orangtua'] ?? '');
        $pekerjaan_orangtua = mysqli_real_escape_string($conn, $_POST['pekerjaan_orangtua'] ?? '');
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');

        $update = mysqli_query($conn, "
            UPDATE siswa SET nis='$nis', nama_siswa='$nama_siswa', kelas='$kelas', jurusan='$jurusan', email='$email', no_telepon='$no_telepon', nama_orangtua='$nama_orangtua', telp_orangtua='$telp_orangtua', pekerjaan_orangtua='$pekerjaan_orangtua', alamat='$alamat', updated_at=NOW()
            WHERE id=$id ");

        if ($update) {
            $message = "Data siswa berhasil diperbarui!";
            header("Location: siswa.php");
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
    $edit_query = mysqli_query($conn, "SELECT * FROM siswa WHERE id=$id");
    if ($edit_query && mysqli_num_rows($edit_query) > 0) {
        $edit_data = mysqli_fetch_assoc($edit_query);
    }
}

// Handle delete
if ($action == 'delete' && isset($_GET['id']) && $_SESSION['role'] === 'admin') {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM siswa WHERE id=$id");
    if ($delete) {
        header("Location: siswa.php?message=deleted");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                <img src="../login_app/asset/gambar/images.png" alt="Logo" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 50%; padding: 5px; margin-bottom: 15px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                <h2 style="font-size: 20px; color: #fff; margin: 0; padding: 0;">Admin Panel</h2>
                <div style="color: #c4c8ceff; font-size: 14px; margin-top: 10px; font-weight: 500;">Sistem Poin Pelanggaran</div>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="riwayat_pelanggaran.php">Riwayat Pelanggaran</a></li>
                <li><a href="pelanggaran.php">Data Pelanggaran</a></li>
                <li><a href="guru.php">Data Guru</a></li>
                <li><a href="siswa.php" class="active">Data Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Data Siswa</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </div>
            </div>

            <div class="content">
                <?php if (($action == 'add' || $action == 'edit') && $_SESSION['role'] === 'admin'): ?>
                    <!-- Form Tambah/Edit -->
                    <h2><?php echo $action == 'add' ? 'Tambah Siswa Baru' : 'Edit Data Siswa'; ?></h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" style="max-width: 500px;">
                        <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>">
                        
                        <div class="form-group">
                            <label>NIS</label>
                            <input type="text" name="nis" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nis']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Nama Siswa</label>
                            <input type="text" name="nama_siswa" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_siswa']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <input type="text" name="kelas" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['kelas']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label>Jurusan</label>
                            <select name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="Rekayasa Perangkat Lunak" <?php echo ($edit_data && ($edit_data['jurusan'] ?? '') == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                                <option value="Desain Komunikasi Visual" <?php echo ($edit_data && ($edit_data['jurusan'] ?? '') == 'Desain Komunikasi Visual') ? 'selected' : ''; ?>>Desain Komunikasi Visual</option>
                                <option value="Teknik Komputer Jaringan" <?php echo ($edit_data && ($edit_data['jurusan'] ?? '') == 'Teknik Komputer Jaringan') ? 'selected' : ''; ?>>Teknik Komputer Jaringan</option>
                                <option value="Animasi" <?php echo ($edit_data && ($edit_data['jurusan'] ?? '') == 'Animasi') ? 'selected' : ''; ?>>Animasi</option>
                                <option value="Bisnis Digital" <?php echo ($edit_data && ($edit_data['jurusan'] ?? '') == 'Bisnis Digital') ? 'selected' : ''; ?>>Bisnis Digital</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="no_telepon" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['no_telepon']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Nama Orang Tua</label>
                            <input type="text" name="nama_orangtua" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_orangtua'] ?? '') : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>No. Telepon Orang Tua</label>
                            <input type="text" name="telp_orangtua" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['telp_orangtua'] ?? '') : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Pekerjaan Orang Tua</label>
                            <input type="text" name="pekerjaan_orangtua" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['pekerjaan_orangtua'] ?? '') : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" rows="3" required><?php echo $edit_data ? htmlspecialchars($edit_data['alamat'] ?? '') : ''; ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="siswa.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- Daftar Siswa -->

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] !== 'kepala_sekolah'): ?>
                    <div style="margin-bottom: 20px;">
                        <a href="siswa.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Siswa</a>
                    </div>
                    <?php endif; ?>

                    <?php if (count($data) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>

                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <?php if ($_SESSION['role'] !== 'kepala_sekolah'): ?>
                                    <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($data as $row): 
                                ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nis'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_siswa'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['kelas'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['jurusan'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['email'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['no_telepon'] ?? '-'); ?></td>
                                        <?php if ($_SESSION['role'] !== 'kepala_sekolah'): ?>
                                        <td>
                                            <a href="siswa.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-small">Edit</a>
                                            <a href="siswa.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Tidak ada data siswa</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
