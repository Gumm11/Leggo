<?php
session_start();
require 'database.php'; // file yang berisi koneksi ke database

// Define the base URL
$base_url = 'https://localhost/github/leggo/';

$email_or_username = $_POST['email'];
$password = $_POST['password'];

// Check if email/username or password is empty
if (empty($email_or_username) || empty($password)) {
    header("Location: " . $base_url . "login.php?error=Semua+field+harus+diisi!");
    exit();
}

// Query untuk mendapatkan pengguna berdasarkan username atau email
$query = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$query->bind_param("ss", $email_or_username, $email_or_username);
$query->execute();
$user = $query->get_result()->fetch_assoc();

if (!$user) {
    // User tidak ditemukan berdasarkan username atau email
    header("Location: " . $base_url . "login.php?error=Username+atau+email+tidak+ditemukan!");
    exit();
}

// Check if the password is correct
if (!password_verify($password, $user['password'])) {
    // Password tidak cocok
    header("Location: " . $base_url . "login.php?error=Password+salah!");
    exit();
}

// Password cocok, buat sesi
$_SESSION['user_id'] = $user['Id_users'];
header("Location: " . $base_url . "index.html");
exit();
?>