<?php
ob_start();
require_once '../config/config.php';
require_once '../includes/header.php';
require_once '../includes/auth.php';

$matkul = $pdo->query("SELECT * FROM matkul")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $matkul_id = $_POST['matkul_id'];
    $nomor_pertemuan = $_POST['nomor_pertemuan'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    $user_id = $_SESSION['user_id'];

    // Cek apakah pertemuan sudah ada
    $stmt = $pdo->prepare("SELECT id FROM pertemuan WHERE matkul_id = ? AND nomor_pertemuan = ?");
    $stmt->execute([$matkul_id, $nomor_pertemuan]);
    $pertemuan = $stmt->fetch();

    if ($pertemuan) {
        $pertemuan_id = $pertemuan['id'];
    } else {
        // Buat pertemuan baru
        $stmt = $pdo->prepare("INSERT INTO pertemuan (matkul_id, nomor_pertemuan) VALUES (?, ?)");
        $stmt->execute([$matkul_id, $nomor_pertemuan]);
        $pertemuan_id = $pdo->lastInsertId();
    }

    // Simpan note
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, pertemuan_id, title, content, is_public) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $pertemuan_id, $title, $content, $is_public]);

    header("Location: dashboard.php");
    exit;
}
?>

<div class="container mt-5">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Tambah Catatan</h4>
        </div>
        <div class="card-body">

            <!-- Panduan Singkat -->
            <div class="alert alert-info rounded-4 d-flex flex-column" style="background-color:#e8f4fd; border-left:4px solid #007bff; padding:20px;">
                <h5 class="mb-3">📘 Panduan Singkat</h5>
                <ul class="mb-2" style="margin-left: -20px;">
                    <li><strong>Judul</strong>: Buat judul yang singkat dan jelas, misalnya <em>"Rangkuman tentang Tahu Bulat"</em>.</li>
                    <li><strong>Mata Kuliah</strong>: Pilih dari daftar mata kuliah yang sudah tersedia. Jika tidak tersedia, hubungi admin.</li>
                    <li><strong>Nomor Pertemuan</strong>: Isi dengan angka pertemuan (contoh: <em>3</em> untuk pertemuan ke-3).</li>
                    <li><strong>Isi Catatan</strong>: Gunakan editor untuk menulis catatan dengan format yang baik.</li>
                    <li><strong>Publik</strong>: Pilih status publikasi catatan.</li>
                </ul>
                <p class="mb-0">Semua catatan bisa diedit nanti dari dashboard.</p>
            </div>

            <!-- Form -->
            <form method="post" class="mt-4">
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Judul</label>
                    <input type="text" class="form-control rounded-pill" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="matkul_id" class="form-label fw-semibold">Mata Kuliah</label>
                    <select class="form-select rounded-pill" id="matkul_id" name="matkul_id" required>
                        <option disabled selected>-- Pilih --</option>
                        <?php foreach ($matkul as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nomor_pertemuan" class="form-label fw-semibold">Nomor Pertemuan</label>
                    <input type="number" class="form-control rounded-pill" id="nomor_pertemuan" name="nomor_pertemuan" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Isi Catatan</label>
                    <textarea class="form-control rounded-3" id="content" name="content" rows="6"></textarea>
                </div>

                <!-- Toggle Publik / Tidak Publik -->
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block mb-2">Status Publikasi</label>
                    <div class="btn-group" role="group" aria-label="Publik atau Tidak Publik">
                        <input type="radio" class="btn-check" name="is_public" id="public_yes" value="1" autocomplete="off" checked>
                        <label class="btn btn-outline-success rounded-pill" for="public_yes">🌍 Publik</label>

                        <input type="radio" class="btn-check" name="is_public" id="public_no" value="0" autocomplete="off">
                        <label class="btn btn-outline-secondary rounded-pill" for="public_no">🔒 Tidak Publik</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success rounded-pill px-4">Simpan</button>
            </form>
        </div>
    </div>
</div>

<style>
/* Hover effect untuk radio toggle */
.btn-check:checked + .btn {
    color: #fff;
}
.btn-outline-success.btn-check:checked + .btn {
    background-color: #28a745;
    border-color: #28a745;
}
.btn-outline-secondary.btn-check:checked + .btn {
    background-color: #6c757d;
    border-color: #6c757d;
}
</style>


</body>
</html>

<?php 
require_once '../includes/footer.php';
ob_end_flush();
?>