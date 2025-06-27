<?php
session_start();
include('db_connect.php'); // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id']; // Assuming user is logged in
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch the current password from the database
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($dbPasswordHash);
    $stmt->fetch();
    $stmt->close();

    // Verify if the current password is correct
    if (!password_verify($currentPassword, $dbPasswordHash)) {
        $_SESSION['message'] = "Current password is incorrect."; // Store error message in session
        $_SESSION['msg_type'] = "error"; // Set message type
        header("Location: settings.php"); // Redirect back to the settings page
        exit();
    }

    // Check if the new password matches the confirmation
    if ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = "New passwords do not match.";
        $_SESSION['msg_type'] = "error";
        header("Location: settings.php");
        exit();
    }

    // Hash the new password before storing it
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $updateStmt->bind_param("si", $newPasswordHash, $userId);
    $updateStmt->execute();
    $updateStmt->close();

    // Success message
    $_SESSION['message'] = "Password changed successfully!";
    $_SESSION['msg_type'] = "success";
    header("Location: settings.php");
    exit();
}
?>
