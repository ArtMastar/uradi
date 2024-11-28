<?php
session_start();
// Ensure user is logged in and user_type is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.html");
    exit();
}

// Check if job_id is passed via GET
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "uradi");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the job details based on the job_id
    $sql = "SELECT * FROM job_listings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if job exists
    if ($result->num_rows > 0) {
        $job = $result->fetch_assoc();
    } else {
        echo "<p>Job not found.</p>";
        exit();
    }
    $conn->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $university = $_POST['university'];
    $additional_message = $_POST['message'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "uradi");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert application into the applications table
    $sql = "INSERT INTO applications (job_id, job_title, company_name, position, first_name, last_name, email, university_name, additional_message) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $job_id, $job['job_title'], $job['company_name'], $job['job_title'], $first_name, $last_name, $email, $university, $additional_message);
    
    if ($stmt->execute()) {
        $alertMessage = "Application submitted successfully!";
    } else {
        $alertMessage = "Error submitting application: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="content-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul>
                <!-- <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Dashboard</a></li> --> -->
                <li><i class="fa fa-briefcase"></i><a href="listing.php">Listings</a></li>
                <li><i class="fa fa-file-alt"></i><a href="application.php">Application</a></li>
                <li><i class="fa fa-user-circle"></i><a href="#" id="profId">Profile</a></li>
                <li><i class="fa fa-sign-out-alt"></i><a href="index.html">Log Out</a></li>
            </ul>
        </aside>

        <script>
            // Get the user type from PHP session
            var userType = "<?php echo $_SESSION['user_type']; ?>";

            // Add an event listener to the Profile link
            document.getElementById("profId").addEventListener("click", function (event) {
                event.preventDefault();  // Prevent the default anchor behavior
                // Redirect based on user type
                if (userType.toLowerCase() === "student") {
                    window.location.href = "profile-student-disp.php";
                } else if (userType.toLowerCase() === "company") {
                    window.location.href = "profile-company-disp.php";
                } else {
                    console.log("Unknown user type: " + userType);
                }
            });
        </script>

        <!-- Main Content -->
        <main>
            <div class="application-container">
                <h2>Full job description including company, position, description, additional message</h2>
                
                <?php if (isset($job)): ?>
                    <div class="job-details">
                        <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($job['job_title']); ?></p>
                        <p><strong>Job Description:</strong> <?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
                    </div>
                <?php endif; ?>

                <div class="application-form">
                    <h3>APPLY</h3>
                    <form method="POST" action="application.php?job_id=<?php echo $job_id; ?>">
                        <div class="form-group">
                            <input type="text" placeholder="First Name" name="first_name" required>
                            <input type="text" placeholder="Last Name" name="last_name" required>
                            <input type="text" placeholder="Phone Number" name="phone" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="email@gmail.com" name="email" required>
                            <input type="text" placeholder="University Name" name="university" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Additional Message" name="message" required></textarea>
                        </div>
                        <button type="submit">Submit Application</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
