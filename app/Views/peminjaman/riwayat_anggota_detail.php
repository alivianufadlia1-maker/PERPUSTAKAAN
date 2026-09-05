<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
$placeholderSampul = 'data:image/svg+xml;charset=utf-8,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="400">' .
    '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' .
    '<stop offset="0" stop-color="#667eea"/><stop offset="1" stop-color="#764ba2"/>' .
    '</linearGradient></defs>' .
    '<rect width="300" height="400" fill="url(#g)"/>' .
    '<text x="150" y="215" font-size="60" text-anchor="middle" fill="#ffffff" opacity="0.85">📖</text>' .
    '</svg>'
);
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/peminjaman/riwayat-anggota" class="btn btn-soft" aria-label="Kembali ke riwayat anggota">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>Riwayat Peminjaman</h1>
            <p class="text-muted mb-0">Detail riwayat peminjaman anggota <strong><?= esc($anggota['nama']); ?></strong></p>
        </div>
        <a href="/peminjaman/riwayat-anggota/<?= (int) $anggota['id_anggota']; ?>/cetak" class="btn btn-primary ms-auto">
            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
        </a>
    </div>

    <!-- Info anggota -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4 col-lg-3 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                         style="width: 96px; height: 96px; font-size: 2.6rem; background: var(--primary-light); color: var(--primary);">
                        <?php if (! empty($anggota['foto'])): ?>
                            <img src="/img/<?= esc($anggota['foto']); ?>" alt="Foto <?= esc($anggota['nama']); ?>"
                                 class="rounded-4 w-100 h-100" style="object-fit: cover;"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml;charset=utf-8,<?= rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"><rect width="96" height="96" fill="#f3eefb"/><text x="48" y="60" font-size="40" text-anchor="middle">👤</text></svg>'); ?>'">
                        <?php else: ?>
                            <i class="bi bi-person-fill"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8 col-lg-9">
                    <h2 class="h5 fw-bold mb-2"><?= esc($anggota['nama']); ?></h2>
                    <div class="row g-2 small">
                        <div class="col-sm-6">
                            <div class="text-muted"><i class="bi bi-envelope me-1"></i>Email</div>
                            <div class="fw-semibold"><?= esc($anggota['email']); ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted"><i class="bi bi-telephone me-1"></i>No. Telepon</div>
                            <div class="fw-semibold"><?= esc($anggota['no_telp'] ?? '-'); ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted"><i class="bi bi-geo-alt me-1"></i>Alamat</div>
                            <div class="fw-semibold"><?= esc($anggota['alamat'] ?? '-'); ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted"><i class="bi bi-calendar-plus me-1"></i>Terdaftar</div>
                            <div class="fw-semibold"><?= empty($anggota['tanggal_daftar']) ? '-' : esc($anggota['tanggal_daftar']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($peminjaman)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">📚</div>
                <h3 class="h5 fw-bold mb-2">Belum Pernah Meminjam</h3>
                <p class="text-muted mb-0">Anggota ini belum memiliki riwayat peminjaman.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Wajib Kembali</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjaman as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/img/<?= esc($p['sampul']); ?>" alt="Sampul <?= esc($p['judul']); ?>"
                                             class="rounded border" width="34" height="44" style="object-fit: cover;"
                                             onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                                        <div>
                                            <a href="/buku/<?= esc($p['id_buku']); ?>" class="fw-semibold text-decoration-none"><?= esc($p['judul']); ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="small"><?= esc($p['tanggal_pinjam']); ?></td>
                                <td class="small <?= $p['status_tampil'] === 'terlambat' ? 'text-danger fw-semibold' : ''; ?>">
                                    <?= esc($p['tanggal_wajib_kembali']); ?>
                                </td>
                                <td class="small"><?= empty($p['tanggal_kembali']) ? '-' : esc($p['tanggal_kembali']); ?></td>
                                <td>
                                    <?php if ($p['status_tampil'] === 'dikembalikan'): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Dikembalikan</span>
                                    <?php elseif ($p['status_tampil'] === 'terlambat'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Terlambat</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-book me-1"></i>Dipinjam</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>