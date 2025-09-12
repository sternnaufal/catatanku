<?php
require_once '../config/config.php';
require_once '../includes/header.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hashed]);
            $success = "Registrasi berhasil! <a href='login.php' class='alert-link'>Login di sini</a>.";
        } catch (PDOException $e) {
            $error = "Email sudah digunakan.";
        }
    } else {
        $error = "Semua field wajib diisi.";
    }
}
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:60px; height:60px; font-size:1.5rem;">
                        📝
                    </div>
                    <h3 class="mt-3 mb-1 fw-bold">Daftar</h3>
                    <p class="text-muted small">Buat akun baru untuk mulai menulis catatan</p>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success small"><?= $success ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label small">Username</label>
                        <input type="text" name="username" id="username" class="form-control rounded-3" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label small">Password</label>
                        <input type="password" name="password" id="password" class="form-control rounded-3" required>
                    </div>
                    <button type="submit" class="btn btn-gradient w-100 rounded-3 py-2 fw-semibold">Daftar</button>
                </form>

                <div class="text-center mt-3">
                    <p class="small mb-1">Sudah punya akun? 
                        <a href="login.php" class="text-decoration-none fw-semibold">Login</a>
                    </p>
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
