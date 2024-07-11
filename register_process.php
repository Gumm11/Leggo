<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_start();
require 'database.php';

// Check connection
if ($conn->connect_error) {
    $response = ['success' => false, 'errors' => ['Database connection failed']];
    sendJsonResponse($response);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';
    $phone_number = $_POST["phone_number"] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($phone_number)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!preg_match('/^[0-9]{10,15}$/', $phone_number)) {
        $errors[] = "Invalid phone number.";
    }

    if (empty($errors)) {
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

        if ($result_username->num_rows > 0) {
            $errors[] = "Username already registered.";
        }
        if ($result_email->num_rows > 0) {
            $errors[] = "Email already registered.";
        }
        if ($result_phone->num_rows > 0) {
            $errors[] = "Phone number already registered.";
        }

        if (empty($errors)) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['phone_number'] = $phone_number;

            $_SESSION['otp'] = mt_rand(100000, 999999);

            $response = ['success' => true, 'email' => $email, 'otp' => $_SESSION['otp']];
        } else {
            $response = ['success' => false, 'errors' => $errors];
        }
    } else {
        $response = ['success' => false, 'errors' => $errors];
    }
} else {
    $response = ['success' => false, 'errors' => ['Invalid request method']];
}

// Close the database connection
$conn->close();

ob_end_clean();

sendJsonResponse($response);

function sendJsonResponse($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
