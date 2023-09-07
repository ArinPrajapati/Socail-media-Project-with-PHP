<?php
$host = 'localhost';
$dbname = 'socailmedia';
$username = 'root';
$password = '';



try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . (int)$e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);



    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $db->prepare($sql);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try {
        $stmt->execute();
        echo "Registration successful!";
        header('Location: LoginInAccount.php');
    } catch (PDOException $e) {
        echo "Registration failed: " . $e->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./index.css">
</head>

<body class="bg-blue-600">
    <div class="border w-[25rem] h-[30rem] p-[5rem] pt-[2.4rem] flex flex-col justify-center items-center absolute top-[6rem] left-[26rem] shadow-2xl bg-white">
        <h2 class="text-[1.9rem] text-center text-blue-500 mb-10 mt-3 w-full font-bold">Create a New Account</h2>
        <form method="POST" action="CreateAccount.php">

            <input type="text" class="inputF" name="username" placeholder="👤 Username" required><br><br>


            <input type="email" class="inputF" name="email" placeholder="📧 Email" required><br><br>

            <input type="password" class="inputF" name="password" placeholder="🔒 Password" required><br><br>

            <input type="submit" class="bg-gradient-to-r from-blue-500 via-blue-400 to-blue-500 hover:from-blue-400 hover:via-blue-500 hover:to-blue-400 focus:outline-none border-none text-white font-bold py-2.5 px-4 rounded-full transform transition-transform hover:scale-95 cursor-pointer" value="Register">
        </form>

        <p class="mt-3">Already have a account <a class="text-blue-700" href="LoginInAccount.php">Login</a></p>
    </div>

</body>

</html>