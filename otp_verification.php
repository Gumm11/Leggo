<?php
session_start();
require 'database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

$response = [];

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Invalid request method.');
    }

    $otp = isset($_POST["otp"]) ? $_POST["otp"] : '';
    $email = isset($_POST["email"]) ? $_POST["email"] : '';

    if (!isset($_SESSION['otp']) || !isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['phone_number']) || !isset($_SESSION['password'])) {
        throw new Exception('Session data is missing. Please try registering again.');
    }

    if ($otp != $_SESSION['otp']) {
        throw new Exception('Incorrect OTP.');
    }

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $phone_number = $_SESSION['phone_number'];

    $sql = "INSERT INTO users (username, email, password, phone_number) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Failed to prepare statement: ' . $conn->error);
    }

    $stmt->bind_param("ssss", $username, $email, $password, $phone_number);

    if (!$stmt->execute()) {
        throw new Exception('Failed to save data to database: ' . $stmt->error);
    }

    session_unset();
    session_destroy();
    $response = ['success' => true, 'message' => 'Registration successful!'];

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
?>