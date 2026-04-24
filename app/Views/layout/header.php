<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toko Buku - Oursawithd</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
      }

      .navbar {
        background-color: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px); /* Efek Glassmorphism */
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        padding: 15px 0;
      }

      .navbar-brand {
        font-weight: 700;
        color: #764ba2 !important;
        letter-spacing: -0.5px;
      }

      .nav-link {
        font-weight: 500;
        color: #555 !important;
        margin: 0 10px;
        transition: color 0.3s ease;
      }

      .nav-link:hover, .nav-link.active {
        color: #764ba2 !important;
      }

      /* Indicator garis bawah saat hover */
      .nav-link {
        position: relative;
      }
      .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: #764ba2;
        transition: width 0.3s ease;
      }
      .nav-link:hover::after {
        width: 100%;
      }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <a class="navbar-brand" href="#">📚 Toko Buku</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav ms-auto"> <a class="nav-link active" aria-current="page" href="/pages">Home</a>
            <a class="nav-link" href="/buku">Daftar Buku</a>
            <a class="nav-link" href="#">Daftar Anggota</a>
          </div>
        </div>
      </div>
    </nav>

    <main class="py-4">
      <?= $this->renderSection('content');?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>