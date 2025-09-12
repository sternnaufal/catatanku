<?php
define('BASE_PATH', __DIR__);
require BASE_PATH . '/../config/config.php';

// ambil token dari GET (untuk render form) atau POST (saat submit)
$token = $_GET['token'] ?? $_POST['token'] ?? null;

$validUser = null;
if ($token) {
    $stmt = $pdo->prepare("
        SELECT id, email 
          FROM users 
         WHERE reset_token = ? 
           AND reset_expires >= UTC_TIMESTAMP()
        LIMIT 1
    ");
    $stmt->execute([$token]);
    $validUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

// jika token invalid → langsung siapkan error (belum render HTML)
if (!$token || !$validUser) {
    $invalidToken = true;
}

// jika token valid & form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validUser) {
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($password !== $confirm) {
        $error = "Password dan konfirmasi tidak sama.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        $newHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE users 
               SET password = :pwd, reset_token = NULL, reset_expires = NULL 
             WHERE id = :id
        ");
        $stmt->execute([
            ':pwd' => $newHash,
            ':id'  => $validUser['id']
        ]);

        // sukses → redirect sebelum ada output HTML
        header("Location: login.php?reset=1");
        exit;
    }
}

// ---- Mulai render HTML ----
require BASE_PATH . '/../includes/header.php';
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">

          <?php if (!empty($invalidToken)): ?>
            <div class="alert alert-danger text-center">
              Link reset tidak valid atau sudah kadaluarsa.
              <div class="mt-2">
                <a href="forgot_password.php" class="btn btn-outline-danger btn-sm">Minta Link Baru</a>
              </div>
            </div>

          <?php else: ?>
            <h3 class="text-center mb-4">Reset Password</h3>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" novalidate>
              <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
              <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
              </div>
              <div class="mb-3">
                <label for="confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm" id="confirm" class="form-control" placeholder="Ulangi password" required>
              </div>
              <button type="submit" class="btn btn-success w-100">Ganti Password</button>
            </form>

            <div class="text-center mt-3">
              <a href="login.php" class="small text-decoration-none">Kembali ke Login</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<?php require BASE_PATH . '/../includes/footer.php'; ?>
