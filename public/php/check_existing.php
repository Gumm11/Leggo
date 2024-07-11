<?php
require 'database.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = htmlspecialchars($data['email']);
$username = htmlspecialchars($data['username']);
$phone_number = htmlspecialchars($data['phone_number']);

$response = ['exists' => false, 'message' => ''];

// Check if username, email, or phone number already exists
$stmt_username = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt_username->bind_param("s", $username);
$stmt_username->execute();
$result_username = $stmt_username->get_result();

$stmt_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt_email->bind_param("s", $email);
$stmt_email->execute();
$result_email = $stmt_email->get_result();

$stmt_phone = $conn->prepare("SELECT * FROM users WHERE phone_number = ?");
$stmt_phone->bind_param("s", $phone_number);
$stmt_phone->execute();
$result_phone = $stmt_phone->get_result();

if ($result_username->num_rows > 0) {
    $response['exists'] = true;
    $response['message'] = 'Username sudah terdaftar, silakan gunakan yang lain!';
} elseif ($result_email->num_rows > 0) {
    $response['exists'] = true;
    $response['message'] = 'Email sudah terdaftar, silakan gunakan yang lain!';
} elseif ($result_phone->num_rows > 0) {
    $response['exists'] = true;
    $response['message'] = 'Nomor telepon sudah terdaftar, silakan gunakan yang lain!';
}

echo json_encode($response);

$conn->close();
?>