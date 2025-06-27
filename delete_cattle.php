<?php
session_start(); // Start session

// Include database connection
include('C:/xampp/htdocs/CattleTrackerApp/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    $cattleId = $_GET['id'];

    // First, verify that the cattle belongs to the logged-in user
    $stmt = $conn->prepare("SELECT * FROM cattle WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cattleId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the cattle exists and belongs to the user, proceed with deletion
    if ($result->num_rows > 0) {
        // Delete the cattle entry
        $deleteStmt = $conn->prepare("DELETE FROM cattle WHERE id = ? AND user_id = ?");
        $deleteStmt->bind_param("ii", $cattleId, $userId);
        $deleteStmt->execute();

        // Redirect back to profile page after deletion
        header("Location: profile.php?msg=cattle_deleted");
        exit();
    } else {
        // Cattle does not belong to the user or doesn't exist
        die("Cattle not found or unauthorized access.");
    }
} else {
    // No cattle ID provided
    die("Invalid request.");
}
?>
