<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

?>
<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
</head>

<body>
    <h2>Welcome, <?php echo $username; ?>!</h2>
    <p>Your user ID: <?php echo $user_id; ?></p>



    <a href="logout.php">Logout</a>
</body>

</html>