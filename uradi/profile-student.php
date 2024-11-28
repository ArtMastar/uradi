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
    $profession = $_POST['profession'];
    $university = $_POST['university'];
    $course = $_POST['course'];
    $skills = $_POST['skills'];
    $contact = $_POST['contact'];

    // Check if the profile exists
    $stmt = $conn->prepare("SELECT * FROM profile_student WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing profile
        $stmt = $conn->prepare("UPDATE profile_student SET name = ?, profession = ?, university = ?, course = ?, skills = ?, contact = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $name, $profession, $university, $course, $skills, $contact, $user_id);
    } else {
        // Insert a new profile
        $stmt = $conn->prepare("INSERT INTO profile_student (user_id, name, profession, university, course, skills, contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $name, $profession, $university, $course, $skills, $contact);
    }

    if ($stmt->execute()) {
        header("Location: profile-student-disp.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}

// Fetch user data to populate the form
$stmt = $conn->prepare("SELECT * FROM profile_student WHERE id = ?");
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
                <form method="POST" action="profile-student.php">
                    <h3>Update Profile</h3>
                    <input type="text" name="name" placeholder="Name" value="<?= $profile['name'] ?? '' ?>" required>
                    <input type="text" name="profession" placeholder="Profession" value="<?= $profile['profession'] ?? '' ?>" required>
                    <input type="text" name="university" placeholder="University" value="<?= $profile['university'] ?? '' ?>" required>
                    <input type="text" name="course" placeholder="Course" value="<?= $profile['course'] ?? '' ?>" required>
                    <input type="text" name="skills" placeholder="Skills" value="<?= $profile['skills'] ?? '' ?>" required>
                    <input type="text" name="contact" placeholder="Contact" value="<?= $profile['contact'] ?? '' ?>" required>
                    <button type="submit">Save</button>
                    <button type="button"><a href="profile-student-disp.php">Cancel</a></button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
