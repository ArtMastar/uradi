<?php
session_start();
// Ensure user is logged in and user_type is set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
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
                // alert('userType'+userType);
                // Redirect based on user type
                if (userType.toLowerCase() === "student") {
                    window.location.href = "profile-student-disp.php";
                } else if (userType.toLowerCase() === "company") {
                    window.location.href = "profile-company-disp.php";
                } else {
                    console.log("Unknown user type: " + userType);
                    // Optionally, handle the case where user_type is unexpected
                }
            });
        </script>
        

        <!-- Main Content -->
        <main>
            <h2>Software Engineer - Tech Industry</h2>
            <p><strong>Description:</strong> This is a full-time opportunity for a software engineer to work on developing innovative solutions...</p>
            <p><strong>Requirements:</strong> Proficiency in JavaScript, Node.js, and React.js.</p>

            <h3>Apply for this Job</h3>
            <form class="apply-form">
                <input type="text" placeholder="Name" required>
                <input type="email" placeholder="Email" required>
                <textarea placeholder="Why are you a good fit?" required></textarea>
                <button type="submit">Apply</button>
            </form>
        </main>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
