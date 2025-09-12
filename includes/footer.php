<footer class="footer text-white mt-5">
  <div class="container py-4">
    <div class="row text-center text-md-start">
      <!-- Branding -->
      <div class="col-md-6 mb-3">
        <h5 class="fw-bold">📝 CatatanKuliah</h5>
        <p class="small">
          Aplikasi catatan pribadi untuk pelajar dan mahasiswa.<br>
          Catatan bisa dibuat, diedit, disimpan, dan dibaca kembali secara privat dan aman.
        </p>
        <p class="small mb-0">
          Dibuat oleh 
          <strong><a href="https://instagram.com/stern_naufal2712" target="_blank" class="footer-link">Naufal Rakha Putra</a></strong>.
        </p>
      </div>

      <!-- Sosial Media -->
      <div class="col-md-6 d-flex align-items-center justify-content-center justify-content-md-end">
        <a href="https://youtube.com/@naufaltechtainment1" target="_blank" class="footer-social mx-2">YouTube</a>
        <a href="https://github.com/sternnaufal" target="_blank" class="footer-social mx-2">GitHub</a>
        <a href="mailto:naufalrakha2712@gmail.com" class="footer-social mx-2">Email</a>
      </div>
    </div>

    <hr class="my-3 text-secondary">

    <div class="text-center small">
      <p class="mb-0">&copy; <?= date('Y') ?> CatatanKuliah. All rights reserved.</p>
    </div>
  </div>
</footer>

<style>
  .footer {
    background: linear-gradient(135deg, #1e1e2f, #2d2d44);
    color: #bbb;
  }

  .footer a {
    text-decoration: none;
  }

  .footer-link {
    color: #4facfe;
    transition: color 0.3s;
  }
  .footer-link:hover {
    color: #00f2fe;
  }

  .footer-social {
    color: #bbb;
    font-weight: 500;
    transition: color 0.3s, transform 0.2s;
  }
  .footer-social:hover {
    color: #4facfe;
    transform: translateY(-2px);
  }
</style>
