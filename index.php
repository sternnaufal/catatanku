<?php require_once 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center text-center text-white">
  <div class="container">
    <h1 class="display-3 fw-bold animate__animated animate__fadeInDown">Catatan Kuliah</h1>
    <p class="lead animate__animated animate__fadeInUp">
      Aplikasi catatan digital untuk pelajar & mahasiswa agar tetap rapi, terorganisir, dan produktif.
    </p>
    <a href="public/register.php" class="btn btn-gradient btn-lg mt-3">Daftar Sekarang</a>
  </div>
</section>

<!-- Fitur -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-5 fw-bold">✨ Fitur Unggulan</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow feature-card">
          <div class="card-body">
            <h3 class="mb-3">📒 Kelola Catatan</h3>
            <p class="card-text">Tambah, edit, dan hapus catatan penting dengan mudah dan cepat.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow feature-card">
          <div class="card-body">
            <h3 class="mb-3">🔐 Akses Aman</h3>
            <p class="card-text">Dengan sistem login, hanya kamu yang bisa mengakses catatanmu.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow feature-card">
          <div class="card-body">
            <h3 class="mb-3">🎨 Desain Minimalis</h3>
            <p class="card-text">Antarmuka sederhana dan bersih untuk pengalaman menulis yang nyaman.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimoni -->
<section class="py-5">
  <div class="container text-center">
    <h2 class="mb-4 fw-bold">💬 Apa Kata Pengguna?</h2>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <blockquote class="blockquote shadow p-4 bg-white rounded">
          <p class="mb-3 fst-italic">"Catatanku bantu aku mengatur deadline kuliah dan tugas harian. Super ringan dan simpel!"</p>
          <footer class="blockquote-footer">Dinda, Mahasiswi Teknik Informatika</footer>
        </blockquote>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section text-white text-center py-5">
  <div class="container">
    <h2 class="mb-3 fw-bold">Siap Menjadi Lebih Produktif?</h2>
    <a href="public/register.php" class="btn btn-outline-light btn-lg">🚀 Daftar Sekarang</a>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<!-- Custom CSS -->
<style>
  /* Hero dengan gradient */
  .hero-section {
    min-height: 100vh;
    background: linear-gradient(135deg, #2563eb, #9333ea);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 20px;
  }

  /* Tombol gradient */
  .btn-gradient {
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    border: none;
    border-radius: 50px;
    color: #fff;
    font-weight: bold;
    padding: 12px 30px;
    transition: transform 0.2s, box-shadow 0.3s;
  }
  .btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    color: #fff;
  }

  /* Card Fitur */
  .feature-card {
    border-radius: 15px;
    transition: transform 0.3s, box-shadow 0.3s;
  }
  .feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
  }

  /* CTA Section */
  .cta-section {
    background: linear-gradient(135deg, #111, #222);
  }
</style>
