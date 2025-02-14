<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "credentials.php";

try {
    $db = new PDO("mysql:host=localhost;dbname=$database", $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Unable to connect to database: " . $e->getMessage());
}
?>
