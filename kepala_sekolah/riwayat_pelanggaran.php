<?php
session_start();
if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['admin', 'kepala_sekolah'])) {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Ambil data riwayat pelanggaran
$query = mysqli_query($conn, "
    SELECT rp.*, s.nama_siswa, p.nama_pelanggaran, p.sanksi_poin
    FROM riwayat_pelanggaran rp
    JOIN siswa s ON rp.siswa_id = s.id
    JOIN pelanggaran p ON rp.pelanggaran_id = p.id
    ORDER BY rp.created_at DESC
");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}

// Ambil data siswa untuk dropdown
$query_siswa = mysqli_query($conn, "SELECT id, nama_siswa FROM siswa WHERE deleted_at IS NULL ORDER BY nama_siswa");
$siswa_list = [];
if ($query_siswa) {
    while ($row = mysqli_fetch_assoc($query_siswa)) {
        $siswa_list[] = $row;
    }
}

// Ambil data pelanggaran untuk dropdown
$query_pelanggaran = mysqli_query($conn, "SELECT id, nama_pelanggaran, sanksi_poin FROM pelanggaran WHERE deleted_at IS NULL ORDER BY nama_pelanggaran");
$pelanggaran_list = [];
if ($query_pelanggaran) {
    while ($row = mysqli_fetch_assoc($query_pelanggaran)) {
        $pelanggaran_list[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] === 'admin') {
    if ($action == 'add') {
        $siswa_id = intval($_POST['siswa_id']);
        $pelanggaran_ids = isset($_POST['pelanggaran_id']) ? $_POST['pelanggaran_id'] : [];
        if (!is_array($pelanggaran_ids)) {
            $pelanggaran_ids = [$pelanggaran_ids];
        }
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');

        $success = true;
        foreach ($pelanggaran_ids as $p_id) {
            $p_id = intval($p_id);
            $insert = mysqli_query($conn, "
                INSERT INTO riwayat_pelanggaran (siswa_id, pelanggaran_id, keterangan, created_at, updated_at)
                VALUES ($siswa_id, $p_id, '$keterangan', NOW(), NOW())
            ");
            if (!$insert) {
                $success = false;
                $error = "Error: " . mysqli_error($conn);
                break;
            }
        }

        if ($success) {
            $message = "Riwayat pelanggaran berhasil dicatat!";
            header("Location: riwayat_pelanggaran.php");
            exit;
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id']);
        $siswa_id = intval($_POST['siswa_id']);
        $pelanggaran_ids = isset($_POST['pelanggaran_id']) ? $_POST['pelanggaran_id'] : [];
        if (!is_array($pelanggaran_ids)) {
            $pelanggaran_ids = [$pelanggaran_ids];
        }
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');

        // Fitur Edit: Update ID yang sedang diedit dengan pelanggaran pertama
        // lalu sisa array-nya akan di insert sebagai pelanggaran tambahan untuk siswa tersebut.
        $success = true;
        if (count($pelanggaran_ids) > 0) {
            $first_p_id = intval($pelanggaran_ids[0]);
            $update = mysqli_query($conn, "
                UPDATE riwayat_pelanggaran SET siswa_id=$siswa_id, pelanggaran_id=$first_p_id, keterangan='$keterangan', updated_at=NOW()
                WHERE id=$id
            ");

            if (!$update) {
                $success = false;
                $error = "Error Update: " . mysqli_error($conn);
            } else {
                // Untuk pilihan ekstra, kita Insert sebagai pelanggaran terpisah
                for ($i = 1; $i < count($pelanggaran_ids); $i++) {
                    $p_id = intval($pelanggaran_ids[$i]);
                    $insert_extra = mysqli_query($conn, "
                        INSERT INTO riwayat_pelanggaran (siswa_id, pelanggaran_id, keterangan, created_at, updated_at)
                        VALUES ($siswa_id, $p_id, '$keterangan', NOW(), NOW())
                    ");
                    if (!$insert_extra) {
                        $success = false;
                        $error = "Error Insert Extra: " . mysqli_error($conn);
                        break;
                    }
                }
            }
        } else {
            $error = "Mohon pilih minimal satu pelanggaran.";
            $success = false;
        }

        if ($success) {
            $message = "Data riwayat berhasil diperbarui / ditambahkan ekstra!";
            header("Location: riwayat_pelanggaran.php");
            exit;
        }
    }
}

// Get edit data
$edit_data = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $edit_query = mysqli_query($conn, "SELECT * FROM riwayat_pelanggaran WHERE id=$id");
    if ($edit_query && mysqli_num_rows($edit_query) > 0) {
        $edit_data = mysqli_fetch_assoc($edit_query);
    }
}

// Handle delete
if ($action == 'delete' && isset($_GET['id']) && $_SESSION['role'] === 'admin') {
    $id = intval($_GET['id']);
    $delete = mysqli_query($conn, "DELETE FROM riwayat_pelanggaran WHERE id=$id");
    if ($delete) {
        header("Location: riwayat_pelanggaran.php?message=deleted");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pelanggaran - Admin Panel</title>
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
                <li><a href="riwayat_pelanggaran.php" class="active">Riwayat Pelanggaran</a></li>
                <li><a href="pelanggaran.php">Data Pelanggaran</a></li>
                <li><a href="guru.php">Data Guru</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Riwayat Pelanggaran</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </div>
            </div>

            <div class="content">
                <?php if (($action == 'add' || $action == 'edit') && $_SESSION['role'] === 'admin'): ?>
                    <!-- Form Tambah/Edit -->
                    <h2><?php echo $action == 'add' ? 'Catat Pelanggaran Siswa' : 'Edit Pelanggaran Siswa'; ?></h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" style="max-width: 500px;">
                        <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>">
                        
                        <div class="form-group">
                            <label>Siswa</label>
                            <select name="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($siswa_list as $siswa): ?>
                                    <option value="<?php echo $siswa['id']; ?>" <?php echo $edit_data && $edit_data['siswa_id'] == $siswa['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($siswa['nama_siswa']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pelanggaran (Pilih Beberapa)</label>
                            <select name="pelanggaran_id[]" id="pelanggaranSelect" multiple required style="height: 120px;" onchange="hitungTotalPoin()">
                                <?php foreach ($pelanggaran_list as $pelanggaran): ?>
                                    <option value="<?php echo $pelanggaran['id']; ?>" 
                                        data-poin="<?php echo $pelanggaran['sanksi_poin']; ?>"
                                        <?php echo ($edit_data && $edit_data['pelanggaran_id'] == $pelanggaran['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($pelanggaran['nama_pelanggaran']); ?> (<?php echo $pelanggaran['sanksi_poin']; ?> Poin)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted" style="display:block; margin-top:5px;">
                                <?php if ($action == 'edit') { echo "Tips: Anda dapat menambahkan pelanggaran lain saat mengedit mode. Data akan ditambahkan terpisah."; } else { echo "Tahan tombol CTRL (Windows) atau CMD (Mac) saat klik untuk memilih lebih dari satu pelanggaran."; } ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Total Poin Pelanggaran Saat Ini</label>
                            <input type="text" id="totalPoinDisplay" class="form-control" value="0 Poin" readonly style="background-color: #f0f0f0; font-weight: bold; color: #d9534f; cursor: not-allowed;">
                        </div>
                        <script>
                            function hitungTotalPoin() {
                                let select = document.getElementById('pelanggaranSelect');
                                let total = 0;
                                for (let i = 0; i < select.options.length; i++) {
                                    if (select.options[i].selected) {
                                        total += parseInt(select.options[i].getAttribute('data-poin')) || 0;
                                    }
                                }
                                document.getElementById('totalPoinDisplay').value = total + ' Poin';
                            }
                            // Hitung default saat halaman edit termuat
                            window.onload = hitungTotalPoin;
                        </script>

                        <div class="form-group">
                            <label>Keterangan (Opsional)</label>
                            <textarea name="keterangan" placeholder="Masukkan keterangan tambahan..."><?php echo $edit_data ? htmlspecialchars($edit_data['keterangan']) : ''; ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="riwayat_pelanggaran.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- Daftar Riwayat -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] !== 'kepala_sekolah'): ?>
                    <div style="margin-bottom: 20px;">
                        <a href="riwayat_pelanggaran.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Catat Pelanggaran</a>
                    </div>
                    <?php endif; ?>

                    <?php if (count($data) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Tanggal Catat</th>
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
                                        <td><?php echo htmlspecialchars($row['nama_siswa']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pelanggaran']); ?></td>
                                        <td><span class="badge badge-warning"><?php echo $row['sanksi_poin']; ?> Poin</span></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                        <?php if ($_SESSION['role'] !== 'kepala_sekolah'): ?>
                                        <td>
                                            <a href="riwayat_pelanggaran.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-small">Edit</a>
                                            <a href="riwayat_pelanggaran.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Tidak ada riwayat pelanggaran</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
