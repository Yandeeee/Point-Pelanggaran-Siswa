<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "database.php";

// Handle deletion of 10 latest users (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_latest'])) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['flash_error'] = 'Aksi tidak diizinkan.';
    } else {
        $deleteQuery = "DELETE FROM users WHERE id IN (SELECT id FROM (SELECT id FROM users ORDER BY created_at DESC LIMIT 10) AS t)";
        mysqli_query($conn, $deleteQuery);
        $_SESSION['flash_success'] = '10 data terbaru berhasil dihapus.';
    }
    header('Location: dashboard.php');
    exit;
}

// Ambil data terbaru dari database (sesuaikan query dengan tabel Anda)
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
}

// Ambil riwayat login untuk role admin
date_default_timezone_set('Asia/Makassar');
$loginLogs = [];
// pastikan tabel login_logs ada sebelum query
$createLogTable = "CREATE TABLE IF NOT EXISTS login_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    role VARCHAR(50),
    ip_address VARCHAR(45),
    user_agent TEXT,
    logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $createLogTable);

$llQuery = mysqli_query($conn, "SELECT * FROM login_logs WHERE role='admin' ORDER BY logged_at DESC LIMIT 20");
if ($llQuery) {
    while ($r = mysqli_fetch_assoc($llQuery)) {
        $loginLogs[] = $r;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Point Pelanggaran Siswa</title>
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
            height: 100vh;
        }

        /* Topbar dengan burger menu */
        .topbar {
            display: none;
            /* background: linear-gradient(135deg, #36d6a6 0%, #36add4 100%); */
            background-color: #1b82c7;
            color: white;
            padding: 15px 20px;
            align-items: center;
            gap: 15px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .burger-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .burger-btn:hover {
            transform: scale(1.1);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            /* background: linear-gradient(135deg, #36d6a6 0%, #36add4 100%); */
            background-color: #1b82c7;
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
            position: relative;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 15px 10px;
            margin-bottom: 0;
        }

        .sidebar-header h3 {
            font-size: 18px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-header h3 {
            display: none;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-header p {
            display: none;
        }

        .toggle-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .toggle-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .sidebar.collapsed .toggle-btn {
            position: static;
            width: 100%;
            margin: 10px 0;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar.collapsed .sidebar-menu a {
            justify-content: center;
            padding: 15px 10px;
            gap: 0;
            border-left: none;
            border-top: 4px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: rgba(192, 44, 44, 0.1);
            border-left-color: #ffd89b;
        }

        .sidebar.collapsed .sidebar-menu a:hover {
            background-color: rgba(117, 28, 28, 0.1);
            border-left-color: transparent;
            border-top-color: #e2a33c;
        }

        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.2);
            border-left-color: #ffd89b;
        }

        .sidebar.collapsed .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.2);
            border-left-color: transparent;
            border-top-color: #ffd89b;
        }

        .menu-icon {
            font-size: 20px;
            min-width: 20px;
        }

        .menu-text {
            transition: all 0.3s;
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
            margin-top: 20px;
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-footer {
            padding: 10px;
            margin-top: 0;
        }

        .sidebar-footer a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            color: white;
            text-decoration: none;
            background-color: rgba(255,255,255,0.1);
            border-radius: 4px;
            text-align: center;
            transition: all 0.3s;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-footer a {
            gap: 0;
        }

        .sidebar-footer a:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        .sidebar-footer .logout-text {
            transition: all 0.3s;
        }

        .sidebar.collapsed .sidebar-footer .logout-text {
            display: none;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
            transition: all 0.3s ease;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
        }

        .header-info {
            text-align: right;
        }

        .header-info p {
            color: #666;
            margin: 5px 0;
        }

        /* Content Section */
        .content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .content h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #f8f9fa;
        }

        table th {
            padding: 15px;
            text-align: left;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-admin {
            background-color: #dc3545;
            color: white;
        }

        .badge-user {
            background-color: #28a745;
            color: white;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .topbar {
                display: flex;
            }

            .container {
                flex-direction: column;
                margin-top: 60px;
            }

            .sidebar {
                position: fixed;
                height: calc(100vh - 60px);
                z-index: 999;
                left: -250px;
                transition: left 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar.collapsed {
                width: 80px;
                left: 0;
            }

            .main-content {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .header-info {
                text-align: left;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="topbar" id="topbar">
        <button class="burger-btn" id="mobileBurgerBtn">☰</button>
        <span id="pageTitle">Dashboard</span>
    </div>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
                <p>Point Pelanggaran Siswa</p>
                <button class="toggle-btn" id="toggleBtn">◀</button>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="active" title="Dashboard">
                        <span class="menu-icon">📊</span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="pelanggaran.php" title="Data Pelanggaran">
                        <span class="menu-icon">📋</span>
                        <span class="menu-text">Data Pelanggaran</span>
                    </a>
                </li>
                <li>
                    <a href="siswa.php" title="Data Siswa">
                        <span class="menu-icon">👥</span>
                        <span class="menu-text">Data Siswa</span>
                    </a>
                </li>
                <li>
                    <a href="laporan.php" title="Laporan">
                        <span class="menu-icon">📄</span>
                        <span class="menu-text">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="pengaturan.php" title="Pengaturan">
                        <span class="menu-icon">⚙️</span>
                        <span class="menu-text">Pengaturan</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <a href="logout.php" title="Logout">
                    <span class="menu-icon">🚪</span>
                    <span class="logout-text">Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>Selamat Datang</h1>
                    <p style="color: #666; margin-top: 5px;">Sistem Point Pelanggaran Siswa</p>
                    <p style="font-size: 16px;font-weight: bold; color: #333; margin-top: 5px;">SMK TI BALI GLOBAL DENPASAR</p>
                </div>
                <div class="header-info">
                    <p><strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
                    <p>Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
                    <p style="font-size: 14px; color: #141111; margin-top: 10px;"><?= date('d M Y H:i') ?></p>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <h2>Data Terbaru dari Database</h2>

                <?php if (isset($_SESSION['flash_success'])): ?>
                    <div style="color: #155724; background:#d4edda; padding:10px; border-radius:6px; margin-bottom:12px;">
                        <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div style="color: #721c24; background:#f8d7da; padding:10px; border-radius:6px; margin-bottom:12px;">
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <form method="post" onsubmit="return confirm('Hapus 10 data terbaru dari database? Tindakan ini tidak dapat dibatalkan.')">
                        <button type="submit" name="delete_latest" style="background:#dc3545;color:white;padding:8px 12px;border-radius:4px;border:none;cursor:pointer;margin-bottom:12px;">Hapus 10 Data Terbaru</button>
                    </form>
                <?php endif; ?>

                <?php if (!empty($data)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $row['role'] ?>">
                                            <?= htmlspecialchars($row['role']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        <p>Tidak ada data yang ditampilkan.</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <hr style="margin:20px 0;">
                    <h2>Riwayat Login Admin (20 Terbaru)</h2>
                    <?php if (!empty($loginLogs)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>IP Address</th>
                                    <th>Waktu Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach ($loginLogs as $log): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($log['username']) ?></td>
                                        <td><?= htmlspecialchars($log['ip_address']) ?></td>
                                        <td><?= date('d M Y H:i:s', strtotime($log['logged_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-message">Belum ada aktivitas login admin.</div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mobileBurgerBtn = document.getElementById('mobileBurgerBtn');
        const mainContent = document.querySelector('.main-content');

        // Toggle sidebar collapse/expand
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            // Update button text
            if (sidebar.classList.contains('collapsed')) {
                toggleBtn.textContent = '▶';
            } else {
                toggleBtn.textContent = '◀';
            }
        });

        // Mobile burger menu
        mobileBurgerBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when menu item clicked on mobile
        document.querySelectorAll('.sidebar-menu a, .sidebar-footer a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            });
        });

        // Update page title on mobile
        document.querySelectorAll('.sidebar-menu a, .sidebar-footer a').forEach(link => {
            link.addEventListener('click', function(e) {
                const text = this.querySelector('.menu-text')?.textContent || this.title;
                document.getElementById('pageTitle').textContent = text;
            });
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !mobileBurgerBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Save sidebar state to localStorage
        window.addEventListener('beforeunload', function() {
            if (sidebar.classList.contains('collapsed')) {
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });

        // Restore sidebar state
        window.addEventListener('load', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                toggleBtn.textContent = '▶';
            }
        });
    </script>
</body>
</html>

