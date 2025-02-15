<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../models/db.php';
// session_start();

// Post yaratish
function createPost($title, $text, $user_id, $status, $category) {
    global $db;
    $stmt = $db->prepare("INSERT INTO posts (title, text, user_id, status, category) VALUES (:title, :text, :user_id, :status, :category)");
    return $stmt->execute([
        ':title' => $title,
        ':text' => $text,
        ':user_id' => $user_id,
        ':status' => $status,
        ':category' => $category
    ]);
}

// Postlarni olish
function fetchPosts($category = '') {
    global $db;
    $sql = "SELECT * FROM posts WHERE status = 'published'";
    
    if (!empty($category)) {
        $sql .= " AND category = :category";
    }
    
    $stmt = $db->prepare($sql);
    
    if (!empty($category)) {
        $stmt->bindParam(':category', $category);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Postni qidirish
function searchPosts($searchPhrase) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM posts WHERE title LIKE :search AND status = 'published'");
    $stmt->bindValue(':search', "%$searchPhrase%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Postni o‘chirish
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_id'])) {
    if (!isset($_SESSION['user'])) {
        header("Location: ../views/login.php");
        exit;
    }
    
    $delete_id = $_POST['delete_id'];
    $user_id = $_SESSION['user']['id'];

    $stmt = $db->prepare("DELETE FROM posts WHERE id = :id AND user_id = :user_id");
    if ($stmt->execute(['id' => $delete_id, 'user_id' => $user_id])) {
        header("Location: ../index.php");
    } else {
        echo "Error: Postni o‘chirib bo‘lmadi.";
    }
    exit;
}


function edit($title, $text, $id, $status) {
    global $db;
    $stmt = $db->prepare("UPDATE posts SET title = ?, text = ?, status = ? WHERE id = ?");
    $stmt->execute([$title, $text, $status, $id]);
    header("Location: ../index.php"); // Tahrirlangandan keyin bosh sahifaga qaytarish
    exit;
}


function registerUser($name, $email, $password) {
    require_once '../models/db.php'; // Baza bilan bog‘lanish
    global $db; // GLOBAL qilish kerak

    // Email allaqachon mavjudligini tekshirish
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);

    if ($checkStmt->fetch()) {
        $_SESSION['error'] = "Bu email allaqachon ro‘yxatdan o‘tgan!";
        return;
    }

    // Parolni himoyalash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Foydalanuvchini qo‘shish
    $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$name, $email, $hashedPassword])) {
        $_SESSION['success'] = "Ro‘yxatdan o‘tish muvaffaqiyatli!";
        header("Location: login.php"); // Login sahifasiga yo‘naltirish
        exit;
    } else {
        $_SESSION['error'] = "Xatolik yuz berdi. Qayta urinib ko‘ring!";
    }
}


function loginUser($email, $password) {
    require_once '../models/db.php';
    global $db;

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user; // To‘liq foydalanuvchi ma’lumotlari saqlanadi
        $_SESSION['success'] = "Tizimga muvaffaqiyatli kirdingiz!";
        header("Location: ../index.php");
        exit;
    } else {
        $_SESSION['error'] = "Email yoki parol noto‘g‘ri!";
    }
}



// POST so‘rov va delete_id mavjudligini tekshirish
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $post_id = $_POST['delete_id'];

    // Post foydalanuvchiga tegishli ekanligini tekshirish
    $stmt = $db->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION['user']['id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        $_SESSION['error'] = "Postni o‘chirishga ruxsatingiz yo‘q!";
        header("Location: ../index.php");
        exit;
    }

    // Agar post mavjud bo‘lsa, uni o‘chirish
    $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
    if ($stmt->execute([$post_id])) {
        $_SESSION['success'] = "Post o‘chirildi!";
    } else {
        $_SESSION['error'] = "Xatolik yuz berdi!";
    }

    // Qayta yo‘naltirish
    header("Location: ../views/my_posts.php");
    exit;
}

function fetchPost($db, $post_id) { // $pdo o‘rniga $db ishlatyapmiz
    $stmt = $db->prepare("SELECT * FROM posts WHERE id = :post_id LIMIT 1");
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function countPosts($category = '') {
    // require_once __DIR__ . '/../models/db.php'; // Bazani ulash

    global $db; 

    $query = "SELECT COUNT(*) FROM posts WHERE status = 'published'";

    if (!empty($category)) {
        $query .= " AND category = :category";
    }

    $stmt = $db->prepare($query);

    if (!empty($category)) {
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchColumn();
}


?>


