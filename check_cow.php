<?php
session_start(); // Start the session
include('db_connect.php'); // Include your database connection



$cow_id = mysqli_real_escape_string($conn, $_POST['cow_id']);

// Check if cow_id is valid by querying the database
$query = "SELECT * FROM cattle WHERE id = '$cow_id'";
$result = mysqli_query($conn, $query);

// Return a JSON response indicating whether the cow_id is valid
if (mysqli_num_rows($result) > 0) {
    echo json_encode(["valid" => true]);
} else {
    echo json_encode(["valid" => false]);
}
?>
