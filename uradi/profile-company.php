<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form data
    $name = $_POST['name'];
    $field = $_POST['field'];
    $email = $_POST['email'];


    // Check if the profile exists
    $stmt = $conn->prepare("SELECT * FROM profile_company WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing profile
        // PHP logic here
        echo "<script>alert(`userid: $user_id`);</script>";

        $stmt = $conn->prepare("UPDATE profile_company SET name = ?, field = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $field, $email, $user_id);

    } else {
        // Insert a new profile
        $stmt = $conn->prepare("INSERT INTO profile_company (id, name, field, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $field, $email);
    }

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Fetch user data to populate the form
$stmt = $conn->prepare("SELECT * FROM profile_company WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="content-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="listing.php">Listings</a></li>
                <li><a href="application.php">Application</a></li>
                <li><a href="profile-student-disp.php">Profile</a></li>
                <li><a href="index.html">Log Out</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="profile-content">
            <div id="profile-edit">
                <form method="POST" action="profile-company.php">
                    <h3>Update Profile</h3>
                    <input type="text" name="name" placeholder="Name" value="<?= $profile_company['name'] ?? '' ?>"
                        required>
                    <input type="text" name="field" placeholder="Field" value="<?= $profile_company['field'] ?? '' ?>"
                        required>
                    <input type="text" name="email" placeholder="Email" value="<?= $profile_company['email'] ?? '' ?>"
                        required>
                    <button type="submit">Save</button>
                    <button type="button"><a href="dashboard.php">Cancel</a></button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>