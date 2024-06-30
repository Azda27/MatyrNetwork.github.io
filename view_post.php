<?php
include 'includes/functions.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$post_id = $_GET['id'];
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$comments_per_page = 6;
$offset = ($page - 1) * $comments_per_page;

// Ambil detail postingan
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Post not found!";
    exit;
}

// Ambil komentar-komentar dengan pagination
$sql_comments = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY comments.created_at DESC LIMIT ? OFFSET ?";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("iii", $post_id, $comments_per_page, $offset);
$stmt_comments->execute();
$comments = $stmt_comments->get_result();

// Hitung total komentar untuk pagination
$sql_count = "SELECT COUNT(*) AS total_comments FROM comments WHERE post_id = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $post_id);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_comments = $count_result->fetch_assoc()['total_comments'];
$total_pages = ceil($total_comments / $comments_per_page);

?>

<?php include 'templates/header.php'; ?>

<div class="back"><a href="index.php">home</a> > <?php echo $post['title']; ?></div><br>

<!-- Pagination links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $page - 1; ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a class="btn btn-light" href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $i; ?>"<?php if ($i == $page) echo ' class="active"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<h2><?php echo $post['title']; ?></h2>
<p><?php echo $post['content']; ?></p>
<p>Posted by <?php echo $post['username']; ?> on <?php echo $post['created_at']; ?></p>

<h3>Comments</h3>
<?php if ($comments->num_rows > 0) : ?>
    <?php while ($comment = $comments->fetch_assoc()) : ?>
        <div class="comments">
            <b><?php echo $comment['username']; ?></b>
            <p><?php echo $comment['created_at']; ?></p>
            <div class="comment">
                <p><?php echo $comment['content']; ?></p>
            </div>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <p>No comments yet.</p>
<?php endif; ?>

<!-- Pagination links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $page - 1; ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $i; ?>"<?php if ($i == $page) echo ' class="active"'; ?>><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="view_post.php?id=<?php echo $post_id; ?>&page=<?php echo $page + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <h3>Add a Comment</h3>
    <form method="POST" action="add_comment.php">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <label for="content">Comment:</label>
        <textarea id="content" name="content" required></textarea>
        <button type="submit">Add Comment</button>
    </form>
<?php else: ?>
    <p>You need to <a  class="btn btn-primary" href="login.php">login</a> to add a comment.</p>
<?php endif; ?>

<?php include 'templates/footer.php'; ?>
