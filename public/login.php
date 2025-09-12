<?php
session_start(); // <-- HARUS DI SINI
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = "Email atau password salah.";
    }
}

// ---- mulai output HTML ----
require_once '../includes/header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="col-md-5">
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body p-4">
        <div class="text-center mb-4">
          <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px; font-size:1.5rem;">
            🔒
          </div>
          <h3 class="mt-3 mb-1 fw-bold">Login</h3>
          <p class="text-muted small">Masuk untuk mengakses catatan kuliahmu</p>
        </div>

        <?php if (!empty($error)) : ?>
          <div class="alert alert-danger small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="mb-3">
            <label for="email" class="form-label small">Email</label>
            <input type="email" name="email" id="email" class="form-control rounded-3" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label small">Password</label>
            <input type="password" name="password" id="password" class="form-control rounded-3" required>
          </div>
          <button type="submit" class="btn btn-gradient w-100 rounded-3 py-2 fw-semibold">Login</button>
        </form>

        <div class="text-center mt-3">
          <p class="small mb-1">Belum punya akun? 
            <a href="register.php" class="text-decoration-none fw-semibold">Daftar</a>
          </p>
          <p class="small"><a href="forgot_password.php" class="text-decoration-none text-muted">Lupa password?</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .btn-gradient {
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    border: none;
    color: #fff;
    transition: transform 0.2s, box-shadow 0.3s;
  }
  .btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  }
</style>

<?php require_once '../includes/footer.php'; ?>
