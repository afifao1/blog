<?php
require __DIR__.'/../models/db.php';

$fetchPost = function($id) use ($db) {
    $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) die("Post not found!");
    
    return $post;
};

function createPost($title, $text, $user_id, $status) {
    global $db; // PDO obyektini olish

    $stmt = $db->prepare("INSERT INTO posts (title, text, user_id, status) VALUES (:title, :text, :user_id, :status)");
    $stmt->execute([
        'title' => $title,
        'text' => $text,
        'user_id' => $user_id,
        'status' => $status
    ]);

    header("Location: http://localhost:8000/index.php");
    exit;
};


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    // var_dump($_POST);
    delete($_POST['delete_id']);
}

function delete($id){
    global $db;
    $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header("Location: http://localhost:8000/index.php");

    exit;

}


function edit($title, $text, $id, $status){
    global $db;

    $stmt = $db->prepare("UPDATE posts SET title = :title, text = :text, status = :status WHERE id = :id");
    $stmt->execute(['title' => $title, 'text' => $text, 'id' => $id,'status'=> $status]);
   
    header("Location: http://localhost:8000/index.php");
    exit();
}


function loginUser($email, $password) {
    global $db;

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: http://localhost:8000/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Incorrect email or password!";
    }
}


function registerUser($name, $email, $password) {
    global $db;

    // Email mavjudligini tekshiramiz
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $_SESSION['error'] = "This email is already registered.!";
    } else {
        // Parolni hash qilish
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Ma'lumotlar bazasiga qo‘shish
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        $_SESSION['success'] = "You have successfully registered.!";
        header("Location: login.php");
        exit;
    }
}

$searchPosts = function($searchPhrase) use ($db){
  $query = "SELECT * FROM posts WHERE title LIKE :searchPhrase";
  $stmt = $db->prepare($query);
  $stmt->execute([':searchPhrase'=>"%$searchPhrase%"]);
  return $stmt->fetchAll();
};

$fetchPosts = function() use ($db) {
  $stmt = $db->query("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC");
  return $stmt->fetchAll();
};
