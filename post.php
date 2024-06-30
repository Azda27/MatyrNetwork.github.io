<?php
include 'includes/functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    if (create_post($_SESSION['user_id'], $title, $content)) {
        header("Location: index.php");
    } else {
        echo "Failed to create post!";
    }
}
?>

<?php include 'templates/header.php'; ?>

<h2>Create a new post</h2>
<form method="POST" action="post.php">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <label for="content">Content:</label>
    <textarea id="content" name="content" required></textarea>
    <button type="submit">Create Post</button>
</form>

<?php include 'templates/footer.php'; ?>
    