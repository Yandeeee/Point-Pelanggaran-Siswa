<?php
include "database.php";

header('Content-Type: text/html; charset=utf-8');

echo '<h2>Debug: Daftar users</h2>';
$result = mysqli_query($conn, "SELECT id, username, role, created_at, password FROM users ORDER BY id DESC");
if ($result && mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="8" cellspacing="0">';
    echo '<tr><th>id</th><th>username</th><th>role</th><th>created_at</th><th>password_hash</th></tr>';
    while ($r = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($r['id']) . '</td>';
        echo '<td>' . htmlspecialchars($r['username']) . '</td>';
        echo '<td>' . htmlspecialchars($r['role']) . '</td>';
        echo '<td>' . htmlspecialchars($r['created_at']) . '</td>';
        echo '<td style="font-family:monospace;">' . htmlspecialchars($r['password']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>Tidak ada user.</p>';
}

echo '<p><a href="index.php">← Kembali ke Login</a></p>';
?>