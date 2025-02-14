<?php
session_start();
// include '../controllers/post_controller.php';

require_once '../controllers/post_controller.php';
// require_once '../models/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    registerUser($name, $email, $password);
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>

    <div class="container">
        <h2>Sign up</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="message success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Ismingiz" required> <br>
            <input type="email" name="email" placeholder="Email" required> <br>
            <input type="password" name="password" placeholder="Parol" required> <br>
            <button type="submit">Sign up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
