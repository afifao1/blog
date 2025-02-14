<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include '../models/db.php';

$user_id = $_SESSION['user']['id'];
$status = $_GET['status'] ?? ''; // Statusni olish

// SQL so‘rovi - agar status bo‘lsa, filtr qo‘shiladi
$sql = "SELECT * FROM posts WHERE user_id = :user_id";
$params = ['user_id' => $user_id];

if ($status) {
    $sql .= " AND status = :status";
    $params['status'] = $status;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Personal Blog</title>
    <link rel="stylesheet" href="../css/my_posts.css">
</head>
<body>
    <h1 class="center">Personal Blog</h1>
    <div class="container">
        <a class="home-btn" href="../index.php">🏠 Home page</a>
        <a class="add_post" href="create.php">Add new post</a>

        <!-- Status bo‘yicha filtr -->
        <form method="get" class="filter-form">
            <select name="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="drafted" <?= $status === 'drafted' ? 'selected' : '' ?>>Drafted</option>
            </select>
        </form>
        
        <?php foreach ($posts as $post): ?>
            <div class="blog">
                <h3>
                    <a class="h3" href="post.php?id=<?= $post['id'] ?>">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h3>
                <span><?= $post['status'] ?></span> <br>
                <span><?= $post['created_at'] ?></span>
                <span><i><?= $post['updated_at'] ?></i></span>
                <p><?= nl2br(htmlspecialchars(substr($post['text'], 0, 100))) ?>...</p>
                <?php if ($_SESSION['user']['id'] == $post['user_id']) { ?>
                    <a class="edit" href="edit.php?id=<?= $post['id'] ?>">edit</a>
                    <form method="post" action="../controllers/post_controller.php">
                       <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                       <button class="delete" type="submit" onclick="return confirm('Do you want to delete?')">Delete</button>
                    </form>
                <?php } ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
