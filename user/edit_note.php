<?php
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/auth.php';

// Cek apakah ID disertakan dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Catatan tidak ditemukan.');
}

// Ambil note
$note_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $_SESSION['user_id']]);
$note = $stmt->fetch();

if (!$note) {
    die('Catatan tidak ditemukan atau bukan milik Anda.');
}

// Ambil daftar mata kuliah
$matkul = $pdo->query("SELECT * FROM matkul")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $matkul_id = $_POST['matkul_id'];
    $nomor_pertemuan = $_POST['nomor_pertemuan'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    // Cek atau buat pertemuan
    $stmt = $pdo->prepare("SELECT id FROM pertemuan WHERE matkul_id = ? AND nomor_pertemuan = ?");
    $stmt->execute([$matkul_id, $nomor_pertemuan]);
    $pertemuan = $stmt->fetch();

    if ($pertemuan) {
        $pertemuan_id = $pertemuan['id'];
    } else {
        $insert = $pdo->prepare("INSERT INTO pertemuan (matkul_id, nomor_pertemuan) VALUES (?, ?)");
        $insert->execute([$matkul_id, $nomor_pertemuan]);
        $pertemuan_id = $pdo->lastInsertId();
    }

    // Update note
    $update = $pdo->prepare("UPDATE notes SET pertemuan_id = ?, title = ?, content = ?, is_public = ? WHERE id = ? AND user_id = ?");
    $update->execute([$pertemuan_id, $title, $content, $is_public, $note_id, $_SESSION['user_id']]);

    header("Location: dashboard.php");
    exit;
}
?>

<h2>Edit Catatan</h2>
<form method="post">
    <label>Judul:<br>
        <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" required>
    </label><br><br>

    <label>Pilih Mata Kuliah:</label><br>
    <select name="matkul_id" required>
        <option disabled>-- Pilih --</option>
        <?php foreach ($matkul as $m): ?>
            <option value="<?= $m['id'] ?>"
                <?= $m['id'] == getMatkulIdByPertemuan($pdo, $note['pertemuan_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($m['nama']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Nomor Pertemuan:<br>
        <input type="number" name="nomor_pertemuan" value="<?= getNomorPertemuan($pdo, $note['pertemuan_id']) ?>" required>
    </label><br><br>

    <label>Isi Catatan:</label><br>
    <textarea name="content" rows="10" cols="60"><?= htmlspecialchars($note['content']) ?></textarea><br><br>

    <label><input type="checkbox" name="is_public" <?= $note['is_public'] ? 'checked' : '' ?>> Publik</label><br><br>

    <button type="submit">Update</button>
</form>
</body>
</html>

<?php
function getMatkulIdByPertemuan($pdo, $pertemuan_id) {
    $stmt = $pdo->prepare("SELECT matkul_id FROM pertemuan WHERE id = ?");
    $stmt->execute([$pertemuan_id]);
    return $stmt->fetchColumn();
}

function getNomorPertemuan($pdo, $pertemuan_id) {
    $stmt = $pdo->prepare("SELECT nomor_pertemuan FROM pertemuan WHERE id = ?");
    $stmt->execute([$pertemuan_id]);
    return $stmt->fetchColumn();
}
?>
