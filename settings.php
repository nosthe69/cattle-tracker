<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .button {
                background-color: #ff7200;
                padding: 10px 20px;
                border-radius: 5px;
                border: none;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s;
        }

        .button:hover {
                background-color: #fff;
                color: #ff7200;
        }
        /* Global Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .logged-in-main {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background-color: #333;
            padding: 20px;
            width: 250px;
            min-height: 100vh;
            color: #fff;
            text-align: center;
        }

        .sidebar img {
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar h2 {
            color: #ff7200;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            color: #ff7200;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
            font-size: 36px;
            letter-spacing: 1.5px;
        }
        /* Media Queries for Responsiveness */
        @media screen and (max-width: 768px) {
            .logged-in-main {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .main-content {
                padding: 20px;
            }
        }
        /* Add styling for message banners */
        .message {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            display: none; /* Hide initially */
        }

        .message.success {
            background-color: #4CAF50;
            color: white;
        }

        .message.error {
            background-color: #f44336;
            color: white;
        }

        /* Password strength bar styles */
        #strength-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
            height: 10px;
            margin-top: 5px;
            position: relative;
            display: inline-block;
            width: 200px; /* Shortened width */
        }
        #strength-indicator {
            height: 100%;
            width: 0;
            background-color: red; /* Default to red */
        }
        
    </style>
</head>
<body>
    <div class="main">
        <div class="sidebar">
            <img src="cattle2.jpg" class="rounded-circle" width="150">
            <h2>CattleTracker</h2>
            <ul>
                <li><a href="dashboard.php">HOME</a></li>
                <li><a href="track-cattle.php">TRACK CATTLE</a></li>
                <li><a href="profile.php">PROFILE</a></li>
                <li><a href="settings.php">SETTINGS</a></li>
                <li><a href="logout.php">LOGOUT</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Settings</h1>
            </div>

            <!-- Show message if set -->
            <?php 
            session_start(); // Ensure session is started at the top
            if (isset($_SESSION['message'])): ?>
                <div class="message <?= $_SESSION['msg_type']; ?>">
                    <?= $_SESSION['message']; ?>
                </div>
                <?php unset($_SESSION['message']); ?> <!-- Clear the message after displaying -->
            <?php endif; ?>

            <div class="content">
                <h2>Account Settings</h2>
                <div class="settings-section">
                    <h3>Change Password</h3>
                    <form id="password-form" action="change_password.php" method="POST">
                        <label for="current-password">Current Password:</label>
                        <input type="password" id="current-password" name="current_password" required>
                        
                        <label for="new-password">New Password:</label>
                        <input type="password" id="new-password" name="new_password" required>
                        
                        <label for="confirm-password">Confirm New Password:</label>
                        <input type="password" id="confirm-password" name="confirm_password" required>

                        <!-- Password strength bar -->
                        <div id="strength-bar">
                            <div id="strength-indicator"></div>
                        </div>
                        <p id="password-strength-text"></p>

                        <button class="button" type="submit">Change Password</button>
                    </form>

                    <p>Password must meet the following requirements:</p>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>At least 1 uppercase letter</li>
                        <li>At least 1 lowercase letter</li>
                        <li>At least 1 number</li>
                        <li>At least 1 special character</li>
                    </ul>
                </div>

                <h2>Display Settings</h2>
                <div class="settings-section">
                    <h3>Dark/Light Mode</h3>
                    <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider round"></span>
                    </label>
                </div>

                
            </div>
        </div>
    </div>

    <script>
        // Display password strength
        function updatePasswordStrength(password) {
            const strengthIndicator = document.getElementById('strength-indicator');
            const strengthText = document.getElementById('password-strength-text');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[\W_]/.test(password)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    strengthIndicator.style.width = '20%';
                    strengthIndicator.style.backgroundColor = 'red';
                    strengthText.textContent = 'Very Weak';
                    break;
                case 2:
                    strengthIndicator.style.width = '40%';
                    strengthIndicator.style.backgroundColor = 'orange';
                    strengthText.textContent = 'Weak';
                    break;
                case 3:
                    strengthIndicator.style.width = '60%';
                    strengthIndicator.style.backgroundColor = 'yellow';
                    strengthText.textContent = 'Mild';
                    break;
                case 4:
                    strengthIndicator.style.width = '80%';
                    strengthIndicator.style.backgroundColor = 'lightgreen';
                    strengthText.textContent = 'Strong';
                    break;
                case 5:
                    strengthIndicator.style.width = '100%';
                    strengthIndicator.style.backgroundColor = 'green';
                    strengthText.textContent = 'Very Strong';
                    break;
            }
        }

        document.getElementById('new-password').addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });

        // Theme toggle functionality
        document.getElementById('theme-toggle').addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        });

        // Display the message for a few seconds and then hide it
        document.addEventListener('DOMContentLoaded', function () {
            const message = document.querySelector('.message');
            if (message) {
                message.style.display = 'block'; // Show the message
                setTimeout(() => {
                    message.style.display = 'none'; // Hide after 5 seconds
                }, 5000);
            }
        });
    </script>
</body>
</html>
