<?php
session_start();
require 'database.php'; // Adjust the path as needed

$response = array('username' => 'Guest');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT username FROM users WHERE Id_users = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($row = $result->fetch_assoc()) {
        $response['username'] = $row['username'];
    }
}

echo json_encode($response);
?>
