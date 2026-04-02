<?php
session_start();
if (!isset($_SESSION['login']) || !in_array($_SESSION['role'], ['admin', 'waka_kesiswaan'])) {
    header("Location: ../login_app/index.php");
    exit;
}

include "../login_app/database.php";

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$error = '';
$success = '';

// Handle Delete
if (isset($_GET['delete']) && $_SESSION['role'] === 'admin') {
    $id = (int)$_GET['delete'];
    $delete_query = mysqli_query($conn, "DELETE FROM guru WHERE id = $id");
    if ($delete_query) {
        $success = "Data guru berhasil dihapus.";
    } else {
        $error = "Gagal menghapus data guru: " . mysqli_error($conn);
    }
}

// Handle Add/Edit Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kode_guru = mysqli_real_escape_string($conn, $_POST['kode_guru']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = (int)$_POST['id'];
        
        $update_sql = "UPDATE guru SET 
                      nama = '$nama', 
                      kode_guru = '$kode_guru', 
                      jenis_kelamin = '$jenis_kelamin', 
                      email = '$email', 
                      role = '$role',
                      username = '$username'";
                      
        // Handle password update if provided
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $update_sql .= ", password = '$password'";
        }
        
        $update_sql .= " WHERE id = $id";
        
        if (mysqli_query($conn, $update_sql)) {
            $success = "Data guru berhasil diupdate.";
            $action = 'list';
        } else {
            $error = "Gagal mengupdate data: " . mysqli_error($conn);
        }
    } else {
        // Insert
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $insert_sql = "INSERT INTO guru (username, password, nama, kode_guru, jenis_kelamin, email, role) 
                       VALUES ('$username', '$password', '$nama', '$kode_guru', '$jenis_kelamin', '$email', '$role')";
                       
        if (mysqli_query($conn, $insert_sql)) {
            $success = "Data guru berhasil ditambahkan.";
            $action = 'list';
        } else {
            $error = "Gagal menambah data: " . mysqli_error($conn);
        }
    }
}

// Fetch all guru
$query_guru = mysqli_query($conn, "SELECT * FROM guru ORDER BY nama ASC");
$guru_list = [];
if ($query_guru) {
    while ($row = mysqli_fetch_assoc($query_guru)) {
        $guru_list[] = $row;
    }
}

// Fetch specific guru for editing
$edit_data = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query_edit = mysqli_query($conn, "SELECT * FROM guru WHERE id = $id");
    if ($query_edit && mysqli_num_rows($query_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($query_edit);
    } else {
        $action = 'list';
        $error = "Data guru tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Guru - Point Pelanggaran Siswa</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../login_app/asset/css/style-main.css">
    <style>
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
    </style>
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
                <li><a href="guru.php" class="active">Data Guru</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Data Guru</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </div>
            </div>

            <div class="content">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <?php if ($action === 'list'): ?>
                    <?php if ($_SESSION['role'] !== 'waka_kesiswaan'): ?>
                    <div style="margin-bottom: 20px;">
                        <a href="?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Guru</a>
                    </div>
                    <?php endif; ?>
                    
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Guru</th>
                                    <th>Nama</th>
                                    <th>L/P</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <?php if ($_SESSION['role'] !== 'waka_kesiswaan'): ?>
                                    <th>Aksi</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($guru_list) > 0): ?>
                                    <?php $no = 1; foreach ($guru_list as $guru): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($guru['kode_guru']); ?></td>
                                            <td><?php echo htmlspecialchars($guru['nama']); ?></td>
                                            <td><?php echo htmlspecialchars($guru['jenis_kelamin'] == 'Laki-laki' ? 'L' : 'P'); ?></td>
                                            <td><?php echo htmlspecialchars($guru['email']); ?></td>
                                            <td><span class="badge badge-info"><?php echo htmlspecialchars(ucfirst($guru['role'])); ?></span></td>
                                            <?php if ($_SESSION['role'] !== 'waka_kesiswaan'): ?>
                                            <td>
                                                <a href="?action=edit&id=<?php echo $guru['id']; ?>" class="btn btn-warning btn-small"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="?delete=<?php echo $guru['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus guru ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data guru.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif (($action === 'add' || $action === 'edit') && $_SESSION['role'] === 'admin'): ?>
                    <div class="form-container">
                        <h3><?php echo $action === 'add' ? 'Tambah Guru Baru' : 'Edit Data Guru'; ?></h3>
                        <br>
                        <form method="POST" action="guru.php">
                            <?php if ($action === 'edit' && $edit_data): ?>
                                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="kode_guru">Kode Guru</label>
                                <input type="text" id="kode_guru" name="kode_guru" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['kode_guru']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" required>
                                    <option value="Laki-laki" <?php echo ($edit_data && $edit_data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php echo ($edit_data && $edit_data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['email']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="role">Role (Peran di Aplikasi)</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="guru" <?php echo ($edit_data && $edit_data['role'] == 'guru') ? 'selected' : ''; ?>>Guru Umum</option>
                                    <option value="bk" <?php echo ($edit_data && $edit_data['role'] == 'bk') ? 'selected' : ''; ?>>Guru BK</option>
                                    <option value="admin" <?php echo ($edit_data && $edit_data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                            
                            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">
                            <h4>Akun Login Guru</h4>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo $edit_data ? htmlspecialchars($edit_data['username']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password <?php echo $action === 'edit' ? '(Kosongkan jika tidak ingin merubah password)' : ''; ?></label>
                                <input type="password" id="password" name="password" class="form-control" <?php echo $action === 'add' ? 'required' : ''; ?>>
                            </div>

                            <div style="margin-top: 25px;">
                                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Data</button>
                                <a href="guru.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
