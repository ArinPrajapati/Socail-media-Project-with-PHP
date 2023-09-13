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

if (isset($_GET['delete_post_id'])) {
    // Handle post deletion
    $delete_post_id = $_GET['delete_post_id'];

    // You should perform validation and security checks here before deleting the post.
    // For simplicity, I'm assuming the user can only delete their own posts.
    $delete_sql = "DELETE FROM posts WHERE post_id = :delete_post_id AND user_id = :user_id";
    $delete_stmt = $db->prepare($delete_sql);
    $delete_stmt->bindParam(':delete_post_id', $delete_post_id);
    $delete_stmt->bindParam(':user_id', $user_id);
    $delete_stmt->execute();

    // Redirect back to the dashboard page after deletion
    header('Location: dashboard.php');
    exit();
}

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

<body class="bg-gray-100 ">
    <div class="h-[10rem] flex items-center  flex-row p-10 bg-gradient-to-r from-blue-600 to-fuchsia-500 fixed top-0 right-0 left-0">

        <img class="rounded-full w-[6rem] h-[6rem]" src="https://picsum.photos/200" alt="">
        <h2 class=" bg-gradient-to-r from-slate-200 via-red-600 to-pink-500 text-transparent    bg-clip-text text-[3rem] ml-4 h-[5rem] font-semibold mb-4">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    </div>
    <aside class="bg-blue-600 z-10 text-white h-screen w-[10rem] fixed top-[10rem] left-0 overflow-y-auto block">
        <div class="p-4">

            <ul class="space-y-2">
                <li><a href="homepage.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527748/home-angle.svg" alt="">Home</a></li>
                <li><a href="dashboard.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527627/box.svg" alt=""> Dashboard</a></li>
                <li><a href="create_post.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/528831/add-circle.svg" alt=""> Create</a></li>
                <li><a href="logout.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527784/logout-3.svg " alt=""> Logout</a></li>
            </ul>
        </div>
    </aside>
    <div class=" w-[40rem] border p-4 ml-52 block mt-[12rem]">


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
                        <!-- Add a Delete button with a confirmation prompt -->
                        <form action="dashboard.php" method="GET" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="delete_post_id" value="<?php echo $post['post_id']; ?>">
                            <button type="submit" class="text-red-500">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>

            </ul>
        <?php else : ?>
            <p class="mt-4 text-gray-600">No posts found.</p>
        <?php endif; ?>


    </div>
</body>

</html>