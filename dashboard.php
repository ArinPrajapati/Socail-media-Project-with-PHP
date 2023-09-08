<?php
ini_set('memory_limit', '256M'); 
ini_set('max_execution_time', 300); 

// Start the session
session_start();


if (!isset($_SESSION['user_id'])) {
   
    header('Location: login_form.php');
    exit();
}

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

$user_id = $_SESSION['user_id'];
$sql = "SELECT post_id, content, image_base64, created_at FROM posts WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$userPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <a href="create_post.php" class="text-white bg-slate-500 px-4 py-2 my-12 active:scale-90">Create Post</a>
        <a href="logout.php" class="text-white bg-slate-500 px-4 mx-3 py-2 my-12 active:scale-90">Logout</a>

        <h3 class="text-xl font-semibold mb-5">Your Posts :</h3>

        <?php if (count($userPosts) > 0) : ?>
            <ul class="my-10 ">
                <?php foreach ($userPosts as $post) : ?>
                    <li class="mb-4 border rounded p-4 bg-white shadow-md w-[30rem]">
                        <p class="text-base"><?php echo $post['content']; ?></p>
                        <?php if (!empty($post['image_base64'])) : ?>
                            <?php
                            $decoded_image_data = gzuncompress(base64_decode($post['image_base64']));
                            if ($decoded_image_data === false) {
                               echo ' <img src="https://t3.ftcdn.net/jpg/02/48/42/64/360_F_248426448_NVKLywWqArG2ADUxDq6QprtIzsF82dMF.jpg" alt="Post Image" class="mt-2">';

                            }
                            $image_data = base64_encode($decoded_image_data);
                            ?>
                            <img src="data:image/png;base64,<?php echo $image_data; ?>" alt="Post Image" class="mt-2">
                        <?php endif; ?>
                        <p class="text-sm text-gray-600">Posted at: <?php echo $post['created_at']; ?></p>
                    </li>
                <?php endforeach; ?>

            </ul>
        <?php else : ?>
            <p class="mt-4 text-gray-600">No posts found.</p>
        <?php endif; ?>


    </div>
</body>

</html>