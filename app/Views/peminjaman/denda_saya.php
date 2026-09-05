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
            <h1 class="h3 mb-0"><i class="bi bi-cash-coin me-2 text-primary"></i>Denda Saya</h1>
            <p class="text-muted mb-0">Riwayat denda keterlambatan dan konfirmasi pembayaran</p>
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

    <!-- Kartu ringkasan -->
    <div class="card border-0 shadow-sm mb-4 <?= (int) $totalBelumBayar > 0 ? 'border-danger' : ''; ?>">
        <div class="card-body d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3 <?= (int) $totalBelumBayar > 0 ? 'bg-danger bg-opacity-25' : 'bg-success bg-opacity-25'; ?>"
                 style="width: 56px; height: 56px; font-size: 1.6rem;">
                <?= (int) $totalBelumBayar > 0 ? '⚠️' : '✅'; ?>
            </div>
            <div>
                <div class="text-muted small fw-semibold">Total Denda Belum Dibayar</div>
                <div class="h3 fw-bold mb-0 <?= (int) $totalBelumBayar > 0 ? 'text-danger' : 'text-success'; ?>">
                    <?= format_rupiah($totalBelumBayar); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($denda)): ?>
        <!-- Empty state -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">🎉</div>
                <h3 class="h5 fw-bold mb-2">Tidak Ada Riwayat Denda</h3>
                <p class="text-muted mb-0">Anda tidak memiliki riwayat denda. Terima kasih selalu tepat waktu!</p>
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
                            <th>Tanggal Kembali</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($denda as $d): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/img/<?= esc($d['sampul']); ?>" alt="Sampul <?= esc($d['judul']); ?>"
                                             class="rounded border" width="34" height="44" style="object-fit: cover;"
                                             onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                                        <a href="/buku/<?= esc($d['id_buku']); ?>" class="fw-semibold text-decoration-none"><?= esc($d['judul']); ?></a>
                                    </div>
                                </td>
                                <td class="small"><?= esc($d['tanggal_pinjam']); ?></td>
                                <td class="small"><?= empty($d['tanggal_kembali']) ? '-' : esc($d['tanggal_kembali']); ?></td>
                                <td class="fw-semibold text-danger"><?= format_rupiah($d['denda']); ?></td>
                                <td>
                                    <?php if ($d['status_denda'] === 'belum_bayar'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Belum Bayar</span>
                                    <?php else: ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Lunas</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($d['status_denda'] === 'belum_bayar'): ?>
                                        <button type="button" class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalBayar<?= esc($d['id_peminjaman']); ?>">
                                            <i class="bi bi-cash-coin me-1"></i>Konfirmasi Sudah Bayar
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Selesai</span>
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

<?php foreach ($denda as $d): ?>
    <?php if ($d['status_denda'] === 'belum_bayar'): ?>
        <!-- Modal konfirmasi pembayaran (simulasi) -->
        <div class="modal fade" id="modalBayar<?= esc($d['id_peminjaman']); ?>" tabindex="-1"
             aria-labelledby="modalBayarLabel<?= esc($d['id_peminjaman']); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-body text-center p-4 p-md-5">
                        <div class="display-5 mb-3">💰</div>
                        <h3 class="h5 fw-bold mb-2" id="modalBayarLabel<?= esc($d['id_peminjaman']); ?>">Konfirmasi Pembayaran Denda</h3>
                        <p class="text-muted mb-4">
                            Ini adalah <strong>simulasi pembayaran</strong> (tidak terhubung ke payment gateway).<br>
                            Anda mengonfirmasi telah membayar denda
                            <strong><?= format_rupiah($d['denda']); ?></strong>
                            untuk buku "<strong><?= esc($d['judul']); ?></strong>".
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </button>
                            <form action="/peminjaman/konfirmasi-bayar/<?= esc($d['id_peminjaman']); ?>" method="post" class="d-inline">
                                <?= csrf_field(); ?>
                                <button type="submit" class="btn btn-warning px-4">
                                    <i class="bi bi-check-lg me-1"></i>Ya, Saya Sudah Bayar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?= $this->endSection(); ?>