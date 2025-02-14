<?php

require __DIR__ . '/../models/db.php';

try {
    // Users jadvalini yaratish
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(64) NOT NULL,
        email VARCHAR(128) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");

    // Posts jadvalini yaratish
    $db->exec("CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(64) DEFAULT NULL,
        text TEXT DEFAULT NULL,
        user_id INT NOT NULL,
        status ENUM('published', 'drafted') NOT NULL DEFAULT 'drafted',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "The Users and Posts tables have been successfully created.!";
} catch (PDOException $e) {
    die("Xatolik: " . $e->getMessage());
}

// category ustuni mavjud emasligini tekshirib, qo'shish
$checkColumn = $db->query("SHOW COLUMNS FROM posts LIKE 'category'");
if (!$checkColumn->fetch()) {
    $db->exec("ALTER TABLE posts ADD COLUMN category ENUM('IT', 'Shaxsiy', 'Sport') NOT NULL DEFAULT 'Shaxsiy'");
    echo "Category column successfully added!";
} else {
    echo "Category column already exists!";
}

