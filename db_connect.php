<?php
// db_connection.php
$servername = "localhost"; // or your server name
$username = "root"; // default username for XAMPP
$password = ""; // default password is empty
$dbname = "cattletracker_db"; // change to your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
