<?php
session_start(); // Start session

// Include database connection
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch current user data to get the current profile picture
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number,address , profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$currentProfilePicture = $user['profile_picture'] ?? 'default.png'; // Default to current profile picture

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone_number'])); // Ensure you're using 'phone_number'
    $address = htmlspecialchars(trim($_POST['address'])); // Capture address

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Handle file upload for profile picture
    $profilePicturePath = $currentProfilePicture; // Default to current profile picture initially
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) { // Ensure this matches your HTML
        $targetDir = "uploads/profile_pictures/"; // Directory for profile pictures
        $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]); // Change to 'profile_picture'
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is a valid image type
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            die("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Check file size (e.g., max 2MB)
        if ($_FILES["profile_picture"]["size"] > 2000000) {
            die("Sorry, your file is too large.");
        }

        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profilePicturePath = $targetFile; // Update to the new file path if upload is successful
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Prepare the SQL query
    // Change 'phone' to 'phone_number' here to match your database schema
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ?,address=? , profile_picture = ? WHERE id = ?");
    
    // Bind the parameters
    $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $address, $profilePicturePath, $userId);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect the user back to the profile page after a successful update
        header("Location: profile.php?success=1");
        exit(); // Make sure to exit after header redirect
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
