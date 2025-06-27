<?php
session_start(); // Start session

// Include database connection
include('C:/xampp/htdocs/CattleTrackerApp/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user information
$stmt = $conn->prepare("SELECT first_name, last_name, email, phone_number,address , profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Fetch user data as an associative array

    // Assign the values to variables for easy use
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $email = $user['email'];
    $phone = $user['phone_number'];
    $address = $user['address'];
    $profilePicture = $user['profile_picture'] ?? 'default.png'; // Use 'default.png' if no profile picture is set
} else {
    die("User not found."); // Handle the case where the user is not found
}
$stmt->close(); // Close the statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
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
                <h1>User Profile</h1>
            </div>

            <!-- Profile Display Section -->
            <div id="profile-display">
                <h2>Personal Information</h2>
                
                <p><strong>Name:</strong> <span id="display-name"><?php echo htmlspecialchars($firstName . " " . $lastName); ?></span></p>
                <p><strong>Email:</strong> <span id="display-email"><?php echo htmlspecialchars($email); ?></span></p>
                <p><strong>Phone Number:</strong> <span id="display-phone"><?php echo htmlspecialchars($phone); ?></span></p>
            </div>

            <button class="button" id="edit-button" onclick="editProfile()">Edit</button> <!-- Single Edit Button -->

            <!-- Profile Edit Form -->
            <div id="profile-edit" style="display: none;">
                <h2>Edit Personal Information</h2>
                <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
                    
                    <label for="profile-picture">Profile Picture:</label>
                    <!-- Display current profile picture -->
                    <?php if (!empty($profilePicture)): ?>
                        <div>
                            <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Current Profile Picture" style="width:100px; height:auto;">
                            <p>Current Picture</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="profile-picture" name="profile_picture" accept="image/*">

                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>

                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" required>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>

                    <button class="button" type="button" onclick="cancelEditProfile()">Cancel</button>

                    <button class="button" type="submit">Update</button> <!-- Submit Button -->
                </form>
            </div>

            <!-- Cattle Information Section -->
            <div class="content">
                <h2>Cattle Information</h2>
                <p>Manage your cattle below. You can remove any cattle you no longer own or have lost, and add new cattle.</p>

                <!-- Cattle List Table -->
                <div id="cattle-list">
                    <h3>Your Cattle List</h3>
                    <table id="cattle-table">
                        <thead>
                            <tr>
                                <th>Cattle ID</th>
                                <th>Cattle Name</th>
                                <th>Breed</th>
                                <th>Gender</th> <!-- Added Gender Column -->
                                <th>Color</th>  <!-- Added Color Column -->
                                <th>QR Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch user's cattle data from the database
                            $stmt = $conn->prepare("SELECT id, name, breed, gender, color, qr_code_path FROM cattle WHERE user_id = ?");
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Dynamically populate the table with user's cattle data
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>" . htmlspecialchars($row['name']) . "</td>
                                        <td>" . htmlspecialchars($row['breed']) . "</td>
                                        <td>" . htmlspecialchars($row['gender']) . "</td> <!-- Display Gender --> 
                                        <td>" . htmlspecialchars($row['color']) . "</td> <!-- Display Color -->
                                        <td>
                                            <a href='#qrModal' onclick=\"showQRModal('" . htmlspecialchars($row['qr_code_path']) . "')\">
                                                <img src='" . htmlspecialchars($row['qr_code_path']) . "' alt='QR Code' width='50'>
                                            </a>
                                        </td>
                                        <td>
                                            <a href='edit_cattle.php?id={$row['id']}'>Edit</a> | 
                                            <a href='delete_cattle.php?id={$row['id']}'>Delete</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No cattle found.</td></tr>";
                            }

                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- QR Code Modal -->
                <div id="qrModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeQRModal()">&times;</span>
                        <img id="qrModalImage" src="" alt="QR Code" style="width:100%; max-width:400px;">
                        <a id="qrDownloadLink" href="#" download="qr-code.png">Download QR Code</a>
                    </div>
                </div>

                <!-- Modal CSS -->
                <style>
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.8);
                }

                .modal-content {
                    margin: 15% auto;
                    padding: 20px;
                    width: 90%;
                    max-width: 500px;
                    text-align: center;
                }

                .close-btn {
                    color: white;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }

                .close-btn:hover,
                .close-btn:focus {
                    color: #999;
                }

                #qrModalImage {
                    width: 100%;
                    max-width: 400px;
                }
                </style>

                <script>
                // Function to show the modal with the clicked QR code
                function showQRModal(qrImagePath) {
                    // Set the image source and download link
                    document.getElementById('qrModalImage').src = qrImagePath;
                    document.getElementById('qrDownloadLink').href = qrImagePath;

                    // Display the modal
                    document.getElementById('qrModal').style.display = 'block';
                }

                // Function to close the modal
                function closeQRModal() {
                    document.getElementById('qrModal').style.display = 'none';
                }

                // Function to edit profile
                function editProfile() {
                    // Hide the "Edit" button after it's clicked
                    document.getElementById('edit-button').style.display = 'none';

                    // Show the edit form
                    document.getElementById('profile-display').style.display = 'none';
                    document.getElementById('profile-edit').style.display = 'block';
                }

                function cancelEditProfile() {
                    // Show the "Edit" button when cancel is clicked
                    document.getElementById('edit-button').style.display = 'inline';

                    // Hide the edit form and show the profile display section again
                    document.getElementById('profile-edit').style.display = 'none';
                    document.getElementById('profile-display').style.display = 'block';
                }
                </script>

                <!-- Button to add new cattle -->
                <button id="add-cattle-btn" class="button">Add New Cattle</button>

            <!-- Cattle Capture Form -->
                <div id="add-cattle-form" style="display: none;">
                    <h3>Add New Cattle</h3>
                    <form action="add_cattle.php" method="POST">
                        <label for="cattle-name">Cattle Name:</label>
                        <input type="text" id="cattle-name" name="cattle_name" required>

                        <label for="cattle-breed">Breed:</label>
                        <input type="text" id="cattle-breed" name="cattle_breed" required>

                        <label for="cattle-gender">Gender:</label>
                        <select id="cattle-gender" name="cattle_gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>

                        <label for="cattle-color">Color:</label>
                        <input type="text" id="cattle-color" name="cattle_color" required>

                        <label for="cattle-age">Age:</label>
                        <input type="number" id="cattle-age" name="cattle_age" required>

                        <!-- Submit button to add cattle and generate QR code -->
                        <button class="button" type="submit">Add Cattle and Generate QR Code</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
    // Function to toggle the "Add New Cattle" form
    document.getElementById('add-cattle-btn').addEventListener('click', function() {
        const form = document.getElementById('add-cattle-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
    </script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
