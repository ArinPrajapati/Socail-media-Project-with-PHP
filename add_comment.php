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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $comment_text = $_POST['comment_text'];

    // Insert the comment into the database
    $sql = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':comment_text', $comment_text);

    if ($stmt->execute()) {
        // Comment added successfully, you can redirect back to the post display page or do other actions
        header("Location: post.php?post_id=$post_id");
        exit();
    } else {
        // Handle the error if the comment couldn't be added
        echo "Error adding comment.";
    }
} else {
    // Handle invalid requests
    echo "Invalid request.";
}
?>



