<?php
define('BASE_PATH', __DIR__);
require BASE_PATH . '/../config/config.php';
require BASE_PATH . '/../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');

    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // generate token
        $token = bin2hex(random_bytes(32));

        // simpan token + expiry via MySQL time (UTC) agar konsisten
        $stmt = $pdo->prepare("
            UPDATE users 
               SET reset_token = :token, 
                   reset_expires = DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 HOUR)
             WHERE id = :id
        ");
        $stmt->execute([
            ':token' => $token,
            ':id'    => $user['id']
        ]);

        // link reset (pakai BASE_URL kalau kamu punya)
        $resetLink = 'reset_password.php?token=' . urlencode($token);
        $success = "Link reset password: <a href='{$resetLink}'>Klik di sini</a> (simulasi).";
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
          <h3 class="text-center mb-4">Lupa Password</h3>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
          <?php else: ?>
            <form method="POST" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email terdaftar" required autofocus>
              </div>
              <button type="submit" class="btn btn-primary w-100">Kirim Link Reset</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require BASE_PATH . '/../includes/footer.php'; ?>
