<?php
require 'database.php';
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $otpValidated = $_POST['otpValidated'] === 'true';

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        $response['message'] = 'Password dan konfirmasi password tidak sama!';
        echo json_encode($response);
        exit();
    }

    // Validate phone number
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
        $response['message'] = 'Nomor telepon tidak valid!';
        echo json_encode($response);
        exit();
    }

    // Validate OTP
    if (!$otpValidated) {
        $response['message'] = 'Silakan validasi OTP terlebih dahulu.';
        echo json_encode($response);
        exit();
    }

    // Insert the new user into the database
    $stmt_insert = $conn->prepare("INSERT INTO users (email, username, phone_number, password) VALUES (?, ?, ?, ?)");
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt_insert->bind_param("ssss", $email, $username, $phone_number, $password_hash);

    if ($stmt_insert->execute()) {
        session_unset();
        session_destroy();
        $response['success'] = true;
        $response['message'] = 'Registration successful!';
        echo json_encode($response);
        exit();
    } else {
        $response['message'] = 'Error: ' . $stmt_insert->error;
        echo json_encode($response);
        exit();
    }
} else {
    $response['message'] = 'Metode pengiriman tidak valid.';
    echo json_encode($response);
    exit();
}

// Menutup koneksi
$conn->close();
?>