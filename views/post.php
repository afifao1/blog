<?php
require "../models/db.php"; // PDO ulanishi
require "../controllers/post_controller.php"; // Postni olish uchun funksiya

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $post = fetchPost($db, $post_id); // $pdo o‘rniga $db

    if (!$post) {
        die("Post topilmadi!");
    }
} else {
    die("ID berilmagan!");
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> - Blog Post</title>
    <link rel="stylesheet" href="../css/post.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta">Yozilgan sana: <?= htmlspecialchars($post['created_at']) ?></div>
        <p><?= nl2br(htmlspecialchars($post['text'])) ?></p>
        <a href="../index.php" class="back-btn">Asosiy sahifaga qaytish</a>
    </div>
</body>
</html>
