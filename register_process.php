<?php
session_start();
require 'database.php';

// Check connection
if ($conn->connect_error) {
    $response = ['success' => false, 'errors' => ['Database connection failed: ' . $conn->connect_error]];
    echo json_encode($response);
    exit();
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        $response = ['success' => false, 'errors' => ['Password dan konfirmasi password tidak sama!']];
        echo json_encode($response);
        exit();
    }

    // Validate phone number
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
        $response = ['success' => false, 'errors' => ['Nomor telepon tidak valid!']];
        echo json_encode($response);
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

    if ($result_username->num_rows > 0 || $result_email->num_rows > 0 || $result_phone->num_rows > 0) {
        $errors = [];
        if ($result_username->num_rows > 0) $errors[] = "Username sudah terdaftar, silakan gunakan yang lain!";
        if ($result_email->num_rows > 0) $errors[] = "Email sudah terdaftar, silakan gunakan yang lain!";
        if ($result_phone->num_rows > 0) $errors[] = "Nomor telepon sudah terdaftar, silakan gunakan yang lain!";
        
        $response = ['success' => false, 'errors' => $errors];
        echo json_encode($response);
        exit();
    } else {
        // Generate OTP and store in session
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phone_number'] = $phone_number;
        $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);

        // Send OTP to email (assuming emailJS is set up)
        // Here you should integrate your email sending logic

        $response = ['success' => true, 'email' => $email];
        echo json_encode($response);
        exit();
    }
} else {
    $response = ['success' => false, 'errors' => ['Metode pengiriman tidak valid.']];
    echo json_encode($response);
    exit();
}

$conn->close();
?>