<?php
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Ambil parameter pencarian (jika ada)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query SQL dasar
$sql = "SELECT notes.*, pertemuan.nomor_pertemuan, matkul.nama AS matkul_nama 
        FROM notes
        JOIN pertemuan ON notes.pertemuan_id = pertemuan.id
        JOIN matkul ON pertemuan.matkul_id = matkul.id
        WHERE notes.user_id = ?";

// Tambahkan kondisi pencarian jika ada input
$params = [$_SESSION['user_id']];
if (!empty($search)) {
    $sql .= " AND (notes.title LIKE ? OR notes.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY notes.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll();
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📚 Catatan Saya</h2>
        <a href="add_note.php" class="btn btn-primary">➕ Tambah Catatan</a>
    </div>

    <!-- Form Pencarian -->
    <form method="get" class="mb-3">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="🔍 Cari judul atau isi catatan, lalu klik enter untuk mencari...">
    </form>

    <?php if (count($notes) === 0): ?>
        <div class="alert alert-info">
            <?= $search ? "Tidak ada catatan yang cocok dengan pencarian '$search'." : "Belum ada catatan. Yuk buat dulu!" ?>
        </div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach ($notes as $note): 
            $content_preview = strip_tags($note['content']);
            $content_preview = mb_strlen($content_preview) > 150 ? mb_substr($content_preview, 0, 50) . '...' : $content_preview;
        ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($note['title']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">
                            <?= htmlspecialchars($note['matkul_nama']) ?> - Pertemuan ke: <?= $note['nomor_pertemuan'] ?>
                        </h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($content_preview)) ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="../public/view_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-outline-info">👁️ Lihat</a>
                        <div>
                            <a href="edit_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-outline-warning me-1">✏️ Edit</a>
                            <a href="delete_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus catatan ini?')">🗑️</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
</body>
</html>

<?php
require_once '../includes/footer.php';
?>
