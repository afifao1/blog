<?php
require "../controllers/post_controller.php";

$id = $_GET['id'] ?? '';

if (!$id) {
    die("Xatolik: Post ID topilmadi.");
}

$post = fetchPost($id);

if (!$post) {
    die("Xatolik: Post topilmadi.");
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
