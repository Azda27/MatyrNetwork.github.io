<?php
// Koneksi ke database pertama
$conn = new mysqli('localhost', 'root', '', 'forumdb');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Koneksi ke database kedua
$conn2 = new mysqli('localhost', 'root', '', 'mc_authme');
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}
?>
