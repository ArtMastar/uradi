<?php
session_start();

// Ensure user is logged in and user_type is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch job listings from the database
$sql = "SELECT * FROM job_listings";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="dashboard-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .job-description {
            display: none; /* Hide description by default */
            padding: 10px;
            background-color: #f4f4f4;
            margin-top: 10px;
        }
        .job-buttons {
            display: flex;
            gap: 10px;
        }
        .job-buttons button {
            padding: 10px;
            cursor: pointer;
        }
    </style>
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

            // Toggle visibility of the job description
            function toggleDescription(jobId) {
                var descriptionDiv = document.getElementById('desc-' + jobId);
                if (descriptionDiv.style.display === 'none' || descriptionDiv.style.display === '') {
                    descriptionDiv.style.display = 'block';
                } else {
                    descriptionDiv.style.display = 'none';
                }
            }

            // Apply for the job (placeholder function)
            function applyForJob(jobId) {
                // Redirect to the application page or open a form to apply
                // alert("Application for job ID: " + jobId + " submitted!");
                window.location.href = 'application.php?job_id=' + jobId;
            }

            
        </script>

        <!-- Main Content -->
        <main>
            <h2>Job Listings</h2>
            <div class="job-listings">
                <?php
                // Check if there are any job listings
                if ($result->num_rows > 0) {
                    // Output data for each job listing
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="job-listing">';
                        echo '<p>' . htmlspecialchars($row['job_title']) . ' - ' . htmlspecialchars($row['company_name']) . '</p>';
                        // Add a "More" button that calls the toggleDescription function
                        echo '<div class="job-buttons">';
                        echo '<button onclick="toggleDescription(' . $row['id'] . ')">More</button>';
                        // Add the "Apply" button next to the "More" button
                        echo '<button onclick="applyForJob(' . $row['id'] . ')">Apply</button>';
                        echo '</div>';
                        // Add a hidden div that holds the full description
                        echo '<div id="desc-' . $row['id'] . '" class="job-description">';
                        echo '<strong>Job Description:</strong><p>' . nl2br(htmlspecialchars($row['job_description'])) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No job listings available.</p>';
                }
                ?>
            </div>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
