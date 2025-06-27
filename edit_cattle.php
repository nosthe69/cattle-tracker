<?php
session_start(); // Start session

// Include database connection
include('db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the cattle ID from the URL
if (!isset($_GET['id'])) {
    die("Cattle ID not specified.");
}
$cattleId = intval($_GET['id']);

// Fetch the current cattle data from the database
$stmt = $conn->prepare("SELECT name, breed, gender, color, age FROM cattle WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cattleId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cattle not found.");
}

$cattle = $result->fetch_assoc();
$stmt->close();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $cattleName = htmlspecialchars(trim($_POST['cattle_name']));
    $cattleBreed = htmlspecialchars(trim($_POST['cattle_breed']));
    $cattleGender = htmlspecialchars(trim($_POST['cattle_gender'])); // New gender input
    $cattleColor = htmlspecialchars(trim($_POST['cattle_color'])); // New color input
    $cattleAge = intval($_POST['cattle_age']); // Ensure age is an integer

    // Prepare the SQL query to update cattle data
    $stmt = $conn->prepare("UPDATE cattle SET name = ?, breed = ?, gender = ?, color = ?, age = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssiii", $cattleName, $cattleBreed, $cattleGender, $cattleColor, $cattleAge, $cattleId, $userId);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to a confirmation page or back to the cattle list
        header("Location: profile.php?success=1");
        exit();
    } else {
        echo "Error updating cattle: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- HTML Form for Editing Cattle -->
<h3>Edit Cattle</h3>
<form action="" method="POST">
    <label for="cattle-name">Cattle Name:</label>
    <input type="text" id="cattle-name" name="cattle_name" value="<?php echo htmlspecialchars($cattle['name']); ?>" required>

    <label for="cattle-breed">Breed:</label>
    <input type="text" id="cattle-breed" name="cattle_breed" value="<?php echo htmlspecialchars($cattle['breed']); ?>" required>

    <label for="cattle-gender">Gender:</label>
    <select id="cattle-gender" name="cattle_gender" required>
        <option value="">Select Gender</option>
        <option value="Male" <?php echo ($cattle['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($cattle['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
    </select>

    <label for="cattle-color">Color:</label>
    <input type="text" id="cattle-color" name="cattle_color" value="<?php echo htmlspecialchars($cattle['color']); ?>" required>

    <label for="cattle-age">Age:</label>
    <input type="number" id="cattle-age" name="cattle_age" value="<?php echo htmlspecialchars($cattle['age']); ?>" required>

    <!-- Submit button to update cattle -->
    <button class="btn-filter" type="submit">Update Cattle</button>
</form>
