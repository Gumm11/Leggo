<?php
require 'database.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        header('Location: https://localhost/github/leggo/register.php?error=Password dan konfirmasi password tidak sama!');
        exit();
    }

    // Check if username already exists
    $stmt_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt_username->bind_param("s", $username);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();

    // Check if email already exists
    $stmt_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();

    if ($result_username->num_rows > 0 && $result_email->num_rows > 0) {
        // Both username and email already exist
        header('Location: https://localhost/github/leggo/register.php?error=Username dan Email sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } elseif ($result_username->num_rows > 0) {
        // Username already exists
        header('Location: https://localhost/github/leggo/register.php?error=Username sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } elseif ($result_email->num_rows > 0) {
        // Email already exists
        header('Location: https://localhost/github/leggo/register.php?error=Email sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt_insert = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $email, $username, $password_hash);

        if ($stmt_insert->execute()) {
            header('Location: https://localhost/github/leggo/login.php?success=Registration successful! Please login.');
            exit();
        } else {
            header('Location: https://localhost/github/leggo/register.php?error=Error: ' . $stmt_insert->error);
            exit();
        }
    }
} else {
    header('Location: register.php?error=Metode pengiriman tidak valid.');
    exit();
}

// Menutup koneksi
$conn->close();
?>
