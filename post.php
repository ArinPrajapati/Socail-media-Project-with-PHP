<?php
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

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch the post
    $sql = "SELECT post_id, content, image_base64, created_at FROM posts WHERE post_id = :post_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch comments for the post
    $sql = "SELECT comment_id, user_id, comment_text, created_at FROM comments WHERE post_id = :post_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the post and comments in HTML
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header and navigation -->
    <aside class="bg-blue-600 z-10 text-white h-screen w-[10rem] fixed top-0 left-0 overflow-y-auto block">
        <div class="p-4">
            <h2 class="text-2xl font-semibold mb-4">MyBook</h2>
            <ul class="space-y-2">
                <li><a href="homepage.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527748/home-angle.svg" alt="">Home</a></li>
                <li><a href="dashboard.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527627/box.svg" alt=""> Dashboard</a></li>
                <li><a href="create_post.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/528831/add-circle.svg" alt="">  Create</a></li>
                <li><a href="logout.php" class="flex items-center"><img class="w-[3rem]" src="https://www.svgrepo.com/show/527784/logout-3.svg " alt=""> Logout</a></li>
            </ul>
        </div>
    </aside>
    <main class="container w-[80%] ml-48 overscroll-x-contain p-4">
        <!-- Display the post -->
        <div class="mb-4 border rounded p-4 bg-white shadow-md">
            <p class="text-base"><?php echo $post['content']; ?></p>
            <?php if (!empty($post['image_base64'])) : ?>
                <?php
                $decoded_image_data = gzuncompress(base64_decode($post['image_base64']));
                if ($decoded_image_data === false) {
                    echo ' <img class="img" src="https://t3.ftcdn.net/jpg/02/48/42/64/360_F_248426448_NVKLywWqArG2ADUxDq6QprtIzsF82dMF.jpg" alt="Post Image" class="mt-2">';
                }
                $image_data = base64_encode($decoded_image_data);
                ?>
                <img src="data:image/png;base64,<?php echo $image_data; ?>" alt="Post Image" class="mt-2">
            <?php endif; ?>
            <p class="text-sm text-gray-600">Posted at: <?php echo $post['created_at']; ?></p>
        </div>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Add this HTML form inside your post display page -->
<form action="add_comment.php" method="POST">
    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
    <textarea name="comment_text" rows="4" cols="50" placeholder="Add your comment"></textarea>
    <br>
    <input type="submit" value="Submit Comment">
</form>

</body>
</html>
        <!-- Display comments -->
        <h3 class="text-xl font-semibold mb-4">Comments :</h3>
        <?php if (count($comments) > 0) : ?>
            <ul>
                <?php foreach ($comments as $comment) : ?>
                    <li class="mb-2 border rounded p-2 bg-white shadow-md">
                        <p class="text-base"><?php echo $comment['comment_text']; ?></p>
                        <p class="text-sm text-gray-600">Posted by User ID: <?php echo $comment['user_id']; ?> at: <?php echo $comment['created_at']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="mt-4 text-gray-600">No comments found.</p>
        <?php endif; ?>
    </main>
</body>
</html>
