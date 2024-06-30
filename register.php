<?php
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (register($username, $email, $password)) {
        echo "Registration successful!";
    } else {
        echo "Registration failed!";
    }
}
?>

<?php include 'templates/header.php'; ?>

<h2>Register</h2>
<form method="POST" action="register.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Register</button>
</form>

<?php include 'templates/footer.php'; ?>
