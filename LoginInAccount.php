<?php
$host = 'localhost';
$dbname = 'socailmedia';
$username = 'root';
$password = '';


try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];


    $sql = "SELECT id, username, email, password FROM users WHERE username = :username OR email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $usernameOrEmail);
    $stmt->bindParam(':email', $usernameOrEmail);

    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php'); 
        exit();
    } else {
        echo "Login failed. Please check your username/email and password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Login to your account</h2>
    <form method="POST" action="login.php">
        <label for="username">Username or Email:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>

</html>