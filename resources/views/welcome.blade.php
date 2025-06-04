<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sistem Informasi Pengajuan Surat - Universitas Darma Persada</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f0f2f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      transition: background 0.3s ease;
    }
    /* Navbar */
    .navbar {
      background: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 1rem 2rem;
      font-weight: 600;
      transition: background 0.3s ease;
    }
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.5rem;
      color: #004080;
    }
    .navbar-brand img {
      height: 50px;
      width: auto;
      border-radius: 8px;
      box-shadow: 0 0 5px rgba(0, 64, 128, 0.3);
    }
    .btn-primary {
      background-color: #004080;
      border: none;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #003060;
      transform: scale(1.05);
    }
    .btn-outline-primary {
      color: #004080;
      border-color: #004080;
      font-weight: 600;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-outline-primary:hover {
      background-color: #004080;
      color: #fff;
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #004080 0%, #1a5298 100%);
      color: white;
      padding: 120px 15px 100px;
      text-align: center;
      clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
      position: relative;
      overflow: hidden;
    }
    .hero-section h1 {
      font-weight: 900;
      font-size: clamp(2.5rem, 5vw, 3.5rem);
      letter-spacing: 1.5px;
      margin-bottom: 1rem;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    .hero-section p {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      text-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
    }
    .hero-section .btn-light {
      font-weight: 600;
      padding: 0.75rem 2.25rem;
      font-size: 1.1rem;
      border-radius: 50px;
      box-shadow: 0 6px 12px rgba(255, 255, 255, 0.3);
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .hero-section .btn-light:hover {
      background-color: #e6e6e6;
      color: #004080;
      box-shadow: 0 8px 18px rgba(255, 255, 255, 0.5);
    }

    /* Features Section */
    .features {
      padding: 60px 15px;
      background: white;
    }
    .features h2 {
      font-weight: 800;
      font-size: 2.75rem;
      color: #003060;
      margin-bottom: 3rem;
      text-align: center;
      letter-spacing: 1.1px;
    }
    .features .card {
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 64, 128, 0.12);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      padding: 2.5rem 1.5rem;
      background: #f9fbff;
      text-align: center;
      cursor: pointer;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
    }
    .features .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 30px rgba(0, 64, 128, 0.2);
    }
    .features svg {
      margin-bottom: 1.5rem;
      filter: drop-shadow(0 0 1px rgba(0, 64, 128, 0.2));
    }
    .features h5 {
      font-weight: 700;
      margin-bottom: 1rem;
      color: #004080;
      font-size: 1.3rem;
    }
    .features p {
      font-size: 1rem;
      color: #444;
      line-height: 1.5;
      flex-grow: 1;
    }

    /* Flow Section */
    #alur-pengajuan {
      background: #e9f0ff;
      padding: 60px 15px;
      text-align: center;
    }
    #alur-pengajuan h2 {
      font-weight: 800;
      font-size: 2.5rem;
      margin-bottom: 3rem;
      color: #003060;
      letter-spacing: 1.1px;
    }
    .flow-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
      flex-wrap: wrap;
      max-width: 900px;
      margin: 0 auto;
      position: relative;
    }
    .flow-step {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(0, 64, 128, 0.15);
      padding: 2rem 2.5rem;
      font-weight: 700;
      font-size: 1.25rem;
      color: #004080;
      position: relative;
      min-width: 140px;
      flex-grow: 1;
      cursor: default;
      user-select: none;
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .flow-step:hover {
      transform: translateY(-8px);
      box-shadow: 0 14px 32px rgba(0, 64, 128, 0.25);
    }

    /* Arrows between steps */
    .flow-arrow {
      flex-shrink: 0;
      width: 40px;
      height: 40px;
      position: relative;
      top: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      user-select: none;
    }
    /* SVG arrow styling */
    .flow-arrow svg {
      width: 28px;
      height: 28px;
      fill: #004080;
      filter: drop-shadow(0 0 1px rgba(0, 64, 128, 0.4));
      transition: transform 0.3s ease;
    }

    /* Connecting lines */
    .flow-container::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 4px;
      background: #004080;
      z-index: -1;
      transform: translateY(-50%);
    }

    /* Responsive vertical layout for narrow screens */
    @media (max-width: 600px) {
      .flow-container {
        flex-direction: column;
        gap: 1.5rem;
      }
      .flow-arrow {
        width: 100%;
        height: 40px;
        margin-left: auto;
        margin-right: auto;
      }
      .flow-arrow svg {
        transform: rotate(90deg);
      }
    }

    /* Call to Action Section */
    #pengajuan {
      background: #f8f9fa;
      padding: 70px 15px;
      text-align: center;
    }
    #pengajuan h2 {
      font-weight: 800;
      font-size: 2.25rem;
      margin-bottom: 1rem;
      color: #003060;
    }
    #pengajuan p {
      font-size: 1.1rem;
      margin-bottom: 2rem;
      color: #555;
    }
    #pengajuan .btn-primary {
      font-weight: 700;
      padding: 0.75rem 2.5rem;
      font-size: 1.15rem;
      border-radius: 50px;
      box-shadow: 0 4px 12px rgba(0, 64, 128, 0.25);
      transition: background-color 0.3s ease, transform 0.3s ease;
    }
    #pengajuan .btn-primary:hover {
      background-color: #002040;
      box-shadow: 0 6px 18px rgba(0, 64, 128, 0.35);
      transform: scale(1.05);
    }

    /* Footer */
    footer {
      background: #004080;
      color: white;
      padding: 25px 15px;
      text-align: center;
      font-weight: 500;
      font-size: 0.95rem;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="https://lpm1.unsada.ac.id/wp-content/uploads/2021/07/logo-unsada-asli-300x300-1.png" alt="Logo Universitas Darma Persada" />
        Universitas Darma Persada
      </a>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1>Sistem Informasi Pengajuan Surat</h1>
      <p>Universitas Darma Persada - Memudahkan pengajuan surat secara online</p>
      <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login</a>
      <a href="{{ route('register') }}" class="btn btn-light btn-lg">Register</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="container">
      <h2>Fitur Utama</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#004080" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                <path d="M5 7h6v1H5V7z"/>
                <path d="M5 9h6v1H5V9z"/>
                <path d="M5 11h4v1H5v-1z"/>
                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM10.5 3a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-3z"/>
              </svg>
            </div>
            <h5>Pengajuan Online</h5>
            <p>Mengajukan surat secara mudah tanpa harus datang langsung ke kantor administrasi.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#004080" class="bi bi-clock-history" viewBox="0 0 16 16">
                <path d="M8.515 3.515a.5.5 0 0 0-.71.71L8.793 5.21 7.5 6.5h.5a.5.5 0 0 0 0-1H7v1.5a.5.5 0 0 0 1 0v-2a.5.5 0 0 0-.485-.485z"/>
                <path d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 1 1 .908-.418A6 6 0 1 1 8 2v1z"/>
              </svg>
            </div>
            <h5>Proses Cepat</h5>
            <p>Surat disetujui dan diproses dengan waktu yang efisien tanpa antrian panjang.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div>
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#004080" class="bi bi-shield-lock" viewBox="0 0 16 16">
                <path d="M5.5 8.5a1 1 0 1 1 2 0v1a1 1 0 0 1-2 0v-1z"/>
                <path d="M7.5 1a7 7 0 0 0-5.5 2.9C2 7.9 3 11.3 7.5 15a7 7 0 0 0 5.5-2.9c1-1.4 1-4.5-2-6.5v-2z"/>
              </svg>
            </div>
            <h5>Keamanan Data</h5>
            <p>Data pengajuan Anda dijaga kerahasiaannya dengan standar keamanan tinggi.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Flow Section -->
  <section id="alur-pengajuan">
    <div class="container">
      <h2>Alur Pengajuan Surat</h2>
      <div class="flow-container" role="list" aria-label="Alur Pengajuan Surat">
        <div class="flow-step" role="listitem" tabindex="0">Login / Register</div>
        <div class="flow-arrow" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5l8 7-8 7V5z"/></svg>
        </div>
        <div class="flow-step" role="listitem" tabindex="0">Membuat Pengajuan</div>
        <div class="flow-arrow" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5l8 7-8 7V5z"/></svg>
        </div>
        <div class="flow-step" role="listitem" tabindex="0">Tunggu Proses</div>
        <div class="flow-arrow" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M8 5l8 7-8 7V5z"/></svg>
        </div>
        <div class="flow-step mt-3" role="listitem" tabindex="0">Selesai</div>
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section id="pengajuan">
    <div class="container">
      <h2>Ajukan Surat Anda Sekarang</h2>
      <p>Mulai proses pengajuan surat dengan mengisi form online kami</p>
      <a href="#" class="btn btn-primary">Form Pengajuan</a>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p>&copy; 2025 Universitas Darma Persada. All Rights Reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
