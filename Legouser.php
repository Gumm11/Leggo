<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '123';
$db_name = 'db_leggo';

// Membuat koneksi ke database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari tabel users
$sql = "SELECT username, email, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan data dalam bentuk tabel HTML
    echo "<table border='1'>
            <tr>
        
                <th>Username</th>
                <th>Email</th>
                <th>Password</th>
               
            </tr>";
    // Output data dari setiap row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
              
                <td>" . $row["username"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["password"] . "</td>
                
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Menutup koneksi
$conn->close();
?>