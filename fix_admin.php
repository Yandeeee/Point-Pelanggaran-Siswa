<?php
include "login_app/database.php";

$username = "guru1";
$password_plain = "pass1";
$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

// Check if user exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
if (mysqli_num_rows($check) > 0) {
    // Update existing user to have hashed password
    $update = mysqli_query($conn, "UPDATE users SET password='$password_hashed', role='admin' WHERE username='$username'");
    if ($update) {
        echo "User '$username' berhasil diupdate dengan password hash yang benar.\n";
    } else {
        echo "Gagal mengupdate: " . mysqli_error($conn) . "\n";
    }
} else {
    // Insert new user if not exists
    $insert = mysqli_query($conn, "
        INSERT INTO users (username, password, nama, kode_guru, jenis_kelamin, email, role)
        VALUES ('$username', '$password_hashed', 'Budi Santoso', 'GR001', 'Laki-laki', 'budi@guru.sch.id', 'admin')
    ");
    if ($insert) {
        echo "User '$username' berhasil ditambahkan sebagai admin!\n";
    } else {
        echo "Gagal menambahkan: " . mysqli_error($conn) . "\n";
    }
}
?>
