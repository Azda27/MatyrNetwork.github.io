<?php
include 'includes/functions.php';
session_start();

// Pastikan hanya menerima request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah pengguna sudah login
    if (isset($_SESSION['user_id'])) {
        // Ambil data dari form
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'];

        // Tambahkan komentar ke database
        if (add_comment($post_id, $user_id, $content)) {
            // Redirect kembali ke halaman view_post.php dengan ID postingan
            header("Location: view_post.php?id=" . $post_id);
            exit;
        } else {
            echo "Failed to add comment.";
        }
    } else {
        echo "You need to login to add a comment.";
    }
} else {
    // Jika bukan request POST, redirect ke halaman yang sesuai
    header("Location: index.php");
    exit;
}
?>
s