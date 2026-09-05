<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Riwayat Peminjaman Anggota</h1>
            <p class="text-muted mb-0">Ringkasan aktivitas peminjaman setiap anggota</p>
        </div>
        <a href="/peminjaman/riwayat-anggota/cetak-semua" class="btn btn-primary ms-auto">
            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak Rekap Semua
        </a>
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

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">📊</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc(array_sum(array_map(fn ($r) => (int) $r['total_pinjam'], $statistik))); ?></div>
                        <div class="text-muted small fw-semibold">Total Peminjaman</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">👥</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahAnggotaPeminjam); ?></div>
                        <div class="text-muted small fw-semibold">Anggota Peminjam</div>
                    </div>
                </div>
            </div>
        </div>
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

    <?php if (empty($statistik)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">👥</div>
                <h3 class="h5 fw-bold mb-2">Belum Ada Anggota</h3>
                <p class="text-muted mb-0">Data anggota akan tampil di sini setelah ada yang terdaftar.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Anggota</th>
                            <th class="text-center">Total Pinjam</th>
                            <th class="text-center">Sedang Dipinjam</th>
                            <th class="text-center">Terlambat</th>
                            <th>Terakhir Pinjam</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statistik as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold"><?= esc($s['nama']); ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-envelope me-1"></i><?= esc($s['email']); ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill text-bg-primary"><?= esc($s['total_pinjam']); ?>×</span>
                                </td>
                                <td class="text-center">
                                    <?php if ((int) $s['aktif'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-book me-1"></i><?= esc($s['aktif']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ((int) $s['terlambat'] > 0): ?>
                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i><?= esc($s['terlambat']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small"><?= empty($s['terakhir_pinjam']) ? '-' : esc($s['terakhir_pinjam']); ?></td>
                                <td class="text-end pe-4">
                                    <a href="/peminjaman/riwayat-anggota/<?= esc($s['id_anggota']); ?>" class="btn btn-soft btn-sm">
                                        <i class="bi bi-eye me-1"></i>Lihat Riwayat
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