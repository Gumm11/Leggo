<?php
session_start();
include 'database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

function sendJsonResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

try {
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception('Invalid request method.');
    }

    $otp = isset($_POST["otp"]) ? $_POST["otp"] : '';

    if (!isset($_SESSION['otp']) || !isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['password']) || !isset($_SESSION['phone_number'])) {
        throw new Exception('Session data is missing. Please try registering again.');
    }

    if ($otp != $_SESSION['otp']) {
        throw new Exception('Incorrect OTP.');
    }

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $password = password_hash($_SESSION['password'], PASSWORD_BCRYPT);
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
    sendJsonResponse(true, 'Registration successful!');

} catch (Exception $e) {
    sendJsonResponse(false, $e->getMessage());
}
?>