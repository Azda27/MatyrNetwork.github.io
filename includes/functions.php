<?php
require 'config.php';

class Sha256 {
    /** @var string[] range of characters for salt generation */
    private $CHARS;

    const SALT_LENGTH = 16;

    public function __construct() {
        $this->CHARS = self::initCharRange();
    }

    public function hash($password) {
        $salt = $this->generateSalt();
        return '$SHA$' . $salt . '$' . hash('sha256', hash('sha256', $password) . $salt);
    }

    /**
     * @return string randomly generated salt
     */
    private function generateSalt() {
        $maxCharIndex = count($this->CHARS) - 1;
        $salt = '';
        for ($i = 0; $i < self::SALT_LENGTH; ++$i) {
            $salt .= $this->CHARS[mt_rand(0, $maxCharIndex)];
        }
        return $salt;
    }

    private static function initCharRange() {
        return array_merge(range('0', '9'), range('a', 'f'));
    }
}

function userExists($conn, $username, $email) {
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed for userExists: " . $conn->error);
    }
    $stmt->bind_param('ss', $username, $email); // Menggunakan tanda kutip untuk string
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

function register($username, $email, $password) {
    global $conn, $conn2;

    $lowercase_username = strtolower($username);

    // Cek apakah username atau email sudah ada di database pertama
    if (userExists($conn, $lowercase_username, $email)) {
        return false; // User sudah ada
    }
    
    // Instance Sha256 class for hashing
    $sha256 = new Sha256();
    $hashed_password = hash('sha256', $password); // Hash password for the first database
    $authme_password = $sha256->hash($password); // Hash password with salt for the second database

    // Query untuk database pertama
    $sql1 = "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())";
    $stmt1 = $conn->prepare($sql1);
    if (!$stmt1) {
        die("Prepare failed for SQL1: " . $conn->error);
    }
    $stmt1->bind_param('sss', $lowercase_username, $email, $hashed_password); // Menggunakan tanda kutip untuk string
    $result1 = $stmt1->execute();
    
    // Query untuk database kedua
    $ip = $_SERVER['REMOTE_ADDR']; // Mendapatkan IP pengguna
    $regdate = time(); // Mendapatkan timestamp saat ini
    $sql2 = "INSERT INTO authme (username, realname, password, ip, regdate, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn2->prepare($sql2);
    if (!$stmt2) {
        die("Prepare failed for SQL2: " . $conn2->error);
    }
    $stmt2->bind_param('ssssss', $lowercase_username, $username, $authme_password, $ip, $regdate, $email); // Menggunakan tanda kutip untuk string
    $result2 = $stmt2->execute();

    // Menutup statement
    $stmt1->close();
    $stmt2->close();
    
    // Mengembalikan true jika kedua operasi berhasil
    return $result1 && $result2;
}

function login($username, $password)
{
    global $conn;
    $hashed_password = hash('sha256', $password);
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $hashed_password === $user['password']) {
        return $user;
    }
    return false;
}


function create_post($user_id, $title, $content)
{
    global $conn;
    $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);
    return $stmt->execute();
}

function get_posts()
{
    global $conn;
    $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC";
    return $conn->query($sql);
}

function add_comment($post_id, $user_id, $content)
{
    global $conn;
    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $content);
    return $stmt->execute();
}

function get_comments($post_id)
{
    global $conn;
    $sql = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE post_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    return $stmt->get_result();
}
