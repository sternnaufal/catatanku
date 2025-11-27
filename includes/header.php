<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CatatanKuliah</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="<?= BASE_URL ?>assets/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
tinymce.init({
  selector: 'textarea[name="content"]',
  height: 300,
  menubar: false,
  plugins: 'link lists advlist image table code codesample fullscreen preview',
  toolbar: 'formatselect | styles | customBold customItalic customUnderline | forecolor backcolor | bullist numlist | undo redo | removeformat | alignleft aligncenter alignright alignjustify | link image | preview fullscreen code',
  content_css: 'default',
  branding: false,
  style_formats: [
    { title: 'Heading 1', block: 'h1' },
    { title: 'Heading 2', block: 'h2' },
    { title: 'Heading 3', block: 'h3' },
    { title: 'Heading 4', block: 'h4' },
    { title: 'Heading 5', block: 'h5' },
    { title: 'Paragraph', block: 'p' },
  ],
  toolbar_mode: 'wrap',
  setup: function (editor) {

    // ===== Custom Buttons with new tooltip =====
    editor.ui.registry.addButton('customBold', {
      tooltip: 'Bold (Ctrl+Shift+B)',
      icon: 'bold',
      onAction: function () {
        editor.execCommand('mceToggleFormat', false, 'bold');
      }
    });

    editor.ui.registry.addButton('customItalic', {
      tooltip: 'Italic (Ctrl+Shift+I)',
      icon: 'italic',
      onAction: function () {
        editor.execCommand('mceToggleFormat', false, 'italic');
      }
    });

    editor.ui.registry.addButton('customUnderline', {
      tooltip: 'Underline (Ctrl+Shift+U)',
      icon: 'underline',
      onAction: function () {
        editor.execCommand('mceToggleFormat', false, 'underline');
      }
    });

    // ===== Alignment Shortcuts =====
    editor.shortcuts.add('ctrl+l', 'Align left', () => {
      editor.execCommand('JustifyLeft');
    });

    editor.shortcuts.add('ctrl+e', 'Align center', () => {
      editor.execCommand('JustifyCenter');
    });

    // Ctrl+R: cegah refresh browser
    editor.on('keydown', function (e) {
      if (e.ctrlKey && e.key.toLowerCase() === 'r') {
        e.preventDefault();
        editor.execCommand('JustifyRight');
      }
    });

    editor.shortcuts.add('ctrl+j', 'Justify', () => {
      editor.execCommand('JustifyFull');
    });

    // ===== Formatting Shortcuts Override =====
    editor.on('keydown', function (e) {
      if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'b') {
        e.preventDefault();
        editor.execCommand('mceToggleFormat', false, 'bold');
      }
      if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i') {
        e.preventDefault();
        editor.execCommand('mceToggleFormat', false, 'italic');
      }
      if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'u') {
        e.preventDefault();
        editor.execCommand('mceToggleFormat', false, 'underline');
      }
    });

  }
});

    </script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<header class="main-header py-2 shadow-sm sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <h1 class="logo m-0 fw-bold text-primary">📝 Catatan Kuliah</h1>
    <nav class="nav-links">
        <a href='https://naufalrakha.my.id/' class="nav-item">Root</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href='<?= BASE_URL ?>public/index.php' class="nav-item">Beranda</a>
        <a href='<?= BASE_URL ?>public/logout.php' class="nav-item">Logout</a>
      <?php else: ?>
        <a href='<?= BASE_URL ?>public/login.php' class="nav-item">Login</a>
        <a href='<?= BASE_URL ?>public/register.php' class="nav-item btn-nav">Daftar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<style>
  /* Navbar kecil */
  .main-header {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(6px);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    z-index: 1000;
  }

  .logo {
    font-size: 1.2rem; /* lebih kecil */
  }

  .nav-links .nav-item {
    text-decoration: none;
    margin-left: 15px;
    color: #333;
    font-weight: 500;
    font-size: 0.9rem; /* lebih kecil */
    transition: color 0.3s, transform 0.2s;
  }

  .nav-links .nav-item:hover {
    color: #2563eb;
    transform: translateY(-1px);
  }

  .btn-nav {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.9rem;
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    color: #fff !important;
    font-weight: 600;
    transition: transform 0.2s, box-shadow 0.3s;
  }

  .btn-nav:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
  }
</style>


