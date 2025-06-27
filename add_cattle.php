<?php
session_start(); // Start session

// Include database connection
include('C:/xampp/htdocs/CattleTrackerApp/db_connect.php');

// Include QR code generation library
include('C:/xampp/htdocs/CattleTrackerApp/lib/qrlib.php'); // Ensure you have all the necessary QR library files

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cattleName = $_POST['cattle_name'];
    $cattleBreed = $_POST['cattle_breed'];
    $cattleGender = $_POST['cattle_gender']; // New gender input
    $cattleColor = $_POST['cattle_color']; // New color input
    $cattleAge = $_POST['cattle_age'];
    $userId = $_SESSION['user_id']; // Get user ID from session

    // Check if user ID exists
    if (!isset($userId)) {
        die("User not logged in.");
    }

    // Insert cattle data into the database, including new attributes
    $stmt = $conn->prepare("INSERT INTO cattle (user_id, name, breed, gender, color, age) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $userId, $cattleName, $cattleBreed, $cattleGender, $cattleColor, $cattleAge);
    $stmt->execute();

    // Retrieve the last inserted cattle ID
    $cattleId = $conn->insert_id; // This gets the newly inserted cattle's ID

    // Generate QR code data
    $qrData = "Cattle ID: $cattleId, Name: $cattleName, Breed: $cattleBreed, Gender: $cattleGender, Color: $cattleColor, Age: $cattleAge"; // Include new attributes
    $qrPath = "qrcodes/cattle_$cattleId.png";  // Define the file path for the QR code

    // Save the QR code as an image
    QRcode::png($qrData, $qrPath);

    // Update the cattle record with the QR code path
    $updateStmt = $conn->prepare("UPDATE cattle SET qr_code_path = ? WHERE id = ?");
    $updateStmt->bind_param("si", $qrPath, $cattleId);
    $updateStmt->execute();

     // Redirect back to the profile page
    header('Location: profile.php');
    exit(); // Make sure to exit to stop further script execution
}
?>
