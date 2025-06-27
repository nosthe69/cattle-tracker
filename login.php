<?php
session_start(); // Start the session
// Include your database connection
include 'db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query using positional placeholders (?)
    $sql = "SELECT * FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the email parameter
        $stmt->bind_param("s", $email);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows == 1) {
            // Fetch the user data
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start the session and store user info
                $_SESSION['user_id'] = $user['id']; // Store user ID or other info
        
                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Invalid password
                echo "Invalid password!";
            }
        } else {
            // No user found with that email
            echo "No account found with that email!";
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the SQL statement
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
