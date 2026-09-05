<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-clipboard-data me-2 text-primary"></i>Laporan</h1>
            <p class="text-muted mb-0">Pusat cetak laporan perpustakaan dalam format PDF</p>
        </div>
    </div>

    <!-- Kartu laporan -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm card-hover h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-25"
                             style="width: 52px; height: 52px; font-size: 1.5rem; color: var(--primary);">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="h6 fw-bold mb-0">Log Pembayaran Denda</h2>
                            <small class="text-muted">Catatan pelunasan denda anggota</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="h3 fw-bold mb-0"><?= (int) $totalTransaksiLunas; ?>
                            <span class="fs-6 fw-semibold text-muted">transaksi lunas</span>
                        </div>
                        <div class="small text-muted">Total terbayar <?= format_rupiah($totalDendaTerbayar); ?></div>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="/peminjaman/log-pembayaran" class="btn btn-soft btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Lihat Halaman
                        </a>
                        <a href="/peminjaman/log-pembayaran/export-pdf" class="btn btn-primary btn-sm flex-fill">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm card-hover h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-25"
                             style="width: 52px; height: 52px; font-size: 1.5rem; color: var(--primary);">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="h6 fw-bold mb-0">Rekap Semua Anggota</h2>
                            <small class="text-muted">Ringkasan peminjaman per anggota</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="h3 fw-bold mb-0"><?= (int) $totalAnggota; ?>
                            <span class="fs-6 fw-semibold text-muted">anggota terdaftar</span>
                        </div>
                        <div class="small text-muted">Termasuk yang belum pernah meminjam</div>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="/peminjaman/riwayat-anggota" class="btn btn-soft btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Lihat Halaman
                        </a>
                        <a href="/peminjaman/riwayat-anggota/cetak-semua" class="btn btn-primary btn-sm flex-fill">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm card-hover h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-25"
                             style="width: 52px; height: 52px; font-size: 1.5rem; color: var(--primary);">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="h6 fw-bold mb-0">Rekap Statistik Buku</h2>
                            <small class="text-muted">Frekuensi peminjaman per judul</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="h3 fw-bold mb-0"><?= (int) $totalBuku; ?>
                            <span class="fs-6 fw-semibold text-muted">buku terdaftar</span>
                        </div>
                        <div class="small text-muted">Termasuk yang belum pernah dipinjam</div>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="/peminjaman/statistik-buku" class="btn btn-soft btn-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>Lihat Halaman
                        </a>
                        <a href="/peminjaman/statistik-buku/cetak" class="btn btn-primary btn-sm flex-fill">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cetak riwayat per anggota -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25"
                     style="width: 52px; height: 52px; font-size: 1.5rem; flex-shrink: 0;">
                    <i class="bi bi-person-badge text-danger"></i>
                </div>
                <div class="flex-grow-1">
                    <h2 class="h6 fw-bold mb-1">Cetak Riwayat Per-Anggota</h2>
                    <p class="text-muted small mb-0">
                        PDF detail riwayat peminjaman satu anggota tertentu (lengkap dengan info anggota
                        dan ringkasannya). Karena butuh memilih anggota dulu, cetak dilakukan dari halaman
                        detail anggota — pilih anggota dari daftar, lalu tekan tombol "Cetak PDF" di sana.
                    </p>
                </div>
                <a href="/peminjaman/riwayat-anggota" class="btn btn-outline-primary flex-shrink-0">
                    <i class="bi bi-person-lines-fill me-1"></i>Pilih Anggota
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
