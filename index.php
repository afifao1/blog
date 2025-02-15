<?php
require './controllers/post_controller.php';

if (!isset($_SESSION['user'])) {
    header("Location: views/login.php");
    exit;
}

$searchPhrase = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$totalPosts = countPosts($category);
$totalPages = ceil($totalPosts / $limit);

$posts = $searchPhrase ? searchPosts($searchPhrase) : fetchPosts($category);

// Paginatsiya uchun postlarni filtrlash
$posts = array_slice($posts, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Blog</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/pagination.css">
</head>
<body>
    <h1 class="center">Personal Blog</h1>
    <div class="container">
        <div class="links">
            <a class="add_post" href="views/create.php">Add new post</a>
            <form action="" method="get">
                <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars($searchPhrase) ?>">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="IT" <?= $category === 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="Shaxsiy" <?= $category === 'Shaxsiy' ? 'selected' : '' ?>>Shaxsiy</option>
                    <option value="Sport" <?= $category === 'Sport' ? 'selected' : '' ?>>Sport</option>
                </select>
                <button type="submit">Filter</button>
            </form>
            <a class="add_post" href="views/my_posts.php">My posts</a>
            <a class="add_post" href="views/logout.php">Exit</a>
        </div>

        <?php if (!$posts): ?>
            <p>Posts not found</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="blog">
                    <h3>
                        <a class="h3" href="views/post.php?id=<?= $post['id'] ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>
                    <span><?= $post['created_at'] ?></span>
                    <p><?= nl2br(htmlspecialchars(substr($post['text'], 0, 100))) ?>...</p>
                    <b>Category: <?= htmlspecialchars($post['category']) ?></b>
                        <?php if ($_SESSION['user']['id'] == $post['user_id']): ?>
                            <div class="actions">
                                <a class="edit" href="views/edit.php?id=<?= $post['id'] ?>">edit</a>
                                <form method="post" action="controllers/post_controller.php">
                                    <input type="hidden" name="delete_id" value="<?= $post['id'] ?>">
                                    <button class="delete" type="submit" onclick="return confirm('Do you want to delete?')">Delete</button>
                                </form>
                            </div>
                        <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Paginatsiya -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&category=<?= urlencode($category) ?>" class="prev">← Previous</a>
                <?php endif; ?>

                <span>Page <?= $page ?> of <?= $totalPages ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&category=<?= urlencode($category) ?>" class="next">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
