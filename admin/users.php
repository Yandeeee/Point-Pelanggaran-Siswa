<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_app/index.php");
    exit;
}
include "../login_app/database.php";
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$message = '';
$error = '';

// Ambil data users
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'add') {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $nama = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
        $kode_guru = isset($_POST['kode_guru']) ? mysqli_real_escape_string($conn, $_POST['kode_guru']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) : '';

        // Check if username exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $insert = mysqli_query($conn, "
                INSERT INTO users (username, password, nama, kode_guru, jenis_kelamin, email, role, created_at, updated_at)
                VALUES ('$username', '$password', '$nama', '$kode_guru', '$jenis_kelamin', '$email', '$role', NOW(), NOW())
            ");

            if ($insert) {
                $message = "User berhasil ditambahkan!";
                header("Location: users.php");
                exit;
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    } elseif ($action == 'edit') {
        $id = intval($_POST['id']);
        $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $nama = isset($_POST['nama']) ? mysqli_real_escape_string($conn, $_POST['nama']) : '';
        $kode_guru = isset($_POST['kode_guru']) ? mysqli_real_escape_string($conn, $_POST['kode_guru']) : '';
        $jenis_kelamin = isset($_POST['jenis_kelamin']) ? mysqli_real_escape_string($conn, $_POST['jenis_kelamin']) : '';

        $password_update = "";
        if (!empty($_POST['password'])) {
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $password_update = ", password='$hashed_password'";
        }

        $update = mysqli_query($conn, "
            UPDATE users SET nama='$nama', kode_guru='$kode_guru', jenis_kelamin='$jenis_kelamin', email='$email', role='$role' $password_update, updated_at=NOW()
            WHERE id=$id
        ");

        if ($update) {
            $message = "Data user berhasil diperbarui!";
            header("Location: users.php");
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
    $edit_query = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    if ($edit_query && mysqli_num_rows($edit_query) > 0) {
        $edit_data = mysqli_fetch_assoc($edit_query);
    }
}

// Handle delete
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prevent self-deletion
    if ($id == $_SESSION['user_id']) {
        $error = "Anda tidak dapat menghapus akun sendiri!";
    } else {
        $delete = mysqli_query($conn, "DELETE FROM users WHERE id=$id");
        if ($delete) {
            header("Location: users.php?message=deleted");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin Panel</title>
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
                <li><a href="guru.php">Guru</a></li>
                <li><a href="siswa.php">Siswa</a></li>
                <li><a href="surat.php">Cetak Laporan</a></li>
                <li><a href="users.php" class="active">Manajemen User</a></li>
                <li style="margin-top: auto;"><a href="../login_app/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Manajemen User</h1>
                <div class="header-actions">
                    <span style="color: #666; font-size: 14px;">Selamat datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                </div>
            </div>

            <div class="content">
                <?php if ($action == 'add' || $action == 'edit'): ?>
                    <!-- Form Tambah/Edit -->
                    <h2><?php echo $action == 'add' ? 'Tambah User Baru' : 'Edit Data User'; ?></h2>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" style="max-width: 500px;">
                        <input type="hidden" name="id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>">
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['username']) : ''; ?>" <?php echo $action == 'edit' ? 'readonly' : ''; ?> style="<?php echo $action == 'edit' ? 'background-color: #f0f0f0; cursor: not-allowed;' : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="<?php echo $edit_data && isset($edit_data['nama']) ? htmlspecialchars($edit_data['nama']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Kode Guru (Opsional)</label>
                            <input type="text" name="kode_guru" value="<?php echo $edit_data && isset($edit_data['kode_guru']) ? htmlspecialchars($edit_data['kode_guru']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?php echo $edit_data && isset($edit_data['jenis_kelamin']) && $edit_data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo $edit_data && isset($edit_data['jenis_kelamin']) && $edit_data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo $edit_data && isset($edit_data['email']) ? htmlspecialchars($edit_data['email']) : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label>Password <?php echo $action == 'edit' ? '<small style="color: #888;">(Kosongkan jika tidak ingin mengubah)</small>' : ''; ?></label>
                            <div style="position: relative; width: 100%; box-sizing: border-box;">
                                <input type="password" id="password" name="password" <?php echo $action == 'add' ? 'required' : ''; ?> style="box-sizing: border-box; width: 100%; padding-right: 40px;">
                                <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888; font-size: 16px;"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="admin" <?php echo $edit_data && $edit_data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="kepala_sekolah" <?php echo $edit_data && $edit_data['role'] == 'kepala_sekolah' ? 'selected' : ''; ?>>Kepala Sekolah</option>
                                <option value="waka_kesiswaan" <?php echo $edit_data && $edit_data['role'] == 'waka_kesiswaan' ? 'selected' : ''; ?>>Waka Kesiswaan</option>
                                <option value="guru" <?php echo $edit_data && $edit_data['role'] == 'guru' ? 'selected' : ''; ?>>Guru Mapel</option>
                                <option value="guru_bk" <?php echo $edit_data && $edit_data['role'] == 'guru_bk' ? 'selected' : ''; ?>>Guru BK</option>
                                <option value="siswa" <?php echo $edit_data && $edit_data['role'] == 'siswa' ? 'selected' : ''; ?>>Siswa</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="users.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- Daftar User -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div style="margin-bottom: 20px;">
                        <a href="users.php?action=add" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
                    </div>

                    <?php if (count($data) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>L/P</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
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
                                        <td><?php echo isset($row['nama']) && $row['nama'] !== '' ? htmlspecialchars($row['nama']) : '-'; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Laki-laki' ? 'L' : (isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'Perempuan' ? 'P' : '-'); ?></td>
                                        <td><span class="badge badge-info"><?php echo ucfirst(str_replace('_', ' ', $row['role'])); ?></span></td>
                                        <td><?php echo date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <a href="users.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning btn-small">Edit</a>
                                            <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Tidak ada data user</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }
    </script>
</body>
</html>
