<?php

ini_set('memory_limit', '256M'); 
ini_set('max_execution_time', 300); 

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
    die("Database connection failed: " . (int)$e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_content = $_POST['post_content'];
    $privacy = $_POST['privacy'];

    // File upload handling (if an image is uploaded)
    $image_base64 = '';
    if (!empty($_FILES['post_image']['name'])) {
        $image_path = $_FILES['post_image']['tmp_name'];
        if (file_exists($image_path)) {
            $image_data = file_get_contents($image_path);
            if ($image_data !== false) {
                $compressed_image_data = base64_encode(gzcompress($image_data, 9)); // 9 is the compression level
            } else {
                echo "Failed to read the uploaded image.";
                // Handle the error as needed.
            }
        } else {
            echo "Failed to process the uploaded image.";
            // You can handle the error as needed.
        }
    }

    // Insert post data into the database
    $sql = "INSERT INTO posts (user_id, content, image_base64, privacy) VALUES (:user_id, :content, :image_base64, :privacy)";
    $stmt = $db->prepare($sql);

    // Replace :user_id with the actual user ID of the logged-in user (you'll need to implement user authentication)
    $user_id = $_SESSION['user_id'];

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':content', $post_content);
    $stmt->bindParam(':image_base64', $compressed_image_data);
    $stmt->bindParam(':privacy', $privacy);

    try {
        $stmt->execute();
        echo "Post created successfully!";
    } catch (PDOException $e) {
        echo "Post creation failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>
    <!-- Add Tailwind CSS link here -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="w-[30rem] mx-auto mt-8 p-4 ">
        <h2 class="text-2xl font-semibold mb-4">Create a New Post</h2>
        <form method="POST" action="create_post.php" enctype="multipart/form-data">
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
                <input type="submit" value="Create Post" class="bg-blue-500 text-white px-4 py-3 rounded hover:bg-blue-600">
                <a href="dashboard.php" class="bg-orange-500 text-white px-4 py-3 rounded hover:bg-orange-600">Dashboard</a>
            </div>
        </form>
    </div>
</body>

</html>