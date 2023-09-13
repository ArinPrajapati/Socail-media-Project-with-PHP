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

// Modify the SQL query to retrieve all posts
$sql = "SELECT post_id, content, image_base64, created_at, user_id FROM posts ORDER BY created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
$allPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Function to get username by user ID
function getUsernameById($db, $userId)
{
    $sql = "SELECT username FROM users WHERE id = :user_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['username'];
}

?>




?>
<!DOCTYPE html>
<html>

<head>
    <title>MyBook - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex-row flex justify-around m-0">
        <!-- Sidebar -->
        <aside class="bg-blue-600 z-10 text-white h-screen w-[10rem] fixed top-0 left-0 overflow-y-auto block">
            <div class="p-4">
                <h2 class="text-2xl font-semibold mb-4">MyBook</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527748/home-angle.svg" alt="">Home</a></li>
                    <li><a href="dashboard.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527627/box.svg" alt=""> Dashboard</a></li>
                    <li><a href="create_post.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/528831/add-circle.svg" alt=""> Create</a></li>
                    <li><a href="logout.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527784/logout-3.svg " alt=""> Logout</a></li>
                </ul>
            </div>
        </aside>
        <!-- Main Content -->
        <main class="">
            <nav class="bg-blue-600 fixed top-0 z-0 left-0 right-0 p-4">
                <div class="container mx-auto">
                    <h1 class="text-2xl text-right text-white">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                </div>
            </nav>

            <!-- Create Post -->

            <!-- All Posts -->
            <!-- All Posts -->
            <section class="container ml-[1rem] mr-[3rem] mt-[3rem] p-4">
                <form method="POST" class="w-[30rem] bg-white p-10" id="createPostForm" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="post_content" class="block font-medium">Post Content:</label>
                        <textarea name="post_content" rows="4" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="post_image" class="block font-medium">Upload Image (optional):</label>
                        <input type="file" name="post_image" class="w-full">
                    </div>

                    <div class="mb-4">
                        <label for="privacy" class="block font-medium">Privacy:</label>
                        <select name="privacy" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                            <option value="public">Public</option>
                            <option value="friends">Friends Only</option>
                            <option value="private">Private</option>
                        </select>
                    </div>

                    <div>
                        <input type="submit" onclick="submitForm()" value="Create Post" class="bg-blue-500 text-white px-4 py-3 rounded hover:bg-blue-600">

                    </div>
                </form>
                <h3 class="text-xl font-semibold mb-5">All Posts :</h3>

                <?php if (count($allPosts) > 0) : ?>
                    <ul class="my-10 ">
                        <?php foreach ($allPosts as $post) : ?>
                            <?php
                            $username = getUsernameById($db, $post['user_id']);
                            ?>
                            <li class="mb-4 border rounded p-4 bg-white shadow-md w-[30rem]">
                                <a href="post.php?post_id=<?php echo $post['post_id']; ?>
                        <p class=" text-sm text-gray-600">Posted by <?php echo $username; ?> at: <?php echo $post['created_at']; ?></p>
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
                                    <p class="text-base mt-4"><?php echo $post['content']; ?></p>

                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="mt-4 text-gray-600">No posts found.</p>
                <?php endif; ?>
            </section>

        </main>
    </div>
    <script>
        function submitForm() {
            var form = document.getElementById('createPostForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'create_post.php', true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {

                    console.log(xhr.responseText);
                }
            };

            xhr.send(formData);
        }
    </script>

</body>

</html>