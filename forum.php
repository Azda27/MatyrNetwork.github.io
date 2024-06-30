<?php
include 'includes/functions.php';
session_start();
$posts = get_posts();
?>

<?php include 'templates/header.php'; ?>

<div class="main">
    <h2>Posts</h2>
    <div class="main">
        <a class="btn btn-warning" href="post.php">post</a> <br><br>
        <?php while ($post = $posts->fetch_assoc()) : ?>
            <div style="cursor: pointer;" class="post" onclick="window.location.href='view_post.php?id=<?php echo $post['id']; ?>'">
                <h3><a href="view_post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h3>
                <p><?php echo substr($post['content'], 0, 200); ?>...</p>
                <p>Posted by <?php echo $post['username']; ?> on <?php echo $post['created_at']; ?></p>

                <?php if (isset($_SESSION['user_id'])) : ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>