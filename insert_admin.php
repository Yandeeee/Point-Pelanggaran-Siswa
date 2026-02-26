<?php
include "login_app/database.php";

$username = "admin_baru";
$password_plain = "admin123";
$password = password_hash($password_plain, PASSWORD_DEFAULT);
$email = "admin@example.com";
$role = "admin";

$check = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
if (mysqli_num_rows($check) > 0) {
    echo "Username sudah ada.\n";
} else {
    $insert = mysqli_query($conn, "
        INSERT INTO users (username, password, email, role, created_at, updated_at)
        VALUES ('$username', '$password', '$email', '$role', NOW(), NOW())
    ");

    if ($insert) {
        echo "User admin berhasil ditambahkan!\n";
        echo "Username: $username\n";
        echo "Password: $password_plain\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}
?>
