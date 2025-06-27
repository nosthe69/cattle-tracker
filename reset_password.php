<?php
// Include database connection
include 'db_connection.php'; // Adjust the path as needed

// Check if the token is set in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $query = "SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "Invalid or expired token.";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Check if passwords match
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password and clear the reset token and expiry
            $updateQuery = "UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $hashedPassword, $token);
            $updateStmt->execute();

            echo "Your password has been successfully reset. You can now <a href='index.php'>login</a>.";
            exit();
        } else {
            $error = "Passwords do not match.";
        }
    }
} else {
    echo "No token provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <h2>Reset Password</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit">Reset Password</button>
        </form>
        <p class="link">Remembered your password? <a href="index.php">Login</a></p>
    </div>
</body>
</html>
