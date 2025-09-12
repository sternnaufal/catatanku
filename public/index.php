<?php
require_once '../config/config.php';
require_once '../includes/header.php';

$selected_matkul = isset($_GET['matkul_id']) ? (int) $_GET['matkul_id'] : null;
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Ambil semua matkul untuk dropdown
$matkul_list = $pdo->query("SELECT id, nama FROM matkul")->fetchAll();

// Fungsi untuk menghasilkan warna HEX dari nama matkul
function getColorFromMatkul($namaMatkul) {
    $hash = md5($namaMatkul);
    return '#' . substr($hash, 0, 6);
}

// Siapkan SQL dan parameter
$sql = "
    SELECT 
        notes.id, notes.title, notes.content, notes.created_at,
        users.username,
        pertemuan.nomor_pertemuan,
        matkul.id AS matkul_id,
        matkul.nama AS nama_matkul
    FROM notes
    JOIN users ON notes.user_id = users.id
    JOIN pertemuan ON notes.pertemuan_id = pertemuan.id
    JOIN matkul ON pertemuan.matkul_id = matkul.id
    WHERE notes.is_public = 1
";

$params = [];

if ($selected_matkul) {
    $sql .= " AND matkul.id = ?";
    $params[] = $selected_matkul;
}

if (!empty($search)) {
    $sql .= " AND (notes.title LIKE ? OR notes.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY notes.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll();
// Ambil top 5 berdasarkan view
$top_views = $pdo->query("
    SELECT notes.id, notes.title, COUNT(note_views.id) AS view_count
    FROM notes
    LEFT JOIN note_views ON notes.id = note_views.note_id
    WHERE notes.is_public = 1
    GROUP BY notes.id
    ORDER BY view_count DESC
    LIMIT 5
")->fetchAll();

// Ambil top 5 berdasarkan like
$top_likes = $pdo->query("
    SELECT notes.id, notes.title, COUNT(note_likes.id) AS like_count
    FROM notes
    LEFT JOIN note_likes ON notes.id = note_likes.note_id
    WHERE notes.is_public = 1
    GROUP BY notes.id
    ORDER BY like_count DESC
    LIMIT 5
")->fetchAll();

// Siapkan view dan like count per note
$note_ids = array_column($notes, 'id');
$placeholders = implode(',', array_fill(0, count($note_ids), '?'));

// Ambil jumlah views
$views_map = [];
if ($note_ids) {
    $stmt = $pdo->prepare("
        SELECT note_id, COUNT(*) as count FROM note_views 
        WHERE note_id IN ($placeholders)
        GROUP BY note_id
    ");
    $stmt->execute($note_ids);
    foreach ($stmt as $row) {
        $views_map[$row['note_id']] = $row['count'];
    }

    // Ambil jumlah likes
    $likes_map = [];
    $stmt = $pdo->prepare("
        SELECT note_id, COUNT(*) as count FROM note_likes 
        WHERE note_id IN ($placeholders)
        GROUP BY note_id
    ");
    $stmt->execute($note_ids);
    foreach ($stmt as $row) {
        $likes_map[$row['note_id']] = $row['count'];
    }
}

?>

<main class="container my-5">

    <?php if (isset($_SESSION['user_id'])): ?>
        <section class="mb-5">
            <div class="alert alert-success rounded-4 shadow-sm p-4">
                <h4 class="alert-heading">Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h4>
                <p>Gunakan menu berikut untuk memulai:</p>
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= BASE_URL ?>user/dashboard.php" class="btn btn-primary btn-sm px-3">📚 Dashboard</a>
                    <a href="<?= BASE_URL ?>user/add_note.php" class="btn btn-success btn-sm px-3">➕ Tambah Catatan</a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="mb-5">
        <h4 class="text-center mb-4 fw-bold">🔥 Top Catatan</h4>
        <div class="row justify-content-center text-center mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <h6 class="mb-3">📈 Paling Banyak Dilihat</h6>
                <div class="d-flex flex-row overflow-auto gap-2 px-2 py-1">
                    <?php foreach ($top_views as $view): ?>
                        <a href="view_note.php?id=<?= $view['id'] ?>" class="badge rounded-pill text-white px-3 py-2" style="background: linear-gradient(90deg, #4facfe, #00f2fe);">
                            <?= htmlspecialchars($view['title']) ?> (<?= $view['view_count'] ?>x)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3">❤️ Paling Banyak Disukai</h6>
                <div class="d-flex flex-row overflow-auto gap-2 px-2 py-1">
                    <?php foreach ($top_likes as $like): ?>
                        <a href="view_note.php?id=<?= $like['id'] ?>" class="badge rounded-pill text-white px-3 py-2" style="background: linear-gradient(90deg, #ff758c, #ff7eb3);">
                            <?= htmlspecialchars($like['title']) ?> (<?= $like['like_count'] ?>)
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="public-notes">
        <h2 class="mb-4 text-center fw-bold">📖 Catatan Publik</h2>

        <!-- Form Filter dan Pencarian -->
        <form method="get" class="row g-3 justify-content-center mb-5">
            <div class="col-auto">
                <select name="matkul_id" id="matkul_id" class="form-select rounded-pill" onchange="this.form.submit()">
                    <option value="">-- Semua Mata Kuliah --</option>
                    <?php foreach ($matkul_list as $matkul): ?>
                        <option value="<?= $matkul['id'] ?>" <?= $selected_matkul == $matkul['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($matkul['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="q" class="form-control rounded-pill" placeholder="Cari judul atau isi..."
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary rounded-pill px-4">🔍 Cari</button>
            </div>
        </form>

        <!-- List Catatan -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (count($notes) > 0): ?>
                <?php foreach ($notes as $note):
                    $short = strip_tags($note['content']);
                    $preview = substr($short, 0, 70) . (strlen($short) > 70 ? '...' : '');
                    $bgColor = getColorFromMatkul($note['nama_matkul']);
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 rounded-4 hover-shadow" style="transition: transform 0.2s;">
                            <div class="card-body" style="background-color: <?= htmlspecialchars($bgColor) ?>;">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($note['title']) ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?= htmlspecialchars($note['nama_matkul']) ?> - Pertemuan ke-<?= htmlspecialchars($note['nomor_pertemuan']) ?> |
                                    oleh <?= htmlspecialchars($note['username']) ?>
                                </h6>
                                <p class="small text-muted mb-2">
                                    👁️ <?= $views_map[$note['id']] ?? 0 ?>x dilihat |
                                    ❤️ <?= $likes_map[$note['id']] ?? 0 ?> suka
                                </p>
                                <p class="card-text"><?= nl2br($preview) ?></p>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-end">
                                <a href="<?= BASE_URL ?>public/view_note.php?id=<?= $note['id'] ?>" class="btn btn-outline-dark btn-sm rounded-pill">Lihat Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center rounded-4">Tidak ditemukan catatan publik yang sesuai.</div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>


<?php require_once '../includes/footer.php'; ?>
