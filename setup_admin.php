<?php
include "login_app/database.php";

$username = "guru1";
$password_plain = "pass1";
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Update guru table
$update_guru = mysqli_query($conn, "UPDATE guru SET password='$password_hashed' WHERE username='$username'");
if ($update_guru) {
    echo "Tabel guru: Password untuk '$username' berhasil dihash.\n";
} else {
    echo "Gagal update guru: " . mysqli_error($conn) . "\n";
}

// Check and insert/update users table
$check_users = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
if (mysqli_num_rows($check_users) > 0) {
    $update_users = mysqli_query($conn, "UPDATE users SET password='$password_hashed', role='admin' WHERE username='$username'");
    if ($update_users) {
        echo "Tabel users: Password dan role admin untuk '$username' berhasil diupdate.\n";
    }
} else {
    $insert_users = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', 'admin')");
    if ($insert_users) {
        echo "Tabel users: User '$username' berhasil ditambahkan sebagai admin!\n";
    } else {
        echo "Gagal menambahkan ke users: " . mysqli_error($conn) . "\n";
    }
}
?>
