<?php
session_start(); // Start the session to store user info

// Database connection
$conn = new mysqli("localhost", "root", "", "uradi");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the posted email and password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize email
    $email = $conn->real_escape_string($email);

    // Prepare SQL query to fetch user details from the database
    $stmt = $conn->prepare("SELECT id, password_hash, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            // Correct credentials, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user['user_type'];
            // PHP code (set cookies for session data)
            setcookie('user_id', $_SESSION['user_id'], time() + 3600, '/'); // expires in 1 hour
            setcookie('email', $_SESSION['email'], time() + 3600, '/');
            setcookie('user_type', $_SESSION['user_type'], time() + 3600, '/');

            // Send success response with user type
            echo json_encode([
                'success' => true,
                'user_type' => $user['user_type']
            ]);
        } else {
            // Incorrect password
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        // User does not exist
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

    $stmt->close();
    $conn->close();
}
?>