<?php
// Start the session (this should be at the top of the dashboard.php page)
session_start();

// Check if the user is logged in (session user_id exists)
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or display an error message if not logged in
    header('Location: login_form.php'); // Redirect to the login page
    exit();
}

// Now you can access user data from the session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// You can use $user_id and $username to display user-specific information on the dashboard
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>Your user ID: <?php echo $user_id; ?></p>

    <!-- Display other user-specific information here -->

    <a href="logout.php">Logout</a> <!-- Add a logout link to log out the user -->
</body>
</html>
