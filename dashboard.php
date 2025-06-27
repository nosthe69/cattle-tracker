<?php
session_start();
// Check if the user is logged in; if not, redirect to login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require_once 'db_connect.php';

// Get user ID from session (assuming user is logged in)
$user_id = $_SESSION['user_id'];

// Fetch notifications from cattle_reports table
$sql = "SELECT id,message, created_at, is_read
        FROM notifications 
        WHERE user_id = '$user_id' 
        ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cattle Tracker Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" /> <!-- FontAwesome Icons -->

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

        .icons {
            position: relative;
            cursor: pointer;
        }

        .icons .fas {
            font-size: 24px;
            color: #ff7200;
        }

        /* Notification Dropdown */
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 1000;
            width: 300px;
            right: 0;
            top: 35px;
            border-radius: 5px;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background-color: #f9f9f9;
        }

        .unread {
            font-weight: bold;
            color: black;
        }

        .read {
            font-weight: normal;
            color: gray;
        }

        .message-preview {
            margin: 0;
            padding: 0;
        }

        /* Heatmap Section */
        .heatmap-container {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        #heatmap {
            height: 400px;
            width: 80%;
        }

        .legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 20%;
        }

        .legend h4 {
            font-size: 16px;
        }

        .legend p {
            font-size: 14px;
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

    </style>
</head>
<body>
    <div class="logged-in-main">
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
                <h1>Dashboard</h1>
                

                <!-- Notification Bell Icon -->
                <div class="icons">
                    <div class="dropdown">
                        <i class="fas fa-bell" id="notificationDropdown"></i>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                            <ul id="notification-list">
                                <?php if (!empty($notifications)): ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <li class="dropdown-item <?php echo ($notification['is_read'] == 1) ? 'read' : 'unread'; ?>" 
                                            data-id="<?php echo $notification['id']; ?>" 
                                            data-message="<?php echo htmlspecialchars($notification['message']); ?>" 
                                            data-time="<?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?>">
                                            <p class="message-preview"><?php echo substr(htmlspecialchars($notification['message']), 0, 50); ?>...</p>
                                            <small><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></small>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="dropdown-item">No notifications available.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>

            <div class="content">
                <!-- Overall Community Stats -->
                <div class="community-stats">
                    <div class="stat-item">
                        <h3>Total Number of Registered Cattle</h3>
                        <p id="total-cattle">8</p>
                    </div>
                    <div class="stat-item">
                        <h3>Total Number of Active Users</h3>
                        <p id="total-active-users">5</p>
                    </div>
                    <div class="stat-item">
                        <h3>Total Number of Lost Cattle Reports</h3>
                        <p id="total-lost-cattle-reports">1</p>
                    </div>
                    <div class="stat-item">
                        <h3>Total Number of Recovered Cattle Reports</h3>
                        <p id="total-recovered-cattle-reports">6</p>
                    </div>
                </div>

                

                

                <!-- Cattle Heatmap -->
                <div class="widget">
                    <h3>Cattle Heatmap</h3>
                    <div class="heatmap-container">
                        <div id="heatmap"></div>
                        <div class="legend">
                            <h4>Heatmap Legend</h4>
                            <p><strong>High Intensity:</strong> Areas where cattle are frequently reported as lost or found.</p>
                            <p><strong>Low Intensity:</strong> Areas with fewer reports.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('notificationDropdown').addEventListener('click', function() {
            var dropdownMenu = this.nextElementSibling;
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Show modal with the full message and mark notification as read
        document.querySelectorAll('.dropdown-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var message = this.getAttribute('data-message');
                var time = this.getAttribute('data-time');
                var notificationId = this.getAttribute('data-id');

                // Show modal with full message
                alert(`Message: ${message}\nTime: ${time}`);

                // Mark notification as read (update the class and style)
                this.classList.remove('unread');
                this.classList.add('read');

                // Send AJAX request to mark as read in the database
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'mark_notification_read.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.send('id=' + notificationId);
            });
        });



    </script>
    <script src="dashboard.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script src="heatmap.js"></script>
</body>
</html>
