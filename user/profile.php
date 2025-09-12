<?php
require_once '../config/config.php'; // koneksi database
require_once '../includes/header.php'; // header layout
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ambil data akademik
$stmt = $pdo->prepare("SELECT * FROM user_academic WHERE user_id = ?");
$stmt->execute([$user_id]);
$academic = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Profil Saya</h2>
    <div class="card p-3 mb-3">
        <h4>Informasi Akun</h4>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <div class="card p-3 mb-3">
        <h4>Informasi Akademik</h4>
        <?php if ($academic): ?>
            <p><strong>Universitas:</strong> <?= htmlspecialchars($academic['university_name']) ?></p>
            <p><strong>Fakultas:</strong> <?= htmlspecialchars($academic['faculty']) ?></p>
            <p><strong>Program Studi:</strong> <?= htmlspecialchars($academic['study_program']) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($academic['semester']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($academic['status']) ?></p>
        <?php else: ?>
            <p>Belum ada data akademik. <a href="add_academic.php" class="btn btn-sm btn-primary">Tambah Data</a></p>
        <?php endif; ?>
    </div>

    <a href="academic_edit.php" class="btn btn-warning">Edit Akademik</a>
    <a href="../public/logout.php" class="btn btn-danger">Logout</a>
</div>

<?php require_once '../includes/footer.php'; ?>
