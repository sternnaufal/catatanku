<?php
require_once '../config/config.php';
require_once '../includes/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit;
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Cek apakah user adalah admin
if (!$user || $user['username'] !== 'admin') {
    header('Location: ' . BASE_URL . 'public/index.php');
    exit;
}
?>