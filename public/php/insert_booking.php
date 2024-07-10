<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once 'database.php'; // Adjust this path based on your project structure and database setup

header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user_id is set in session
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Parse JSON input from the request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    // Extract data from paymentData
    $namaLengkap = $conn->real_escape_string($data['namaLengkap']);
    $nomorTelepon = $conn->real_escape_string($data['nomorTelepon']);
    $email = $conn->real_escape_string($data['email']);
    $selectedBagasi = (int) $data['selectedBagasi'];
    $totalPrice = (float) str_replace(['IDR', '.', ','], ['', '', '.'], $data['totalPrice']);
    $departureFlightId = (int) $data['departureFlight']['Id_penerbangan'];
    $returnFlightId = isset($data['returnFlight']) ? (int) $data['returnFlight']['Id_penerbangan'] : null;
    $jumlahPenumpang = count($data['passengers']);

    // Check if user exists in the 'users' table
    $userCheckSql = "SELECT * FROM users WHERE Id_users = '$user_id'";
    $result = $conn->query($userCheckSql);

    if ($result->num_rows > 0) {
        // Prepare SQL statement to insert into 'pemesanan' table
        $sql = "INSERT INTO pemesanan (
                    id_penerbangan_berangkat, id_penerbangan_pulang, id_users, jumlah_penumpang, total_bayar, status_pembayaran,
                    nama_lengkap, nomor_telepon, email, selected_bagasi
                ) VALUES (
                    '$departureFlightId', " . ($returnFlightId ? "'$returnFlightId'" : "NULL") . ", '$user_id', '$jumlahPenumpang', $totalPrice, 'Pending', 
                    '$namaLengkap', '$nomorTelepon', '$email', $selectedBagasi
                )";

        if ($conn->query($sql) === TRUE) {
            $id_pemesanan = $conn->insert_id;

            // Insert passengers data
            $passengerInsertSuccess = true;
            foreach ($data['passengers'] as $passenger) {
                $namaLengkapCustomer = $conn->real_escape_string($passenger['namaLengkapCustomer']);
                $ageGroup = $conn->real_escape_string($passenger['ageGroup']);

                $passengerSql = "INSERT INTO passengers (
                                    id_pemesanan, nama_lengkap, age_group
                                ) VALUES (
                                    '$id_pemesanan', '$namaLengkapCustomer', '$ageGroup'
                                )";

                if ($conn->query($passengerSql) !== TRUE) {
                    $passengerInsertSuccess = false;
                    error_log("Error inserting passenger: " . $conn->error);
                    break;
                }
            }

            if ($passengerInsertSuccess) {
                echo json_encode(['success' => true, 'id_pemesanan' => $id_pemesanan]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to insert passenger data']);
            }
        } else {
            error_log("Error inserting pemesanan: " . $conn->error);
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'User not found']);
    }

    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}
?>