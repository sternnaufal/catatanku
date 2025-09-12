<?php
require_once '../config/config.php';

// Approve user
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $stmt = $pdo->prepare("SELECT * FROM pending_users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user) {
        $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")
            ->execute([$user['username'], $user['email'], $user['password']]);

        $pdo->prepare("DELETE FROM pending_users WHERE id = ?")->execute([$id]);
    }

    header("Location: pending_users.php");
    exit;
}

// Ambil semua pending user
$users = $pdo->query("SELECT * FROM pending_users ORDER BY created_at DESC")->fetchAll();
?>

<h2>Pending Registrations</h2>
<table class="table">
    <thead>
        <tr>
            <th>Username</th><th>Email</th><th>Waktu Daftar</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['created_at'] ?></td>
                <td><a href="?approve=<?= $u['id'] ?>" class="btn btn-success btn-sm">Approve</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
