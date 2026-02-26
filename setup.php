<!DOCTYPE html>
<html>
<head>
    <title>Setup Database</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        button { padding: 10px 20px; font-size: 16px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #45a049; }
        .success { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
        .info { background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Setup Database Point Pelanggaran Siswa</h2>
        <p>Klik tombol di bawah untuk membuat/reset semua tabel database</p>
        
        <form method="POST">
            <button type="submit" name="setup" value="1">▶ Jalankan Setup Database</button>
        </form>
        
        <div class="info">
            <strong>Informasi:</strong><br>
            Akun admin default: <br>
            Username: <strong>admin</strong><br>
            Password: <strong>admin123</strong>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {
            $host = "localhost";
            $user = "root";
            $pass = "";
            $db   = "point_pelanggaran_siswa";

            $conn = mysqli_connect($host, $user, $pass, $db);

            if (!$conn) {
                die("<p class='error'>✗ Koneksi database gagal: " . mysqli_connect_error() . "</p>");
            }

            // Disable foreign key checks to allow dropping tables with constraints
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

            echo "<h3>Hasil Setup:</h3>";

            // Create users table
            $sql_create_users = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if (mysqli_query($conn, $sql_create_users)) {
                echo "<p class='success'>✓ Tabel users berhasil dibuat</p>";
            } else {
                echo "<p class='error'>✗ Error tabel users: " . mysqli_error($conn) . "</p>";
            }

            // Delete existing admin user if exists
            mysqli_query($conn, "DELETE FROM users WHERE username='admin'");

            // Hash password
            $password_hash = password_hash("admin123", PASSWORD_BCRYPT);

            // Insert sample user
            $sql_insert = "INSERT INTO users (username, password, role) VALUES ('admin', '$password_hash', 'admin')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "<p class='success'>✓ User admin berhasil dibuat</p>";
            } else {
                echo "<p class='error'>✗ Error: " . mysqli_error($conn) . "</p>";
            }

            // Drop existing siswa table to recreate it cleanly
            mysqli_query($conn, "DROP TABLE IF EXISTS siswa");

            // Create siswa table
            $sql_create_siswa = "CREATE TABLE IF NOT EXISTS siswa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nis VARCHAR(20) UNIQUE NOT NULL,
                nama_siswa VARCHAR(100) NOT NULL,
                kelas VARCHAR(10) NOT NULL,
                email VARCHAR(100),
                no_telepon VARCHAR(15),
                nama_orangtua VARCHAR(150),
                telp_orangtua VARCHAR(15),
                pekerjaan_orangtua VARCHAR(100),
                alamat TEXT,
                deleted_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

            if (mysqli_query($conn, $sql_create_siswa)) {
                echo "<p class='success'>✓ Tabel siswa berhasil dibuat</p>";
            } else {
                echo "<p class='error'>✗ Error tabel siswa: " . mysqli_error($conn) . "</p>";
            }

            // Delete existing data
            mysqli_query($conn, "DELETE FROM siswa");

            // Insert sample data
            $sql_insert_siswa = "INSERT INTO siswa (nis, nama_siswa, kelas, email, no_telepon, created_at, updated_at) VALUES 
            ('001', 'Ahmad Rizki', 'X-A', 'ahmad@email.com', '08123456789', NOW(), NOW()),
            ('002', 'Siti Nurhaliza', 'X-A', 'siti@email.com', '08123456790', NOW(), NOW()),
            ('003', 'Budi Santoso', 'X-B', 'budi@email.com', '08123456791', NOW(), NOW()),
            ('004', 'Ani Wijaya', 'X-B', 'ani@email.com', '08123456792', NOW(), NOW()),
            ('005', 'Rika Septiana', 'X-C', 'rika@email.com', '08123456793', NOW(), NOW())";

            if (mysqli_query($conn, $sql_insert_siswa)) {
                echo "<p class='success'>✓ Data siswa berhasil ditambahkan (5 data)</p>";
            } else {
                echo "<p class='error'>✗ Error insert data siswa: " . mysqli_error($conn) . "</p>";
            }

            // Drop existing pelanggaran table to recreate it cleanly
            mysqli_query($conn, "DROP TABLE IF EXISTS pelanggaran");

            // Create pelanggaran table
            $sql_create_pelanggaran = "CREATE TABLE IF NOT EXISTS pelanggaran (
                id INT AUTO_INCREMENT PRIMARY KEY,
                kode_pelanggaran VARCHAR(10) UNIQUE NOT NULL,
                nama_pelanggaran VARCHAR(100) NOT NULL,
                sanksi_poin INT NOT NULL,
                deskripsi_sanksi TEXT,
                deleted_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

            if (mysqli_query($conn, $sql_create_pelanggaran)) {
                echo "<p class='success'>✓ Tabel pelanggaran berhasil dibuat</p>";
            } else {
                echo "<p class='error'>✗ Error tabel pelanggaran: " . mysqli_error($conn) . "</p>";
            }

            // Drop existing riwayat_pelanggaran table to recreate it cleanly
            mysqli_query($conn, "DROP TABLE IF EXISTS riwayat_pelanggaran");

            // Create riwayat_pelanggaran table
            $sql_create_riwayat = "CREATE TABLE IF NOT EXISTS riwayat_pelanggaran (
                id INT AUTO_INCREMENT PRIMARY KEY,
                siswa_id INT NOT NULL,
                pelanggaran_id INT NOT NULL,
                tanggal_pelanggaran DATE NOT NULL,
                keterangan TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
                FOREIGN KEY (pelanggaran_id) REFERENCES pelanggaran(id) ON DELETE CASCADE
            )";

            if (mysqli_query($conn, $sql_create_riwayat)) {
                echo "<p class='success'>✓ Tabel riwayat_pelanggaran berhasil dibuat</p>";
            } else {
                echo "<p class='error'>✗ Error tabel riwayat_pelanggaran: " . mysqli_error($conn) . "</p>";
            }

            // Insert default pelanggaran data
            $data_pelanggaran = [
                ['P001', 'Tidak Masuk Tanpa Keterangan', 10, 'Siswa tidak hadir dan tidak memberi keterangan'],
                ['P002', 'Datang Terlambat', 5, 'Siswa datang ke sekolah lebih dari 15 menit setelah jam masuk'],
                ['P003', 'Tidak Mengerjakan PR', 8, 'Siswa tidak mengumpulkan pekerjaan rumah yang diberikan guru'],
                ['P004', 'Menyontek', 15, 'Siswa ketahuan menyontek saat ujian atau kuis'],
                ['P005', 'Tidak Pakai Seragam Lengkap', 5, 'Siswa tidak memakai seragam sesuai peraturan'],
                ['P006', 'Berambut Panjang/Tidak Rapi', 5, 'Siswa memiliki gaya rambut yang tidak sesuai peraturan'],
                ['P007', 'Mengganggu Pelajaran', 10, 'Siswa mengganggu jalannya proses pembelajaran di kelas'],
                ['P008', 'Bicara Tidak Sopan', 12, 'Siswa berbicara tidak sopan kepada guru atau teman'],
                ['P009', 'Membawa HP ke Sekolah', 8, 'Siswa membawa handphone ke lingkungan sekolah'],
                ['P010', 'Tidur di Kelas', 7, 'Siswa tertidur atau tidak memperhatikan saat proses belajar'],
            ];

            foreach ($data_pelanggaran as $pelanggaran) {
                $kode = $pelanggaran[0];
                $nama = $pelanggaran[1];
                $poin = $pelanggaran[2];
                $deskripsi = $pelanggaran[3];
                
                mysqli_query($conn, "
                    INSERT INTO pelanggaran (kode_pelanggaran, nama_pelanggaran, sanksi_poin, deskripsi_sanksi, created_at, updated_at)
                    VALUES ('$kode', '$nama', $poin, '$deskripsi', NOW(), NOW())
                ");
            }
            echo "<p class='success'>✓ Data pelanggaran default berhasil ditambahkan (10 data)</p>";

            // Re-enable foreign key checks
            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

            mysqli_close($conn);

            echo "<h3 style='color: green; margin-top: 20px;'>✓ Setup Selesai!</h3>";
            echo "<p><a href='login_app/index.php' style='color: #2196F3;'>← Kembali ke Login</a></p>";
        }
        ?>
    </div>
</body>
</html>
