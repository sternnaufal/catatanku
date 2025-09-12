<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/includes/header.php';

$matkuls = $pdo->query("SELECT * FROM matkul")->fetchAll();
?>

<div class="container py-4">
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

  <h2>Daftar Mata Kuliah</h2>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Mata Kuliah</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($matkuls as $matkul): ?>
        <tr>
          <td><?= $matkul['id'] ?></td>
          <td><?= htmlspecialchars($matkul['nama']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
