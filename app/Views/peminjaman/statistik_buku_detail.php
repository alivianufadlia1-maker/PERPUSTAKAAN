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
        <a href="/peminjaman/statistik-buku" class="btn btn-soft" aria-label="Kembali ke statistik buku">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Statistik Buku</h1>
            <p class="text-muted mb-0">Riwayat peminjaman buku <strong><?= esc($buku['judul']); ?></strong></p>
        </div>
    </div>

    <!-- Info buku -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <img src="/img/<?= esc($buku['sampul']); ?>" alt="Sampul <?= esc($buku['judul']); ?>"
                         class="buku-cover-sm rounded-3 border shadow-sm" style="max-width: 110px; height: auto;"
                         onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                </div>
                <div class="col">
                    <h2 class="h4 fw-bold mb-2"><?= esc($buku['judul']); ?></h2>
                    <div class="row g-2 small">
                        <div class="col-sm-4">
                            <div class="text-muted"><i class="bi bi-person me-1"></i>Pengarang</div>
                            <div class="fw-semibold"><?= esc($buku['pengarang'] ?? '-'); ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted"><i class="bi bi-building me-1"></i>Penerbit</div>
                            <div class="fw-semibold"><?= esc($buku['penerbit'] ?? '-'); ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted"><i class="bi bi-calendar3 me-1"></i>Tahun Terbit</div>
                            <div class="fw-semibold"><?= empty($buku['tahun_terbit']) ? '-' : esc($buku['tahun_terbit']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-auto text-center">
                    <div class="h3 fw-bold mb-0 text-primary"><?= esc($totalDipinjam); ?></div>
                    <div class="text-muted small fw-semibold">Total Dipinjam</div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($peminjaman)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">📭</div>
                <h3 class="h5 fw-bold mb-2">Belum Pernah Dipinjam</h3>
                <p class="text-muted mb-0">Buku ini belum pernah dipinjam oleh anggota mana pun.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Anggota</th>
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
                                    <div class="fw-semibold"><?= esc($p['nama']); ?></div>
                                    <small class="text-muted">ID <?= esc($p['id_anggota']); ?></small>
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