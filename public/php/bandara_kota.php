<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'database.php'; // Ensure this file sets up the $conn variable for the database connection

// Construct the SQL query
$sql = "SELECT bandara.*, kota.Nama_kota, kota.Negara 
        FROM bandara 
        JOIN kota ON bandara.Id_kota = kota.Id_kota;";

// Execute the query
$result = $conn->query($sql);

if ($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(array("error" => "Query failed."));
}

$conn->close();
?>
