<?php
require_once '../config/config.php';
require_once '../includes/auth.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID tidak valid.";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: dashboard.php");
exit;
