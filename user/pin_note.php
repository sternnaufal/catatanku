<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: dashboard.php");
    exit;
}

$note_id = (int) $_GET['id'];
$action = $_GET['action'];

// pastikan note milik user login
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $_SESSION['user_id']]);
$note = $stmt->fetch();

if (!$note) {
    header("Location: dashboard.php");
    exit;
}

if ($action === "pin") {
    $pdo->prepare("UPDATE notes SET is_pinned = 1 WHERE id = ?")->execute([$note_id]);
} elseif ($action === "unpin") {
    $pdo->prepare("UPDATE notes SET is_pinned = 0 WHERE id = ?")->execute([$note_id]);
}

header("Location: dashboard.php");
exit;
