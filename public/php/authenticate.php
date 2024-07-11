<?php
session_start();
require 'database.php'; // file yang berisi koneksi ke database

header('Content-Type: application/json');

$response = [];

$email_or_username = $_POST['email'];
$password = $_POST['password'];
$cf_response = $_POST['cf-turnstile-response']; 

// Check if email/username or password is empty
if (empty($email_or_username) || empty($password)) {
    $response['status'] = 'error';
    $response['message'] = 'Semua field harus diisi!';
    echo json_encode($response);
    exit();
}

// Verify the Turnstile response
$secret = '0x4AAAAAAAe4BYa8_BiIFcIa-ERRM3iIfBA'; 
$remoteIp = $_SERVER['REMOTE_ADDR'];

$verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
$data = [
    'secret' => $secret,
    'response' => $cf_response,
    'remoteip' => $remoteIp,
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($verifyUrl, false, $context);
$resultData = json_decode($result, true);

if (!$resultData['success']) {
    $response['status'] = 'error';
    $response['message'] = 'Captcha verification failed. Please try again.';
    echo json_encode($response);
    exit();
}

// Query untuk mendapatkan pengguna berdasarkan username atau email
$query = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$query->bind_param("ss", $email_or_username, $email_or_username);
$query->execute();
$user = $query->get_result()->fetch_assoc();

if (!$user) {
    // User tidak ditemukan berdasarkan username atau email
    $response['status'] = 'error';
    $response['message'] = 'Username atau email tidak ditemukan!';
    echo json_encode($response);
    exit();
}

// Check if the password is correct
if (!password_verify($password, $user['password'])) {
    // Password tidak cocok
    $response['status'] = 'error';
    $response['message'] = 'Password salah!';
    echo json_encode($response);
    exit();
}

// Password cocok, buat sesi
$_SESSION['user_id'] = $user['Id_users'];
$response['status'] = 'success';
$response['user'] = [
    'id' => $user['Id_users'],
    'username' => $user['username'],
    'email' => $user['email'],
    'phone_number' => $user['phone_number']
];
echo json_encode($response);
exit();
?>
