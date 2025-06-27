<?php
// Start the session
session_start();

// Include database connection
include('db_connect.php');

// Check if the form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the logged-in user's ID from the session
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['report_error'] = "Please log in to submit a report.";
        header('Location: track-cattle.php');
        exit();
    }
    $user_id = $_SESSION['user_id'];

    // Fetch the reporter's first name and last name from the database
    $sql = "SELECT first_name, last_name FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $reporter_name = $row['first_name'] . ' ' . $row['last_name'];
    } else {
        $reporter_name = 'Anonymous';
    }

    // Retrieve form data
    $cow_id = $_POST['cow_id'];
  
    $location = $_POST['location'];
    $manual_location = $_POST['manual_location'];
    $description = $_POST['description'];
    $user_type = $_POST['user_type'];
    $coordinates = $_POST['coordinates'];

    // Validate cow_id
    if (empty($cow_id)) {
        $_SESSION['report_error'] = "No valid QR code scanned. Please scan a valid QR code before submitting the report.";
        header('Location: track-cattle.php');
        exit();
    }

    // Check if cow_id exists
    $query = "SELECT * FROM cattle WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cow_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $_SESSION['report_error'] = "Sorry, the scanned QR code does not match any cattle records or the cow is no longer registered.";
        header('Location: track-cattle.php');
        exit();
    }

    $cow = $result->fetch_assoc();
    $owner_id = $cow['user_id'];
    $cow_name = $cow['name'];

    // Insert report into cattle_reports
    $sql = "INSERT INTO cattle_reports (cow_id, cow_name, user_id, reporter_name, location, manual_location, description, user_type, coordinates)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isissssss', $cow_id, $cow_name, $user_id, $reporter_name, $location, $manual_location, $description, $user_type, $coordinates);

    if ($stmt->execute()) {
        $notification_message = "Your cow $cow_name (ID: $cow_id) has been reported at $manual_location by $reporter_name. Description: $description";
        $notification_sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
        $stmt = $conn->prepare($notification_sql);
        $stmt->bind_param('is', $owner_id, $notification_message);

        if ($stmt->execute()) {
            $_SESSION['report_success'] = "Cattle sighting reported successfully! Notification sent to the owner.";
        } else {
            $_SESSION['report_error'] = "Cattle sighting reported, but failed to notify the owner. Please try again.";
        }
    } else {
        $_SESSION['report_error'] = "Failed to report cattle sighting. Please try again.";
    }

    // Close the prepared statements and database connection
    $stmt->close();
    $conn->close();

    // Redirect back to track-cattle.php with a success or error message
    header('Location: track-cattle.php');
    exit();

} else {
    header('Location: track-cattle.php');
    exit();
}

?>
