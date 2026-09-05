<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('error')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('pesan')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <h1 class="h2 mb-1"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h1>
        <p class="text-muted mb-0">
            Halo, <strong><?= esc($username); ?></strong>!
            <?= $role === 'admin' ? 'Selamat bekerja, berikut ringkasan perpustakaan.' : 'Selamat datang di perpustakaan.'; ?>
        </p>
    </div>

    <?php if ($role === 'admin'): ?>
        <div class="row g-3 g-md-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3"
                             style="width: 56px; height: 56px; background: var(--primary-light); font-size: 1.6rem;">
                            📚
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0"><?= esc($jumlahBuku); ?></div>
                            <div class="text-muted small fw-semibold">Jumlah Buku</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3"
                             style="width: 56px; height: 56px; background: var(--primary-light); font-size: 1.6rem;">
                            👥
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0"><?= esc($jumlahAnggota); ?></div>
                            <div class="text-muted small fw-semibold">Jumlah Anggota</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-25"
                             style="width: 56px; height: 56px; font-size: 1.6rem;">
                            📕
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0"><?= esc($jumlahDipinjam); ?></div>
                            <div class="text-muted small fw-semibold">Sedang Dipinjam</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3 p-4">
                        <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25"
                             style="width: 56px; height: 56px; font-size: 1.6rem;">
                            ⚠️
                        </div>
                        <div>
                            <div class="h3 fw-bold mb-0"><?= esc($jumlahTerlambat); ?></div>
                            <div class="text-muted small fw-semibold">Terlambat</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total denda belum dibayar -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-25"
                     style="width: 52px; height: 52px; font-size: 1.5rem;">💰</div>
                <div>
                    <div class="h4 fw-bold mb-0 text-danger"><?= format_rupiah($totalDendaBelumBayar ?? 0); ?></div>
                    <div class="text-muted small fw-semibold">Total Denda Belum Dibayar</div>
                </div>
                <a href="/peminjaman" class="btn btn-outline-danger btn-sm ms-auto">
                    <i class="bi bi-cash-coin me-1"></i>Kelola Pembayaran
                </a>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="/buku" class="btn btn-primary">
                <i class="bi bi-journal-bookmark me-1"></i>Kelola Buku
            </a>
            <a href="/anggota" class="btn btn-outline-primary">
                <i class="bi bi-people me-1"></i>Kelola Anggota
            </a>
            <a href="/peminjaman" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left-right me-1"></i>Kelola Peminjaman
            </a>
            <a href="/peminjaman/statistik-buku" class="btn btn-outline-primary">
                <i class="bi bi-graph-up me-1"></i>Statistik Buku
            </a>
        </div>

        <!-- Buku terpopuler -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="h5 fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Buku Terpopuler</h3>
                <a href="/peminjaman/statistik-buku" class="small fw-semibold text-decoration-none">
                    Lihat Semua Statistik <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <?php if ($bukuTerpopuler === null): ?>
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-0">Belum ada data peminjaman tercatat.</p>
                </div>
            <?php else: ?>
                <div class="card-body px-4 pb-4">
                    <div class="d-flex align-items-center gap-3">
                        <img src="/img/<?= esc($bukuTerpopuler['sampul']); ?>" alt="Sampul <?= esc($bukuTerpopuler['judul']); ?>"
                             class="rounded border shadow-sm" width="48" height="64" style="object-fit: cover;"
                             onerror="this.onerror=null;this.src='data:image/svg+xml;charset=utf-8,' + encodeURIComponent('<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;60&quot; height=&quot;80&quot;><rect width=&quot;60&quot; height=&quot;80&quot; fill=&quot;#f3eefb&quot;/></svg>')">
                        <div class="flex-grow-1 min-w-0">
                            <a href="/buku/<?= esc($bukuTerpopuler['id_buku']); ?>" class="fw-bold text-decoration-none text-truncate d-block">
                                <?= esc($bukuTerpopuler['judul']); ?>
                            </a>
                            <div class="small text-muted">
                                Dipinjam <strong><?= esc($bukuTerpopuler['total_dipinjam']); ?>×</strong> — terakhir
                                <?= empty($bukuTerpopuler['terakhir_dipinjam']) ? '-' : esc($bukuTerpopuler['terakhir_dipinjam']); ?>
                            </div>
                        </div>
                        <a href="/peminjaman/statistik-buku/<?= esc($bukuTerpopuler['id_buku']); ?>" class="btn btn-soft btn-sm">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php if ((int) ($totalDendaSaya ?? 0) > 0): ?>
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>
                    <strong>Anda memiliki denda <?= format_rupiah($totalDendaSaya); ?> yang belum dibayar.</strong>
                    Segera konfirmasi pembayaran Anda.
                </div>
                <a href="/peminjaman/denda-saya" class="btn btn-warning btn-sm ms-auto flex-shrink-0">
                    <i class="bi bi-cash-coin me-1"></i>Bayar Denda
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="display-5 mb-3">👋</div>
                <h3 class="h5 fw-bold mb-2">Selamat Datang, <?= esc($username); ?>!</h3>
                <p class="text-muted mb-4">
                    Saat ini tersedia <strong><?= esc($jumlahBuku); ?></strong> judul buku yang bisa Anda baca.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="/buku" class="btn btn-primary">
                        <i class="bi bi-journal-bookmark me-1"></i>Jelajahi Katalog
                    </a>
                    <a href="/peminjaman/riwayat" class="btn btn-outline-primary">
                        <i class="bi bi-clock-history me-1"></i>Riwayat Peminjaman
                    </a>
                    <a href="/profil" class="btn btn-outline-primary">
                        <i class="bi bi-person me-1"></i>Profil Saya
                    </a>
                </div>
            </div>
        </div>

        <!-- Buku yang sedang dipinjam anggota -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h3 class="h5 fw-bold mb-0"><i class="bi bi-book me-2 text-primary"></i>Buku yang Sedang Anda Pinjam</h3>
            </div>
            <?php if (empty($pinjamanAktif)): ?>
                <div class="card-body text-center py-4">
                    <p class="text-muted mb-0">Anda tidak sedang meminjam buku apa pun saat ini.</p>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($pinjamanAktif as $p): ?>
                        <div class="list-group-item d-flex flex-wrap align-items-center gap-3 px-4 py-3">
                            <img src="/img/<?= esc($p['sampul']); ?>" alt="Sampul <?= esc($p['judul']); ?>"
                                 class="rounded border" width="36" height="48" style="object-fit: cover;"
                                 onerror="this.onerror=null;this.src='data:image/svg+xml;charset=utf-8,' + encodeURIComponent('<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;60&quot; height=&quot;80&quot;><rect width=&quot;60&quot; height=&quot;80&quot; fill=&quot;#f3eefb&quot;/></svg>')">
                            <div class="flex-grow-1">
                                <a href="/buku/<?= esc($p['id_buku']); ?>" class="fw-semibold text-decoration-none"><?= esc($p['judul']); ?></a>
                                <div class="small text-muted">
                                    Wajib kembali: <strong><?= esc($p['tanggal_wajib_kembali']); ?></strong>
                                </div>
                            </div>
                            <?php if ($p['status_tampil'] === 'terlambat'): ?>
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Terlambat</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-book me-1"></i>Dipinjam</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>