<?php
require 'database.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define('BASE_URL', 'https://localhost/github/leggo/');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        header('Location: ' . BASE_URL . 'register.php?error=Password dan konfirmasi password tidak sama!');
        exit();
    }

    // Validate phone number
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
        header('Location: ' . BASE_URL . 'register.php?error=Nomor telepon tidak valid!');
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
    
    // Check if phone number already exists
    $stmt_phone = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
    $stmt_phone->bind_param("s", $phone_number);
    $stmt_phone->execute();
    $result_phone = $stmt_phone->get_result();

    if ($result_username->num_rows > 0 && $result_email->num_rows > 0 && $result_phone->num_rows > 0) {
        // Username, email, and phone number already exist
        header('Location: ' . BASE_URL . 'register.php?error=Username, Email, dan Nomor telepon sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } elseif ($result_username->num_rows > 0) {
        // Username already exists
        header('Location: ' . BASE_URL . 'register.php?error=Username sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } elseif ($result_email->num_rows > 0) {
        // Email already exists
        header('Location: ' . BASE_URL . 'register.php?error=Email sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } elseif ($result_phone->num_rows > 0) {
        // Phone number already exists
        header('Location: ' . BASE_URL . 'register.php?error=Nomor telepon sudah terdaftar, silakan gunakan yang lain!');
        exit();
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt_insert = $conn->prepare("INSERT INTO users (email, username, phone_number, password) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("ssss", $email, $username, $phone_number, $password_hash);

        if ($stmt_insert->execute()) {
            header('Location: ' . BASE_URL . 'login.php?success=Registration successful! Please login.');
            exit();
        } else {
            header('Location: ' . BASE_URL . 'register.php?error=Error: ' . $stmt_insert->error);
            exit();
        }
    }
} else {
    header('Location: ' . BASE_URL . 'register.php?error=Metode pengiriman tidak valid.');
    exit();
}

// Menutup koneksi
$conn->close();
?>
