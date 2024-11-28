<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: index.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $name = $_POST['name'];
    $profession = $_POST['profession'];
    $university = $_POST['university'];
    $course = $_POST['course'];
    $skills = $_POST['skills'];
    $contact = $_POST['contact'];

    // Check if the profile exists for this user
    $stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Profile exists, update it
        $stmt = $conn->prepare("UPDATE profiles SET name = ?, profession = ?, university = ?, course = ?, skills = ?, contact = ? WHERE user_id = ?");
        $stmt->bind_param("ssssssi", $name, $profession, $university, $course, $skills, $contact, $user_id);
    } else {
        // Profile doesn't exist, insert new profile
        $stmt = $conn->prepare("INSERT INTO profiles (user_id, name, profession, university, course, skills, contact) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $name, $profession, $university, $course, $skills, $contact);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile-student.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
