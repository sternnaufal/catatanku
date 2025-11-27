<?php
require_once '../includes/auth_admin.php';
require_once '../config/config.php';
require_once '../includes/header.php'; // Tambahkan header

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_matkul = trim($_POST['nama_matkul']);

    if (!empty($nama_matkul)) {
        $stmt = $pdo->prepare("INSERT INTO matkul (nama) VALUES (:nama)");
        $stmt->execute(['nama' => $nama_matkul]);
        $pesan = "Matkul berhasil ditambahkan!";
    } else {
        $pesan = "Nama matkul tidak boleh kosong.";
    }
}
?>

<div class="container mt-4">
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="add_matkul.php">Tambah Mata Kuliah</a></li>
        <li class="nav-item"><a class="nav-link" href="tabel_user.php">Daftar Pengguna</a></li>
        <li class="nav-item"><a class="nav-link" href="tabel_matkul.php">Daftar Mata Kuliah</a></li>
        <li class="nav-item"><a class="nav-link" href="tabel_catatan.php">Daftar Catatan</a></li>
      </ul>
    </div>
  </div>
</nav>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Mata Kuliah</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($pesan)): ?>
                <div class="alert alert-info"><?= htmlspecialchars($pesan) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="nama_matkul" class="form-label">Nama Matkul</label>
                    <input type="text" class="form-control" id="nama_matkul" name="nama_matkul" required>
                </div>
                <button type="submit" class="btn btn-success">Tambah</button>
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
