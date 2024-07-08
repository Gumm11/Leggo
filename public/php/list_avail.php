<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require 'database.php'; // Ensure this file sets up the $conn variable for the database connection

// Retrieve query parameters
$departure = isset($_GET['departure']) ? $_GET['departure'] : '';
$arrival = isset($_GET['arrival']) ? $_GET['arrival'] : '';
$departureDate = isset($_GET['departureDate']) ? $_GET['departureDate'] : '';
$returnDate = isset($_GET['returnDate']) ? $_GET['returnDate'] : '';
$ticketClass = isset($_GET['ticketClass']) ? $_GET['ticketClass'] : '';
$passengers = isset($_GET['passengers']) ? $_GET['passengers'] : '';

// Construct the SQL query for flights
$sql = "
    SELECT 
        p.*,
        pv.Nama_maskapai,
        k1.Kode_bandara AS bandara_keberangkatan,
        k2.Kode_bandara AS bandara_kedatangan,
        k1.Nama_bandara AS nama_bandara_keberangkatan,
        k2.Nama_bandara AS nama_bandara_kedatangan,
        kt1.Nama_kota AS kota_keberangkatan,
        kt2.Nama_kota AS kota_kedatangan
    FROM 
        penerbangan p
    INNER JOIN 
        pesawat pv ON p.Id_pesawat = pv.Id_pesawat
    LEFT JOIN 
        bandara k1 ON p.Id_bandara_keberangkatan = k1.Id_bandara
    LEFT JOIN 
        bandara k2 ON p.Id_bandara_kedatangan = k2.Id_bandara
    LEFT JOIN 
        kota kt1 ON k1.Id_kota = kt1.Id_kota
    LEFT JOIN 
        kota kt2 ON k2.Id_kota = kt2.Id_kota
    WHERE 1=1
";

// Add conditions to the query based on the parameters
if (!empty($departure)) {
    $sql .= " AND kt1.Nama_kota = '" . $conn->real_escape_string($departure) . "'";
}
if (!empty($arrival)) {
    $sql .= " AND kt2.Nama_kota = '" . $conn->real_escape_string($arrival) . "'";
}
if (!empty($departureDate)) {
    $sql .= " AND DATE(p.Tanggal_keberangkatan) = '" . $conn->real_escape_string($departureDate) . "'";
}
if (!empty($ticketClass)) {
    $sql .= " AND p.Kelas = '" . $conn->real_escape_string($ticketClass) . "'";
}
if (!empty($passengers)) {
    $sql .= " AND p.Jumlah_kursi >= " . intval($passengers);
}

// Execute the query for flights
$result = $conn->query($sql);

// Prepare response data array
$data = array();

// Fetch flights data
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return JSON response
echo json_encode($data);

// Close database connection
$conn->close();
?>
