<?php
require_once '../config/config.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: ../public/login.php");
    exit;
}

$note_id = $_GET['id'] ?? null;
if (!$note_id || !is_numeric($note_id)) {
    echo "<p>Catatan tidak ditemukan.</p>";
    exit;
}

$note_id = (int)$note_id;
$user_id = $_SESSION['user_id'];
$ip = $_SERVER['REMOTE_ADDR'];

// 🔹 Hitung view unik berdasarkan IP
$stmt = $pdo->prepare("SELECT id FROM note_views WHERE note_id = ? AND ip_address = ?");
$stmt->execute([$note_id, $ip]);
if ($stmt->rowCount() === 0) {
    $insert = $pdo->prepare("INSERT INTO note_views (note_id, ip_address) VALUES (?, ?)");
    $insert->execute([$note_id, $ip]);
}

// 🔹 Hitung total view
$view_stmt = $pdo->prepare("SELECT COUNT(*) FROM note_views WHERE note_id = ?");
$view_stmt->execute([$note_id]);
$view_count = $view_stmt->fetchColumn();

// 🔹 Ambil catatan
$stmt = $pdo->prepare("SELECT notes.*, users.username FROM notes 
                       JOIN users ON notes.user_id = users.id 
                       WHERE notes.id = ?");
$stmt->execute([$note_id]);
$note = $stmt->fetch();

if (!$note) {
    echo "<p>Catatan tidak ditemukan.</p>";
    exit;
}

// 🔹 Cek hak akses
$is_owner = $user_id == $note['user_id'];
$can_view = $note['is_public'] || $is_owner;

if (!$can_view) {
    header("HTTP/1.0 404 Not Found");
    echo "<p>Catatan tidak ditemukan.</p>";
    exit;
}

// 🔹 Proses tombol Like
$like_check_stmt = $pdo->prepare("SELECT id FROM note_likes WHERE note_id = ? AND user_id = ?");
$like_check_stmt->execute([$note_id, $user_id]);
$has_liked = $like_check_stmt->rowCount() > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like']) && !$has_liked) {
    $like_insert = $pdo->prepare("INSERT INTO note_likes (note_id, user_id) VALUES (?, ?)");
    $like_insert->execute([$note_id, $user_id]);
    header("Location: view_note.php?id=$note_id");
    exit;
}

// 🔹 Hitung total like
$like_stmt = $pdo->prepare("SELECT COUNT(*) FROM note_likes WHERE note_id = ?");
$like_stmt->execute([$note_id]);
$like_count = $like_stmt->fetchColumn();
?>

<div class="container my-5">
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <h2 class="card-title fw-bold"><?= htmlspecialchars($note['title']) ?></h2>
            <p class="text-muted mb-2">
                oleh <strong><?= htmlspecialchars($note['username']) ?></strong> pada <?= $note['created_at'] ?>
            </p>

            <hr>

            <div class="note-content mb-4">
                <?= $note['content'] ?>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <span class="badge bg-secondary">👁️ <?= $view_count ?> views</span>
                <span class="badge bg-danger">❤️ <?= $like_count ?> likes</span>

                <form method="post" class="d-inline">
                    <?php if (!$has_liked): ?>
                        <button type="submit" name="like" class="btn btn-sm btn-outline-danger">❤️ Like</button>
                    <?php else: ?>
                        <span class="text-success small">Kamu menyukai catatan ini</span>
                    <?php endif; ?>
                </form>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="<?= BASE_URL ?>public/index.php" class="btn btn-outline-secondary btn-sm">← Kembali</a>
                <a href="export_note.php?id=<?= $note['id'] ?>&type=pdf" class="btn btn-success btn-sm">⬇ PDF</a>
                <a href="export_note.php?id=<?= $note['id'] ?>&type=txt" class="btn btn-info btn-sm text-white">⬇ TXT</a>
            </div>
        </div>
    </div>
</div>

<style>
.note-content {
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 8px;
    line-height: 1.6;
}
</style>

<?php require_once '../includes/footer.php'; ?>
