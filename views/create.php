<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../controllers/post_controller.php';

// Session faqat boshlanmagan bo‘lsa boshlanadi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['title']) && !empty($_POST['text']) && !empty($_POST['status']) && !empty($_POST['category'])) {
        $title = trim($_POST['title']);
        $text = trim($_POST['text']);
        $status = $_POST['status'];
        $category = $_POST['category'];
        $user_id = $_SESSION['user']['id'];

        if (createPost($title, $text, $user_id, $status, $category)) {
            header("Location: ../index.php");
            exit;
        } else {
            $error = "An error occurred, please try again.";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <link rel="stylesheet" href="../css/create.css">
</head>
<body>
    <div class="container">
        <h1>Add New Post</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="text" placeholder="Text" required></textarea>

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="drafted">Drafted</option>
                <option value="published">Published</option>
            </select>

            <label for="category">Category:</label>
            <select name="category" required>
                <option value="IT">IT</option>
                <option value="Shaxsiy">Shaxsiy</option>
                <option value="Sport">Sport</option>
            </select>

            <button type="submit">Submit</button>
        </form>
        <a href="../index.php" class="back-btn">Return to homepage</a>
    </div>
</body>
</html>
