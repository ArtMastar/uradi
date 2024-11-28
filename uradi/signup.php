<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get data from the form
    $userType = $_POST['user_type'];          // 'student' or 'company'
    $name = $_POST['name'];
    $university = $_POST['university'];
    $companyName = $_POST['companyName'];    // For companies
    $industry = $_POST['industry'];         // For companies
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash password for security

    // Prepare the SQL query to insert data into the 'users' table
    $stmt = $conn->prepare("INSERT INTO users (user_type, name, university, company_name, industry, email, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $userType, $name, $university, $companyName, $industry, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Sign-up successful!'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'index.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
