<?php
session_start();

$response = ['logged_in' => false, 'username' => 'Guest'];

if (isset($_SESSION['user_id'])) {
    require 'database.php';
    $user_id = $_SESSION['user_id'];
    
    $query = $conn->prepare("SELECT username FROM users WHERE Id_users = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    $user_info = $result->fetch_assoc();
    
    if ($user_info) {
        $response['logged_in'] = true;
        $response['username'] = $user_info['username'];
    }
}

echo json_encode($response);
?>
