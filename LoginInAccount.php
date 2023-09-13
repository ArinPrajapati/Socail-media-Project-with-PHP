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
        header('Location: homepage.php');
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
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="./index.css">
    <title>Document</title>
</head>

<body class="bg-blue-600">
    <div class="border w-[25rem] h-[30rem] p-[5rem] pt-[2.4rem] flex flex-col justify-center items-center absolute top-[6rem] left-[26rem] shadow-2xl bg-white">
        <h2 class="text-[1.9rem] text-center text-blue-500 mb-10 mt-3 w-full font-bold">Login to Your Account</h2>
        <form method="POST" action="loginInAccount.php">

            <input class="inputF" type="text" name="username" placeholder="👤 Username" required><br><br>


            <input class="inputF" type="password" name="password" placeholder="🔒 Password"><br><br>

            <input type="submit" class="bg-gradient-to-r from-blue-500 via-blue-400 to-blue-500 hover:from-blue-400 hover:via-blue-500 hover:to-blue-400 focus:outline-none border-none text-white font-bold py-2.5 px-4 rounded-full transform transition-transform hover:scale-95 cursor-pointer"  value="Login">


        </form>
        <p class="mt-3">No Account ? <a class="text-blue-500" href="CreateAccount.php">Create Account</a></p>
    </div>

</body>

</html>