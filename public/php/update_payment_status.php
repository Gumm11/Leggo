<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'database.php'; // Adjust this path based on your project structure and database setup

header('Content-Type: application/json');

// Parse JSON input from the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && isset($data['id_pemesanan']) && isset($data['status'])) {
    // Extract data
    $idPemesanan = $conn->real_escape_string($data['id_pemesanan']);
    $status = $conn->real_escape_string($data['status']);

    // Update status in 'pemesanan' table
    $sql = "UPDATE pemesanan SET status_pembayaran = '$status' WHERE id_pemesanan = '$idPemesanan'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}
?>