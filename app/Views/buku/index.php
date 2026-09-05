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

$kataKunci = trim((string) service('request')->getGet('cari'));
$isPencarian = ($kataKunci !== '');
?>

<div class="container">
    <!-- Header halaman -->
    <div class="page-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div>
            <h1 class="h2 mb-1"><i class="bi bi-journal-bookmark me-2 text-primary"></i>Daftar Buku</h1>
            <p class="text-muted mb-0">
                Koleksi buku perpustakaan
                <?php if ($isPencarian): ?>
                    — hasil pencarian "<strong><?= esc($kataKunci); ?></strong>"
                <?php else: ?>
                    (<?= count($buku); ?> buku di halaman ini)
                <?php endif; ?>
            </p>
        </div>
        <a href="/buku/tambah" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Buku
        </a>
    </div>

    <!-- Form pencarian -->
    <form action="/buku" method="get" class="mb-4">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Cari judul buku..." name="cari"
                   value="<?= esc($kataKunci); ?>" aria-label="Cari buku">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
            <?php if ($isPencarian): ?>
                <a href="/buku" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Pesan flashdata -->
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('pesan')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($buku)): ?>
        <!-- Empty state -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3"><?= $isPencarian ? '🔍' : '📚'; ?></div>
                <h3 class="h5 fw-bold mb-2">
                    <?= $isPencarian ? 'Hasil Pencarian Tidak Ditemukan' : 'Belum Ada Buku'; ?>
                </h3>
                <p class="text-muted mb-4">
                    <?= $isPencarian
                        ? 'Tidak ada buku yang cocok dengan kata kunci "<strong>' . esc($kataKunci) . '</strong>". Coba kata kunci lain.'
                        : 'Koleksi masih kosong. Yuk tambahkan buku pertama ke perpustakaan.'; ?>
                </p>
                <?php if ($isPencarian): ?>
                    <a href="/buku" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Tampilkan Semua Buku
                    </a>
                <?php else: ?>
                    <a href="/buku/tambah" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Buku Pertama
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Grid katalog buku -->
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 g-md-4">
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

        <!-- Pagination -->
        <?php if (isset($pager) && $pager !== null): ?>
            <?= $pager->links('buku', 'page_buku'); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>