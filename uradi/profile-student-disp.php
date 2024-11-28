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

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch profile data from the profile_company table
$stmt = $conn->prepare("SELECT * FROM profile_student WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a profile exists
if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc(); // Fetch the profile data
} else {
    $profile = null; // If no profile exists, set $profile to null
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="content-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <ul>
                <!-- <li><i class="fa fa-home"></i><a href="dashboard.php">Dashboard</a></li> -->
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
        <main class="profile-content">
            <div class="profile-header">
                <div class="profile-info">
                    <h1>
                        <?= $profile['name'] ?? 'Name Not Set' ?>
                    </h1>
                    <p>
                        <?= $profile['profession'] ?? 'Profession Not Set' ?>
                    </p>
                </div>
            </div>

            <div class="profile-details">
                <div class="details-row">
                    <?= $profile['university'] ?? 'University Not Set' ?>
                </div>
                <div class="details-row">
                    <?= $profile['course'] ?? 'Course Not Set' ?>
                </div>
                <div class="details-row">
                    <?= $profile['skills'] ?? 'Skills Not Set' ?>
                </div>
                <div class="details-row">
                    <?= $profile['contact'] ?? 'Contact Not Set' ?>
                </div>
            </div>

            <div class="edit-profile">
                <button><a href="profile-edit.php">Edit Profile</a></button>
            </div>
        </main>
    </div>
</body>
<script>
    document.getElementById('edit-profile').addEventListener('click', () => {
        // Access the cookies in JavaScript
        let userId = document.cookie.replace(/(?:(?:^|.*;\s*)user_id\s*=\s*([^;]*).*$)|^.*$/, "$1");
        let email = document.cookie.replace(/(?:(?:^|.*;\s*)email\s*=\s*([^;]*).*$)|^.*$/, "$1");
        let userType = document.cookie.replace(/(?:(?:^|.*;\s*)user_type\s*=\s*([^;]*).*$)|^.*$/, "$1");

        console.log(`user : ${userId}, ${email}, ${userType}`);

    })
</script>

</html>