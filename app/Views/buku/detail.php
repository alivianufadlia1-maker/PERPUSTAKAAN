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

// Status login & role untuk menampilkan tombol aksi yang tepat.
// $bukuTersedia, $estimasiTersedia, $ringkasanPinjam, dan $dendaAnggota sudah
// disiapkan oleh Buku::detail() (controller) — view hanya merender data yang diterima.
$sessDetail = session();
$roleDetail = $sessDetail->get('role');
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/buku" class="btn btn-soft" aria-label="Kembali ke daftar buku">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Detail Buku</h1>
            <p class="text-muted mb-0">Informasi lengkap buku perpustakaan</p>
        </div>
    </div>

    <!-- Pesan flashdata -->
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('pesan')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('error')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($buku)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">😕</div>
                <h3 class="h5 fw-bold mb-2">Buku Tidak Ditemukan</h3>
                <p class="text-muted mb-4">Data buku yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="/buku" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Buku
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row g-0">
                <!-- Sampul -->
                <div class="col-md-4 col-lg-3 bg-light">
                    <img src="/img/<?= esc($buku['sampul']); ?>" class="w-100 h-100 buku-cover"
                         alt="Sampul <?= esc($buku['judul']); ?>"
                         onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                </div>

                <!-- Informasi -->
                <div class="col-md-8 col-lg-9">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge-tahun">
                                <i class="bi bi-calendar3 me-1"></i>Tahun <?= empty($buku['tahun_terbit']) ? '-' : esc($buku['tahun_terbit']); ?>
                            </span>
                            <span class="badge bg-light text-secondary border">
                                <i class="bi bi-hash me-1"></i>ID <?= esc($buku['id_buku']); ?>
                            </span>
                            <?php if ($bukuTersedia): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Tersedia
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-book me-1"></i>Sedang Dipinjam<?= ! empty($estimasiTersedia) ? ' — perkiraan tersedia ' . esc($estimasiTersedia) : ''; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="h3 fw-bold mb-4"><?= esc($buku['judul']); ?></h2>

                        <dl class="row mb-4 g-3">
                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-person me-1"></i>Pengarang
                            </dt>
                            <dd class="col-sm-9"><?= esc($buku['pengarang']); ?></dd>

                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-building me-1"></i>Penerbit
                            </dt>
                            <dd class="col-sm-9"><?= esc($buku['penerbit']); ?></dd>

                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>Tahun Terbit
                            </dt>
                            <dd class="col-sm-9"><?= empty($buku['tahun_terbit']) ? '-' : esc($buku['tahun_terbit']); ?></dd>
                        </dl>

                        <?php if ((int) $dendaAnggota > 0): ?>
                            <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <div class="small">
                                    Anda memiliki denda <strong><?= format_rupiah($dendaAnggota); ?></strong>
                                    yang belum dibayar dari peminjaman sebelumnya.
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Tombol aksi -->
                        <div class="d-flex flex-wrap gap-2">
                            <?php if ($roleDetail === 'admin'): ?>
                                <a href="/buku/ubah/<?= esc($buku['id_buku']); ?>" class="btn btn-warning">
                                    <i class="bi bi-pencil-square me-1"></i>Ubah
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalHapus">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            <?php endif; ?>

                            <?php if ($roleDetail === 'anggota' && $bukuTersedia): ?>
                                <form action="/peminjaman/pinjam/<?= esc($buku['id_buku']); ?>" method="post">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-book me-1"></i>Pinjam Buku
                                    </button>
                                </form>
                            <?php elseif ($roleDetail === 'anggota' && ! $bukuTersedia): ?>
                                <span class="btn btn-secondary disabled">
                                    <i class="bi bi-book me-1"></i>Sedang Dipinjam
                                </span>
                            <?php endif; ?>

                            <a href="/buku" class="btn btn-outline-secondary ms-auto">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($roleDetail === 'admin'): ?>
            <!-- Riwayat Peminjaman (khusus admin) -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="h5 fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman</h3>
                </div>
                <?php if ((int) $ringkasanPinjam['total'] === 0): ?>
                    <div class="card-body text-center py-4">
                        <p class="text-muted mb-0">Buku ini belum pernah dipinjam.</p>
                    </div>
                <?php else: ?>
                    <div class="card-body px-4 pb-4">
                        <p class="text-muted small mb-3">
                            Sudah dipinjam <strong><?= esc($ringkasanPinjam['total']); ?>×</strong> —
                            <?= count($ringkasanPinjam['riwayat']); ?> peminjaman terakhir:
                        </p>
                        <div class="list-group list-group-flush">
                            <?php foreach ($ringkasanPinjam['riwayat'] as $r): ?>
                                <div class="list-group-item px-0 d-flex flex-wrap align-items-center gap-2 py-3">
                                    <i class="bi bi-person-circle text-primary fs-5"></i>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-semibold"><?= esc($r['nama']); ?></div>
                                        <div class="small text-muted">
                                            Pinjam: <?= esc($r['tanggal_pinjam']); ?>
                                            <?= empty($r['tanggal_kembali']) ? '' : ' · Kembali: ' . esc($r['tanggal_kembali']); ?>
                                        </div>
                                    </div>
                                    <?php if ($r['status_tampil'] === 'dikembalikan'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Dikembalikan</span>
                                    <?php elseif ($r['status_tampil'] === 'terlambat'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Terlambat</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-book me-1"></i>Dipinjam</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if (!empty($buku)): ?>
    <!-- Modal konfirmasi hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body text-center p-4 p-md-5">
                    <div class="display-5 mb-3">🗑️</div>
                    <h3 class="h5 fw-bold mb-2" id="modalHapusLabel">Hapus Buku Ini?</h3>
                    <p class="text-muted mb-4">
                        Buku "<strong><?= esc($buku['judul']); ?></strong>" akan dihapus permanen
                        dan tidak dapat dikembalikan.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <form action="/buku/<?= esc($buku['id_buku']); ?>" method="post" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-trash me-1"></i>Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection(); ?>