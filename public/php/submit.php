<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi sederhana
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        header('Location: https://leggo.my.id/register.html?error=Semua+field+harus+diisi!');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: https://leggo.my.id/register.html?error=Email+tidak+valid!');
        exit();
    }

    if ($password !== $confirm_password) {
        header('Location: https://leggo.my.id/register.html?error=Password+dan+konfirmasi+password+tidak+sama!');
        exit();
    }

    // Hashing password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Username atau email sudah ada
        header('Location: https://leggo.my.id/register.html?error=Username+atau+Email+sudah+terdaftar,+silakan+gunakan+yang+lain!');
        exit();
    } else {
        // Menyiapkan statement SQL untuk menghindari SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);

        // Menjalankan statement
        if ($stmt->execute()) {
            header('Location: https://leggo.my.id/login.html');
        } else {
            header('Location: https://leggo.my.id/register.html?error=Error:+'. $stmt->error);
            exit();
        }

        // Menutup statement
        $stmt->close();
    }
} else {
    header('Location: https://leggo.my.id/register.html?error=Metode+pengiriman+tidak+valid.');
    exit();
}

// Menutup koneksi
$conn->close();
?>
