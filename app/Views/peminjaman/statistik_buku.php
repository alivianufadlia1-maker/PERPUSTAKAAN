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
            <h1 class="h3 mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Statistik Peminjaman Buku</h1>
            <p class="text-muted mb-0">Ringkasan frekuensi peminjaman setiap judul buku</p>
        </div>
        <a href="/peminjaman/statistik-buku/cetak" class="btn btn-primary ms-auto">
            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak Rekap PDF
        </a>
    </div>

    <!-- Pesan flashdata -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('error')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">🏆</div>
                    <div class="min-w-0">
                        <div class="text-muted small fw-semibold">Buku Paling Sering Dipinjam</div>
                        <?php if ($bukuTerpopuler !== null && (int) $bukuTerpopuler['total_dipinjam'] > 0): ?>
                            <div class="fw-bold text-truncate"><?= esc($bukuTerpopuler['judul']); ?></div>
                            <div class="small text-muted"><?= esc($bukuTerpopuler['total_dipinjam']); ?>× dipinjam</div>
                        <?php else: ?>
                            <div class="text-muted small">Belum ada data peminjaman</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-secondary bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">📭</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahBelumDipinjam); ?></div>
                        <div class="text-muted small fw-semibold">Buku Belum Pernah Dipinjam</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">⚠️</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahTerlambat); ?></div>
                        <div class="text-muted small fw-semibold">Total Keterlambatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form filter & urutan -->
    <form action="/peminjaman/statistik-buku" method="get" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari judul buku..." name="cari"
                           value="<?= esc($cari ?? ''); ?>" aria-label="Cari judul buku">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="sort" aria-label="Urutkan">
                    <option value="" <?= ($sort ?? '') === '' ? 'selected' : ''; ?>>Paling Sering Dipinjam</option>
                    <option value="judul" <?= ($sort ?? '') === 'judul' ? 'selected' : ''; ?>>Judul A-Z</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="/peminjaman/statistik-buku" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Reset</a>
            </div>
        </div>
    </form>

    <?php if (empty($statistik)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">📊</div>
                <h3 class="h5 fw-bold mb-2">Tidak Ada Data Buku</h3>
                <p class="text-muted mb-0">Tidak ada buku yang cocok dengan pencarian saat ini.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <th class="text-center">Total Dipinjam</th>
                            <th class="text-center">Sedang Dipinjam</th>
                            <th class="text-center">Terlambat</th>
                            <th>Terakhir Dipinjam</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistik as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/img/<?= esc($s['sampul']); ?>" alt="Sampul <?= esc($s['judul']); ?>"
                                             class="rounded border" width="34" height="44" style="object-fit: cover;"
                                             onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                                        <div>
                                            <div class="fw-semibold"><?= esc($s['judul']); ?></div>
                                            <small class="text-muted"><i class="bi bi-person me-1"></i><?= esc($s['pengarang']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill text-bg-primary"><?= esc($s['total_dipinjam']); ?>×</span>
                                </td>
                                <td class="text-center">
                                    <?php if ((int) $s['sedang_dipinjam'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-book me-1"></i><?= esc($s['sedang_dipinjam']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ((int) $s['jumlah_terlambat'] > 0): ?>
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= esc($s['jumlah_terlambat']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?= empty($s['terakhir_dipinjam']) ? '-' : esc($s['terakhir_dipinjam']); ?></td>
                                <td class="text-end pe-4">
                                    <a href="/peminjaman/statistik-buku/<?= esc($s['id_buku']); ?>" class="btn btn-soft btn-sm">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
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