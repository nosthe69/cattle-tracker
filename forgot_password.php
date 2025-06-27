<?php
// Include database connection
include 'db_connect.php'; // Adjust the path as needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email exists in the database
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Save the token and expiry time in the database
            $updateQuery = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sss", $token, $expiry, $email);
            $updateStmt->execute();

            // Prepare password reset email
            $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token; // Adjust the URL as needed
            $subject = "Password Reset Request";
            $message = "Please click the following link to reset your password: " . $resetLink;
            $headers = "From: noreply@yourwebsite.com"; // Change to your website's email

            // Send the email
            if (mail($email, $subject, $message, $headers)) {
                echo "A password reset link has been sent to your email.";
            } else {
                echo "Unable to send email. Please try again later.";
            }
        } else {
            echo "No account found with that email address.";
        }
    } else {
        echo "Invalid email format.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <h2>Forgot Password</h2>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p class="link">Remembered your password? <a href="index.php">Login</a></p>
    </div>
</body>
</html>
