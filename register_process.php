<?php
// Disable error reporting to prevent HTML output
error_reporting(0);
ini_set('display_errors', 0);

// Start output buffering to catch any unexpected output
ob_start();

session_start();
include 'database.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';
    $phone_number = $_POST["phone_number"] ?? '';

    // Perform all your validations here
    // Example validations:
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($phone_number)) {
        $errors[] = "All fields are required.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    // Add more validations as needed...

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
    $response = ['success' => false, 'errors' => ['Invalid request method']];
}

// Clear the output buffer and disable output buffering
ob_end_clean();

// Set the content type to JSON
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
exit;
?>