<?php
include "database.php";
header('Content-Type: text/html; charset=utf-8');
echo '<h2>Debug: Tabel siswa</h2>';
$res = mysqli_query($conn, "SELECT id, nama, nis, kelas, nama_orangtua, no_telp_orangtua, created_at FROM siswa ORDER BY id ASC");
if ($res && mysqli_num_rows($res) > 0) {
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<tr><th>id</th><th>nama</th><th>nis</th><th>kelas</th><th>nama_orangtua</th><th>no_telp_orangtua</th><th>created_at</th></tr>';
    while ($r = mysqli_fetch_assoc($res)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($r['id']) . '</td>';
        echo '<td>' . htmlspecialchars($r['nama']) . '</td>';
        echo '<td>' . htmlspecialchars($r['nis']) . '</td>';
        echo '<td>' . htmlspecialchars($r['kelas']) . '</td>';
        echo '<td>' . htmlspecialchars($r['nama_orangtua']) . '</td>';
        echo '<td>' . htmlspecialchars($r['no_telp_orangtua']) . '</td>';
        echo '<td>' . htmlspecialchars($r['created_at']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>Tidak ada data siswa.</p>';
}
echo '<p><a href="siswa.php">← Kembali ke halaman siswa</a></p>';
?>
