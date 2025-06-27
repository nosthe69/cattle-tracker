<?php
session_start(); // Start the session
include('db_connect.php'); // Include your database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Cattle</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Add your CSS file for overall page style -->
    <script src="https://unpkg.com/@zxing/library@0.18.6/umd/index.min.js"></script>


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


        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .qr-section, .form-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            width: 48%;
        }

        .qr-section h2, .form-section h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4CAF50;
        }

        #qr-reader {
            background-color: #f0f0f0;
            border-radius: 10px;
            display: none;
        }

        .qr-instructions {
            text-align: center;
            margin-top: 10px;
            color: #777;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: 600;
        }

        input[type="text"], textarea, select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .banner {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .banner.success {
            background-color: #4CAF50;
            color: white;
        }

        .banner.error {
            background-color: #f44336;
            color: white;
        }

        .recent-activity {
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .activity-feed {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-feed li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .activity-feed li:last-child {
            border-bottom: none;
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
<?php


    // Start session and display banners if needed
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['report_success'])): ?>
        <div class="banner success" style="background-color: #4CAF50; color: white; padding: 10px; text-align: center;">
            <?php echo $_SESSION['report_success']; ?>
        </div>
        <?php unset($_SESSION['report_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['report_error'])): ?>
        <div class="banner error" style="background-color: #f44336; color: white; padding: 10px; text-align: center;">
            <?php echo $_SESSION['report_error']; ?>
        </div>
        <?php unset($_SESSION['report_error']); ?>
    <?php endif; ?>

    <div class="main-content">
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

        <div class="container">
            <!-- QR Code Scanning Section -->
            <div class="qr-section">


                <h2>Scan QR Code</h2>
                <p class="qr-instructions">Scan the QR code attached to the cow’s tag.</p>
                <!-- Success and error banners -->
                <div id="scan-success-banner" class="banner success" style="color:green;display: none;text-align:center;">
                    You have successfully scanned <span id="cow-name"></span> 
                </div>
                <div id="scan-error-banner" class="banner error" style="color:red;display: none;text-align:center;" >
                    QR code scan failed. Please try again or upload a clearer image.
                </div>
                <video id="qr-reader" style="display: none;"></video> <!-- Use video for live feed -->
                <button id="start-scan">Start QR Code Scan</button>
                
                <!-- Upload QR Code Image for scanning -->
                <h3>Or Upload QR Code Image</h3>
                <input type="file" id="qr-image-upload" accept="image/*">
                <button id="upload-scan">Scan Uploaded Image</button>
                
            </div>


            <!-- Details Form Section -->
            <div class="form-section">
            <h2>Report Cattle Sighting</h2>
            <form id="sighting-form" action="submit_report.php" method="POST" onsubmit="return validateForm()">
                <!-- Location (auto-filled via geolocation) -->
                <label for="location">Location (Auto-Detected)</label>
                <input type="text" id="location" name="location" readonly placeholder="Location being detected...">

                <!-- Optional manual location input -->
                <label for="manual-location">Specify Location (Optional)</label>
                <input type="text" id="manual-location" name="manual_location" placeholder="Enter a landmark or address">

                <!-- Additional Information -->
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Describe the situation..." required></textarea>

                <!-- User Type Dropdown -->
                <label for="user-type">User Type</label>
                <select id="user-type" name="user_type" required>
                    <option value="Community Member">Community Member</option>
                    <option value="Municipal Authority">Municipal Authority</option>
                </select>

                <input type="hidden" id="cow_name" name="cow_name" value="">

                <!-- Hidden field for coordinates -->
                <input type="hidden" id="coordinates" name="coordinates" value="">

                <!-- Hidden field for cow_id (should be filled by QR code scan) -->
                <input type="hidden" id="cow-id" name="cow_id" value="">
                
                

                <!-- Error message for validation -->
                <div id="form-error" style="color: red; display: none;">Please scan a valid QR code before submitting.</div>

                <button type="submit">Submit Report</button>
            </form>
        </div>
        </div>

       
    </div>

    <!-- QR Code and Geolocation JavaScript -->
    <script>
        const codeReader = new ZXing.BrowserQRCodeReader(); // Initialize ZXing code reader

        document.getElementById("start-scan").addEventListener("click", function () {
            console.log("Starting QR Code scan..."); // Log button click

            document.getElementById("qr-reader").style.display = "block"; // Show the video feed

            // Get the video devices (cameras)
            codeReader.getVideoInputDevices().then((videoInputDevices) => {
                const firstDeviceId = videoInputDevices[0].deviceId;

                // Start decoding from the camera
                codeReader.decodeFromVideoDevice(firstDeviceId, 'qr-reader', (result, err) => {
                    if (result) {
                        console.log(result.text); // Log the decoded QR code content
                        document.getElementById('cow-id').value = result.text; // Set the hidden input to the decoded value
                        
                        

                        // Parse the result for cow details (assuming JSON format)
                        let cowDetails = result.text;
                        document.getElementById('cow-name').textContent = cowDetails.Name; // Set cow name in banner

                        // Show success banner and hide error banner
                        document.getElementById('scan-success-banner').style.display = 'block';
                        document.getElementById('scan-error-banner').style.display = 'none';

                        codeReader.reset(); // Stop the scanner once a code is detected
                        document.getElementById("qr-reader").style.display = "block"; // Hide the video feed
                        hideBanners(); // Optionally hide the banners after 5 seconds
                    }

                    if (err && !(err instanceof ZXing.NotFoundException)) {
                        console.error(err); // Log any errors (except not found)

                        // Show error banner
                        document.getElementById('scan-error-banner').style.display = 'block';
                        document.getElementById('scan-success-banner').style.display = 'none';

                        hideBanners(); // Optionally hide the banners after 5 seconds
                    }
                });
            }).catch((err) => {
                console.error(err); // Log camera access errors
            });
        });


        // Event listener for the image upload scan button
        document.getElementById('upload-scan').addEventListener('click', function() {
            const input = document.getElementById('qr-image-upload');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        codeReader.decodeFromImage(img)
                            .then(result => {
                                console.log('QR Code Scanned from Image:', result.text);
                                
                                // Assuming result.text is in the format: 
                                // "Cattle ID: 7, Name: Jamludi, Breed: Nguni, Gender: Male, Color: Impunga, Age: 3"
                                const details = result.text.split(',').reduce((acc, item) => {
                                    const [key, value] = item.split(':').map(part => part.trim());
                                    acc[key] = value; // Create an object with key-value pairs
                                    return acc;
                                }, {});
                                
                                // Set the cow_id from the parsed details
                                document.getElementById('cow-id').value = details['Cattle ID'];
                                document.getElementById('cow-name').textContent = details['Name']; // Set cow name in banner
                                document.getElementById('cow_name').value =details['Name'];

                                console.log(document.getElementById('cow-id').value);


                                // Show success banner
                                document.getElementById('scan-success-banner').style.display = 'block';
                                document.getElementById('scan-error-banner').style.display = 'none';

                                hideBanners(); // Optionally hide the banners after 5 seconds
                            })
                            .catch(err => {
                                console.error('Failed to scan QR code from image:', err);

                                // Show error banner
                                document.getElementById('scan-error-banner').style.display = 'block';
                                document.getElementById('scan-success-banner').style.display = 'none';

                                hideBanners(); // Optionally hide the banners after 5 seconds
                            });

                    };
                };
                reader.readAsDataURL(file);
            } else {
                console.log('No image file selected for scanning.');
            }
        });

        //Hidding the banners
        function hideBanners() {
            setTimeout(function() {
                document.getElementById('scan-success-banner').style.display = 'none';
                document.getElementById('scan-error-banner').style.display = 'none';
            }, 5000); // 5 seconds timeout
        }

        function validateForm() {
            const cowId = document.getElementById('cow-id').value;
            const errorDiv = document.getElementById('form-error');
            
            if (!cowId) {
                // If cow_id is empty, prevent form submission and show error
                errorDiv.style.display = 'block';
                errorDiv.textContent = "Please scan a valid QR code before submitting.";
                return false; // Prevent form submission
            }

            // Optionally check if cow_id is valid using an AJAX call
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "check_cow.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (!response.valid) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = "Sorry, the scanned QR code does not match any cattle records or the cow is no longer registered.";
                        return false; // Prevent form submission
                    } else {
                        errorDiv.style.display = 'none';
                    }
                }
            };
            xhr.send("cow_id=" + cowId);
            
            return true; // Allow form submission if all checks pass
        }




        // Geolocation for capturing current coordinates
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById('coordinates').value = `${position.coords.latitude},${position.coords.longitude}`;
                document.getElementById('location').value = `Lat: ${position.coords.latitude}, Long: ${position.coords.longitude}`;
            });
        } else {
            document.getElementById('location').value = "Geolocation not supported by your browser.";
        }
    </script>
</body>
</html>
