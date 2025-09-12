<?php
session_start();
require_once '../config/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id         = $_SESSION['user_id'];
    $university_name = trim($_POST['university_name']);
    $faculty         = trim($_POST['faculty']);
    $study_program   = trim($_POST['study_program']);
    $semester        = (int) $_POST['semester'];
    $status          = $_POST['status'] ?? 'aktif';

    if ($university_name && $faculty && $study_program && $semester) {
        $stmt = $pdo->prepare("INSERT INTO user_academic 
            (user_id, university_name, faculty, study_program, semester, status) 
            VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $university_name, $faculty, $study_program, $semester, $status])) {
            $success = "Data akademik berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan data akademik.";
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<div class="container mt-5">
    <h2>Tambah Data Akademik</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Universitas</label>
            <input type="text" name="university_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fakultas</label>
            <input type="text" name="faculty" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Program Studi</label>
            <input type="text" name="study_program" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Semester</label>
            <input type="number" name="semester" min="1" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="aktif">Aktif</option>
                <option value="cuti">Cuti</option>
                <option value="lulus">Lulus</option>
                <option value="dropout">Dropout</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
