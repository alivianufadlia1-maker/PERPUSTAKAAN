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
        <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman Saya</h1>
            <p class="text-muted mb-0">Daftar buku yang pernah dan sedang Anda pinjam</p>
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

    <?php if (empty($peminjaman)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">📚</div>
                <h3 class="h5 fw-bold mb-2">Belum Ada Peminjaman</h3>
                <p class="text-muted mb-4">Anda belum pernah meminjam buku dari perpustakaan.</p>
                <a href="/buku" class="btn btn-primary">
                    <i class="bi bi-journal-bookmark me-1"></i>Jelajahi Katalog Buku
                </a>
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
                            <th>Denda</th>
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
                                <td>
                                    <?php if ((int) ($p['denda'] ?? 0) > 0): ?>
                                        <div class="fw-semibold text-danger"><?= format_rupiah($p['denda']); ?></div>
                                        <?php if (($p['status_denda'] ?? null) === 'belum_bayar'): ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Belum Bayar</span>
                                        <?php elseif (($p['status_denda'] ?? null) === 'lunas'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
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