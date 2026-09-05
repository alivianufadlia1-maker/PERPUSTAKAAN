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
    <div class="page-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Kelola Peminjaman</h1>
                <p class="text-muted mb-0">Pantau peminjaman dan pengembalian buku</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">📕</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahDipinjam); ?></div>
                        <div class="text-muted small fw-semibold">Sedang Dipinjam</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">⚠️</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahTerlambat); ?></div>
                        <div class="text-muted small fw-semibold">Terlambat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form filter -->
    <form action="/peminjaman" method="get" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari nama anggota atau judul buku..." name="cari"
                           value="<?= esc($cari ?? ''); ?>" aria-label="Cari peminjaman">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="status" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" <?= ($statusFilter ?? '') === 'dipinjam' ? 'selected' : ''; ?>>Dipinjam</option>
                    <option value="terlambat" <?= ($statusFilter ?? '') === 'terlambat' ? 'selected' : ''; ?>>Terlambat</option>
                    <option value="dikembalikan" <?= ($statusFilter ?? '') === 'dikembalikan' ? 'selected' : ''; ?>>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="/peminjaman" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Reset</a>
            </div>
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
                <div class="display-4 mb-3">📭</div>
                <h3 class="h5 fw-bold mb-2">Tidak Ada Data Peminjaman</h3>
                <p class="text-muted mb-0">Belum ada peminjaman yang cocok dengan filter saat ini.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <th>Anggota</th>
                            <th>Tanggal Pinjam</th>
                            <th>Wajib Kembali</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th class="text-end pe-4">Aksi</th>
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
                                            <div class="fw-semibold"><?= esc($p['judul']); ?></div>
                                            <small class="text-muted">ID <?= esc($p['id_buku']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold small"><?= esc($p['nama']); ?></div>
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
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-1 flex-wrap">
                                        <?php if ($p['status_tampil'] !== 'dikembalikan'): ?>
                                            <form action="/peminjaman/kembalikan/<?= esc($p['id_peminjaman']); ?>" method="post" class="d-inline">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-box-arrow-down me-1"></i>Tandai Dikembalikan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (($p['status_denda'] ?? null) === 'belum_bayar'): ?>
                                            <form action="/peminjaman/bayar-denda/<?= esc($p['id_peminjaman']); ?>" method="post" class="d-inline">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-cash-coin me-1"></i>Tandai Lunas
                                                </button>
                                            </form>
                                        <?php elseif ($p['status_tampil'] === 'dikembalikan'): ?>
                                            <span class="text-muted small align-self-center">Selesai</span>
                                        <?php endif; ?>
                                    </div>
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