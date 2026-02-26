<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

include "database.php";

// Ambil data siswa dari database
// Pastikan kolom nama_orangtua dan no_telp_orangtua ada (jika belum, tambahkan)
$alter1 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS nama_orangtua VARCHAR(150) NULL";
$alter2 = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS no_telp_orangtua VARCHAR(30) NULL";
mysqli_query($conn, $alter1);
mysqli_query($conn, $alter2);

$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY created_at DESC");
$data = [];
if ($query) {
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
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
    <title>Data Siswa - Point Pelanggaran Siswa</title>
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
            background: linear-gradient(135deg, #36d6a6 0%, #36add4 100%);
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
            background-color: rgba(255,255,255,0.1);
            border-left-color: #ffd89b;
        }

        .sidebar.collapsed .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            border-left-color: transparent;
            border-top-color: #ffd89b;
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
            background-color: rgba(255,255,255,0.2);
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

        /* Toolbar */
        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5568d3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        /* Search */
        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
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

        .badge-laki {
            background-color: #007bff;
            color: white;
        }

        .badge-perempuan {
            background-color: #e83e8c;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons a {
            padding: 5px 10px;
            font-size: 12px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons .edit {
            background-color: #ffc107;
            color: #333;
        }

        .action-buttons .delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-rinci {
            background-color: #17a2b8 !important;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-rinci:hover {
            background-color: #138496 !important;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            color: #333;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #333;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            min-width: 120px;
        }

        .detail-value {
            color: #666;
            flex: 1;
            text-align: right;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
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

            table {
                font-size: 12px;
            }

            table th, table td {
                padding: 8px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="topbar" id="topbar">
        <button class="burger-btn" id="mobileBurgerBtn">☰</button>
        <span id="pageTitle">Data Siswa</span>
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
                    <a href="dashboard.php" title="Dashboard">
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
                    <a href="siswa.php" class="active" title="Data Siswa">
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
                    <h1>Data Siswa</h1>
                    <p style="color: #666; margin-top: 5px;">Kelola Data Siswa</p>
                </div>
                <div class="header-info">
                    <p><strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
                    <p>Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
                    <p style="font-size: 12px; color: #999; margin-top: 10px;"><?= date('d M Y H:i') ?></p>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="toolbar">
                    <a href="tambah_siswa.php" class="btn btn-primary">+ Tambah Siswa</a>
                </div>

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari nama siswa...">
                </div>

                <?php if (!empty($data)): ?>
                    <table id="siswaTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($data as $row): ?>
                                <tr class="data-row">
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['kelas'] ?? '') ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="#" class="btn-rinci" onclick='showDetail(<?= json_encode($row, JSON_HEX_APOS|JSON_HEX_QUOT) ?>); return false;'>Rinci</a>
                                            <a href="edit_siswa.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
                                            <a href="hapus_siswa.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-message">
                        <p>Belum ada data siswa. <a href="tambah_siswa.php" style="color: #667eea;">Tambah siswa sekarang</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Detail Siswa -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Siswa</h2>
                <button class="close-btn" onclick="closeDetail()">&times;</button>
            </div>
            <div class="modal-body" id="detailBody">
                <!-- Detail akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mobileBurgerBtn = document.getElementById('mobileBurgerBtn');
        const searchInput = document.getElementById('searchInput');
        const siswaTable = document.getElementById('siswaTable');

        // Toggle sidebar collapse/expand
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
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

        // Search functionality
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = siswaTable.querySelectorAll('.data-row');
            
            rows.forEach(row => {
                const namaCell = row.querySelector('td:nth-child(2)');
                const nama = namaCell.textContent.toLowerCase();
                
                if (nama.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
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

        // Modal functions
        function showDetail(data) {
            const modal = document.getElementById('detailModal');
            const detailBody = document.getElementById('detailBody');
            
            detailBody.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">Nama</span>
                    <span class="detail-value">${data.nama || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">NIS</span>
                    <span class="detail-value">${data.nis || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kelas</span>
                    <span class="detail-value">${data.kelas || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jenis Kelamin</span>
                    <span class="detail-value">${data.jenis_kelamin || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">No. Telepon</span>
                    <span class="detail-value">${data.no_telepon || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Alamat</span>
                    <span class="detail-value">${data.alamat || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Orang Tua</span>
                    <span class="detail-value">${data.nama_orangtua || '-'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">No. HP Orang Tua</span>
                    <span class="detail-value">${data.no_telp_orangtua || '-'}</span>
                </div>
            `;
            
            modal.classList.add('show');
        }

        function closeDetail() {
            const modal = document.getElementById('detailModal');
            modal.classList.remove('show');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target === modal) {
                closeDetail();
            }
        });
    </script>
</body>
</html>
