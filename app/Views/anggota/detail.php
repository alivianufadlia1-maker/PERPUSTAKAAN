<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
$placeholderFoto = 'data:image/svg+xml;charset=utf-8,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' .
    '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' .
    '<stop offset="0" stop-color="#667eea"/><stop offset="1" stop-color="#764ba2"/>' .
    '</linearGradient></defs>' .
    '<rect width="300" height="300" fill="url(#g)"/>' .
    '<text x="150" y="185" font-size="110" text-anchor="middle" fill="#ffffff" opacity="0.85">👤</text>' .
    '</svg>'
);
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/anggota" class="btn btn-soft" aria-label="Kembali ke daftar anggota">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Detail Anggota</h1>
            <p class="text-muted mb-0">Informasi lengkap anggota perpustakaan</p>
        </div>
    </div>

    <?php if (empty($anggota)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">😕</div>
                <h3 class="h5 fw-bold mb-2">Anggota Tidak Ditemukan</h3>
                <p class="text-muted mb-4">Data anggota yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="/anggota" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Anggota
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row g-0">
                <!-- Foto -->
                <div class="col-md-4 col-lg-3 bg-light d-flex align-items-center justify-content-center p-4">
                    <img src="/img/<?= esc($anggota['foto']); ?>" class="rounded-circle border shadow-sm"
                         style="width: 180px; height: 180px; object-fit: cover;"
                         alt="Foto <?= esc($anggota['nama']); ?>"
                         onerror="this.onerror=null;this.src='<?= $placeholderFoto; ?>'">
                </div>

                <!-- Informasi -->
                <div class="col-md-8 col-lg-9">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge-tahun">
                                <i class="bi bi-person-badge me-1"></i>Anggota
                            </span>
                            <span class="badge bg-light text-secondary border">
                                <i class="bi bi-hash me-1"></i>ID <?= esc($anggota['id_anggota']); ?>
                            </span>
                        </div>

                        <h2 class="h3 fw-bold mb-4"><?= esc($anggota['nama']); ?></h2>

                        <dl class="row mb-4 g-3">
                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </dt>
                            <dd class="col-sm-9"><?= esc($anggota['email']); ?></dd>

                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-telephone me-1"></i>No. Telepon
                            </dt>
                            <dd class="col-sm-9"><?= esc($anggota['no_telp']); ?></dd>

                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-geo-alt me-1"></i>Alamat
                            </dt>
                            <dd class="col-sm-9"><?= esc($anggota['alamat']); ?></dd>

                            <dt class="col-sm-3 text-muted fw-semibold">
                                <i class="bi bi-calendar3 me-1"></i>Tanggal Daftar
                            </dt>
                            <dd class="col-sm-9"><?= empty($anggota['tanggal_daftar']) ? '-' : esc($anggota['tanggal_daftar']); ?></dd>
                        </dl>

                        <!-- Tombol aksi -->
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/anggota/ubah/<?= esc($anggota['id_anggota']); ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square me-1"></i>Ubah
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalHapus">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                            <a href="/anggota" class="btn btn-outline-secondary ms-auto">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (! empty($anggota)): ?>
    <!-- Modal konfirmasi hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body text-center p-4 p-md-5">
                    <div class="display-5 mb-3">🗑️</div>
                    <h3 class="h5 fw-bold mb-2" id="modalHapusLabel">Hapus Anggota Ini?</h3>
                    <p class="text-muted mb-4">
                        Anggota "<strong><?= esc($anggota['nama']); ?></strong>" akan dihapus permanen
                        (termasuk akun loginnya, jika ada) dan tidak dapat dikembalikan.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <form action="/anggota/<?= esc($anggota['id_anggota']); ?>" method="post" class="d-inline">
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