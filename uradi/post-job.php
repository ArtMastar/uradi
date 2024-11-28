<?php
// Start the session
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form data
    $company_name = $_POST['company_name'];
    $company_id = $_SESSION['user_id'];
    $field = $_POST['field'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];

    // Prepare the SQL statement to insert the data into the job_listings table
    $stmt = $conn->prepare("INSERT INTO job_listings (company_name, company_id, field, job_title, job_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $company_name, $company_id, $field, $job_title, $job_description);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to a success page or dashboard after posting the job
        header("Location: listing.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
