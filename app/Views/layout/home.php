<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
// Placeholder untuk sampul yang tidak ada / gagal dimuat
$placeholderSampul = 'data:image/svg+xml;charset=utf-8,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="400">' .
    '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' .
    '<stop offset="0" stop-color="#667eea"/><stop offset="1" stop-color="#764ba2"/>' .
    '</linearGradient></defs>' .
    '<rect width="300" height="400" fill="url(#g)"/>' .
    '<text x="150" y="215" font-size="60" text-anchor="middle" fill="#ffffff" opacity="0.85">📖</text>' .
    '</svg>'
);

$sessHome    = session();
$isLoggedIn  = (bool) $sessHome->get('is_logged_in');
$roleHome    = $sessHome->get('role');
$usernameHome = $sessHome->get('username');
$kataKunci   = trim((string) service('request')->getGet('cari'));
?>

<style>
    .hero-katalog {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1.5rem;
        padding: 3.5rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(74, 45, 110, 0.25);
    }

    .hero-katalog::before,
    .hero-katalog::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }

    .hero-katalog::before {
        width: 260px;
        height: 260px;
        top: -90px;
        right: -60px;
    }

    .hero-katalog::after {
        width: 160px;
        height: 160px;
        bottom: -60px;
        left: 30%;
    }

    .hero-katalog .badge-hero {
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(4px);
        padding: 0.5em 1em;
        border-radius: 50rem;
        font-weight: 600;
    }

    .hero-katalog h1 {
        font-weight: 800;
        letter-spacing: -1px;
    }

    .hero-katalog .input-group {
        border-radius: 50rem;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
        max-width: 520px;
    }

    .hero-katalog .form-control,
    .hero-katalog .input-group-text {
        border: none;
        background: #fff;
    }

    .hero-katalog .form-control:focus {
        box-shadow: none;
    }

    .fitur-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.9rem;
        font-size: 1.4rem;
        background: var(--primary-light);
        color: var(--primary);
        flex-shrink: 0;
    }

    .cta-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1.25rem;
        color: #fff;
    }
</style>

<div class="container">
    <!-- HERO -->
    <div class="hero-katalog mb-4">
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-7">
                <span class="badge-hero mb-3 d-inline-flex align-items-center">
                    <i class="bi bi-journal-bookmark-fill me-2"></i>Perpustakaan Digital
                </span>
                <h1 class="display-5 mb-3 mt-3">
                    Temukan Buku <span class="text-warning">Favoritmu</span>,<br>Baca &amp; Kembalikan dengan Mudah
                </h1>
                <p class="lead opacity-75 mb-4" style="max-width: 520px;">
                    Jelajahi koleksi buku perpustakaan kami. Cari judul, pinjam sebagai anggota, dan pantau
                    jadwal pengembalianmu di satu tempat.
                </p>

                <!-- Pencarian langsung -->
                <form action="/buku" method="get">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-search text-primary"></i></span>
                        <input type="text" class="form-control" name="cari" placeholder="Cari judul buku..."
                               value="<?= esc($kataKunci); ?>" aria-label="Cari buku">
                        <button class="btn btn-warning fw-semibold" type="submit">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <span style="font-size: 9rem; line-height: 1; filter: drop-shadow(0 12px 20px rgba(0,0,0,0.25));">📖</span>
            </div>
        </div>
    </div>

    <!-- Banner CTA sesuai status login -->
    <?php if (! $isLoggedIn): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="fitur-icon"><i class="bi bi-person-plus"></i></div>
                <div class="flex-grow-1">
                    <h2 class="h6 fw-bold mb-1">Ingin meminjam buku?</h2>
                    <p class="text-muted mb-0 small">Masuk sebagai anggota untuk meminjam buku, atau daftar
                        akun baru secara gratis.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/login" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                    <a href="/register" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>Daftar Anggota
                    </a>
                </div>
            </div>
        </div>
    <?php elseif ($roleHome === 'anggota'): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="fitur-icon"><i class="bi bi-person-check"></i></div>
                <div class="flex-grow-1">
                    <h2 class="h6 fw-bold mb-1">Halo, <?= esc($usernameHome); ?>! 👋</h2>
                    <p class="text-muted mb-0 small">Jelajahi katalog lalu pinjam buku favoritmu, atau lihat
                        riwayat peminjamanmu.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/dashboard" class="btn btn-soft">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                    <a href="/peminjaman/riwayat" class="btn btn-primary">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Peminjaman
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="fitur-icon"><i class="bi bi-shield-check"></i></div>
                <div class="flex-grow-1">
                    <h2 class="h6 fw-bold mb-1">Halo, Admin! 🛡️</h2>
                    <p class="text-muted mb-0 small">Kelola koleksi buku, data anggota, dan peminjaman dari dashboard.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/dashboard" class="btn btn-soft">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                    <a href="/buku/tambah" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Buku
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Katalog -->
    <div class="page-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div>
            <h1 class="h2 mb-1"><i class="bi bi-stars me-2 text-primary"></i>Katalog Terbaru</h1>
            <p class="text-muted mb-0">
                <?= $totalBuku; ?> buku tersedia di perpustakaan
                <?php if (count($buku) < $totalBuku): ?>— menampilkan <?= count($buku); ?> terbaru<?php endif; ?>
            </p>
        </div>
        <a href="/buku" class="btn btn-outline-primary">
            Lihat Semua Buku <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <?php if (empty($buku)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">📚</div>
                <h3 class="h5 fw-bold mb-2">Koleksi Masih Kosong</h3>
                <p class="text-muted mb-0">Buku pertama akan segera hadir di perpustakaan kami.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 g-md-4">
            <?php foreach ($buku as $b): ?>
                <div class="col">
                    <div class="card card-hover h-100 overflow-hidden">
                        <img src="/img/<?= esc($b['sampul']); ?>" class="buku-cover"
                             alt="Sampul <?= esc($b['judul']); ?>"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                        <div class="card-body d-flex flex-column p-3">
                            <h5 class="card-title card-title-clamp fw-bold fs-6 mb-1"><?= esc($b['judul']); ?></h5>
                            <p class="card-text text-muted small mb-3">
                                <i class="bi bi-person me-1"></i><?= esc($b['pengarang']); ?>
                            </p>
                            <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                <span class="badge-tahun">
                                    <i class="bi bi-calendar3 me-1"></i><?= empty($b['tahun_terbit']) ? '-' : esc($b['tahun_terbit']); ?>
                                </span>
                                <a href="/buku/<?= esc($b['id_buku']); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- CTA bawah untuk tamu -->
    <?php if (! $isLoggedIn): ?>
        <div class="cta-banner text-center p-4 p-md-5 mt-5">
            <h2 class="h4 fw-bold mb-2">Yuk gabung jadi anggota! 🎉</h2>
            <p class="opacity-75 mb-4">Daftar gratis, langsung bisa pinjam buku dari katalog.</p>
            <a href="/register" class="btn btn-light fw-semibold px-4">
                <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>