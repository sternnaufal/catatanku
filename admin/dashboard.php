<?php
require_once dirname(__DIR__) . '/config/config.php';

// Jumlah matkul
$matkulCount = $pdo->query("SELECT COUNT(*) as total FROM matkul")->fetch()['total'];

// Jumlah user
$userCount = $pdo->query("SELECT COUNT(*) as total FROM users")->fetch()['total'];

// Jumlah catatan per matkul
$notesByMatkul = $pdo->query("
    SELECT m.nama AS nama_matkul, COUNT(n.id) AS jumlah_catatan
    FROM matkul m
    LEFT JOIN pertemuan p ON m.id = p.matkul_id
    LEFT JOIN notes n ON p.id = n.pertemuan_id
    GROUP BY m.id, m.nama
");

require_once dirname(__DIR__) . '/includes/header.php';

// Catatan paling populer (top 5 berdasarkan view)
$top_notes = $pdo->query("
    SELECT n.id, n.title, COUNT(nv.id) AS view_count
    FROM notes n
    LEFT JOIN note_views nv ON n.id = nv.note_id
    GROUP BY n.id
    ORDER BY view_count DESC
    LIMIT 5
")->fetchAll();

// Catatan publik vs privat
$note_status = $pdo->query("
    SELECT SUM(is_public=1) AS publik, SUM(is_public=0) AS privat
    FROM notes
")->fetch();

// Top user menulis catatan publik (top 5)
$top_users = $pdo->query("
    SELECT u.username, COUNT(n.id) AS jumlah_catatan
    FROM users u
    LEFT JOIN notes n ON u.id = n.user_id AND n.is_public=1
    GROUP BY u.id
    ORDER BY jumlah_catatan DESC
    LIMIT 5
")->fetchAll();
?>

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


<div class="container py-4">
    <h1 class="mb-4">Dashboard Admin</h1>

    <div class="row mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-primary shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Mata Kuliah</h5>
                    <p class="card-text fs-4"><?= $matkulCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-success shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Pengguna</h5>
                    <p class="card-text fs-4"><?= $userCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Jumlah Catatan per Mata Kuliah</h3>
    <div class="row">
        <?php while ($row = $notesByMatkul->fetch()): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['nama_matkul']) ?></h5>
                        <p class="card-text">Catatan: <strong><?= $row['jumlah_catatan'] ?></strong></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <div class="row mb-4">
    <!-- Catatan Paling Populer -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark fw-bold">🔥 Catatan Paling Populer</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($top_notes as $note): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($note['title']) ?>
                            <span class="badge bg-primary rounded-pill"><?= $note['view_count'] ?>x</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Catatan Publik vs Privat -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white fw-bold">🌍 Publik vs 🔒 Privat</div>
            <div class="card-body text-center">
                <p class="mb-2">Publik: <strong><?= $note_status['publik'] ?></strong></p>
                <p>Privat: <strong><?= $note_status['privat'] ?></strong></p>
                <div class="progress" style="height:20px;">
                    <?php
                        $total_notes = $note_status['publik'] + $note_status['privat'];
                        $pub_percent = $total_notes ? ($note_status['publik']/$total_notes*100) : 0;
                        $priv_percent = $total_notes ? ($note_status['privat']/$total_notes*100) : 0;
                    ?>
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $pub_percent ?>%" aria-valuenow="<?= $pub_percent ?>" aria-valuemin="0" aria-valuemax="100"><?= round($pub_percent) ?>%</div>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: <?= $priv_percent ?>%" aria-valuenow="<?= $priv_percent ?>" aria-valuemin="0" aria-valuemax="100"><?= round($priv_percent) ?>%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top User Menulis Catatan Publik -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-success text-white fw-bold">🏆 Top User Catatan Publik</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($top_users as $user): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($user['username']) ?>
                            <span class="badge bg-warning rounded-pill"><?= $user['jumlah_catatan'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
