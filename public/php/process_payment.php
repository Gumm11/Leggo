<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Midtrans\Config;
use Midtrans\Snap;

// Set your server key
Config::$serverKey = 'SB-Mid-server-sZCFILFNAM5259a9Mdd-Mpjy';
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $gross_amount = intval(preg_replace('/[^\d]/', '', $data['totalPrice'])) / 100;
    $transactionDetails = [
        'order_id' => 'order-' . time(),
        'gross_amount' => $gross_amount
    ];

    $customerDetails = [
        'first_name' => $data['namaLengkap'],
        'last_name' => '',
        'email' => $data['email'],
        'phone' => $data['nomorTelepon']
    ];

    $params = [
        'transaction_details' => $transactionDetails,
        'customer_details' => $customerDetails,
        'item_details' => [
            [
                'id' => 'item1',
                'price' => $gross_amount,
                'quantity' => 1,
                'name' => 'Flight Payment'
            ]
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        echo json_encode(['token' => $snapToken]);
    } catch (Exception $e) {
        error_log($e->getMessage()); // Log the error message
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
}
?>