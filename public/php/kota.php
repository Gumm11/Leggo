<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'database.php';

$sql = "SELECT * FROM kota";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $kota_data = array();
    while($row = $result->fetch_assoc()) {
        $kota_data[] = $row;
    }
    echo json_encode($kota_data);
} else {
    echo json_encode([]);
}

$conn->close();
?>
