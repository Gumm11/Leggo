<?php
session_start();
require 'database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute query to fetch user information
$query = $conn->prepare("SELECT email, phone_number, password FROM users WHERE Id_users = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user_info = $result->fetch_assoc();

if ($user_info) {
    // Mask the password for security
    $user_info['password'] = str_repeat('*', 8);
    echo json_encode($user_info);
} else {
    echo json_encode(['error' => 'User not found']);
}

$conn->close();
?>
