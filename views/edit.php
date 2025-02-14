<?php
session_start();

// include  '../models/db.php';

require_once '../controllers/post_controller.php';
require_once '../models/db.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Post not found!");

$stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump('tugadi');

    if (isset($_POST['title'], $_POST['text'], $_POST['status']) && isset($_SESSION['user']['id'])) {
        // var_dump($id);
        edit($_POST['title'], $_POST['text'],  $id , $_POST['status']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit post</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
    <h1>Edit post</h1>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br>
        <textarea name="text" required><?= htmlspecialchars($post['text']) ?></textarea><br>
        <select name="status">
            <option value="drafted">Drafted</option>
            <option value="published">Published</option>
        </select><br><br>
        <button type="submit">submit</button>
    </form>
</body>
</html>
