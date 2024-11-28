<?php
session_start();

// Ensure user is logged in and is a company
// if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'company') {
//     header("Location: index.html");
//     exit();
// }

// Fetch the company ID from the session
$company_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch applications for jobs posted by this company
$sql = "
    SELECT 
        applications.id AS application_id,
        applications.job_title,
        applications.first_name,
        applications.last_name,
        applications.email,
        applications.university_name,
        applications.additional_message,
        job_listings.company_name
    FROM 
        applications
    JOIN 
        job_listings 
    ON 
        applications.job_id = job_listings.id
    WHERE 
        job_listings.company_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
// echo $result;
$applications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $applications[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applicants</title>
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
            <h2>My Applicants</h2>
            <div class="applicants">
                <?php if (empty($applications)): ?>
                    <p>No applications found for your job postings.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>University</th>
                                <th>Additional Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($applications as $application): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                    <td><?php echo htmlspecialchars($application['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($application['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($application['email']); ?></td>
                                    <td><?php echo htmlspecialchars($application['university_name']); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($application['additional_message'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
