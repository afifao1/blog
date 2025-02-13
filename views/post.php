<?php
require "../controllers/post_controller.php";
$id = $_GET['id'] ?? '';
$post = $fetchPost($id);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <link rel="stylesheet" href="../css/post.css">
</head>
<body>
    <div class="container">
            <h1><?= htmlspecialchars($post['title']) ?></h1>
            <div class="post-meta">Date written: <?= htmlspecialchars($post['created_at']) ?></div>
            <p><?= nl2br(htmlspecialchars($post['text'])) ?></p>
        <a href="/" class="back-btn">Return to homepage</a>
    </div>
</body>
</html>
