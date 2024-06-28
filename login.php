<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted email and password
    $email = $_POST["email"];
    $password = $_POST["password"];

    $db_host = 'localhost';
    $db_username = 'leggomyi_legolego';
    $db_password = 'Leggoo1234!';
    $db_name = 'leggomyi_lego';
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query the database for the user data
    $sql = "SELECT email, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Login successful
            session_start();
            $_SESSION["email"] = $email;
            header("Location: index.html");
            exit;
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $conn->close();
}
?>