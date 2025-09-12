<?php
require_once '../config/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Catatan tidak ditemukan.");
}

$note_id = (int)$_GET['id'];
$type = isset($_GET['type']) ? strtolower($_GET['type']) : 'txt';

$stmt = $pdo->prepare("SELECT notes.*, users.username 
                       FROM notes
                       JOIN users ON notes.user_id = users.id
                       WHERE notes.id = ?");
$stmt->execute([$note_id]);
$note = $stmt->fetch();

if (!$note) {
    die("Catatan tidak ditemukan.");
}

// Cek akses
$can_view = $note['is_public'] || 
           (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $note['user_id']);
if (!$can_view) {
    die("Akses ditolak.");
}

// Bersihkan konten HTML untuk TXT
function stripHtmlContent($html) {
    return html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
}

$title = $note['title'];
$content = $note['content'];
$username = $note['username'];
$created = $note['created_at'];

if ($type === 'txt') {
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="catatan-' . $note_id . '.txt"');
    echo "Judul: $title\n";
    echo "Oleh: $username pada $created\n";
    echo "============================\n\n";
    echo stripHtmlContent($content);
    exit;
}

if ($type === 'pdf') {
    require_once '../vendor/autoload.php'; // Pastikan composer autoload sudah ada
    $dompdf = new Dompdf\Dompdf();

    $html = "<h2>" . htmlspecialchars($title) . "</h2>";
    $html .= "<p><i>oleh " . htmlspecialchars($username) . " pada $created</i></p>";
    $html .= "<div style='margin-top:10px; font-size:14px;'>" . $content . "</div>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("catatan-$note_id.pdf");
    exit;
}

die("Format ekspor tidak didukung.");
