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

// Query string filter aktif, dibawa ke tombol export
$queryExport = '';
if (! empty($cari)) {
    $queryExport .= (empty($queryExport) ? '?' : '&') . 'cari=' . rawurlencode($cari);
}
if (! empty($filterJalur)) {
    $queryExport .= (empty($queryExport) ? '?' : '&') . 'dibayar_oleh=' . rawurlencode($filterJalur);
}
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Log Pembayaran Denda</h1>
            <p class="text-muted mb-0">Catatan pembayaran denda — siapa, kapan, dan lewat jalur apa</p>
        </div>
        <div class="ms-auto d-flex gap-2">
            <a href="/peminjaman/log-pembayaran/export-csv<?= $queryExport; ?>" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
            </a>
            <a href="/peminjaman/log-pembayaran/export-pdf<?= $queryExport; ?>" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">💰</div>
                    <div>
                        <div class="h4 fw-bold mb-0 text-success"><?= format_rupiah($totalTerbayar); ?></div>
                        <div class="text-muted small fw-semibold">Total Denda Terbayar</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">🧾</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc($jumlahTransaksi); ?></div>
                        <div class="text-muted small fw-semibold">Jumlah Transaksi</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 bg-info bg-opacity-25"
                         style="width: 46px; height: 46px; font-size: 1.3rem;">🛡️</div>
                    <div>
                        <div class="h4 fw-bold mb-0"><?= esc(count($pembayaran)); ?></div>
                        <div class="text-muted small fw-semibold">Baris Tampil</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form filter -->
    <form action="/peminjaman/log-pembayaran" method="get" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari nama anggota atau judul buku..." name="cari"
                           value="<?= esc($cari ?? ''); ?>" aria-label="Cari pembayaran">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="dibayar_oleh" aria-label="Filter jalur pembayaran">
                    <option value="" <?= ($filterJalur ?? '') === '' ? 'selected' : ''; ?>>Semua Jalur</option>
                    <option value="admin" <?= ($filterJalur ?? '') === 'admin' ? 'selected' : ''; ?>>Lewat Admin</option>
                    <option value="mandiri" <?= ($filterJalur ?? '') === 'mandiri' ? 'selected' : ''; ?>>Mandiri (Anggota)</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="/peminjaman/log-pembayaran" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Reset</a>
            </div>
        </div>
    </form>

    <?php if (empty($pembayaran)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">🧾</div>
                <h3 class="h5 fw-bold mb-2">
                    <?= empty($cari) && empty($filterJalur) ? 'Belum Ada Pembayaran' : 'Tidak Ada Pembayaran yang Cocok'; ?>
                </h3>
                <p class="text-muted mb-0">
                    <?= empty($cari) && empty($filterJalur)
                        ? 'Belum ada denda yang dikonfirmasi lunas.'
                        : 'Tidak ada pembayaran yang sesuai dengan filter saat ini.'; ?>
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Anggota</th>
                            <th>Buku</th>
                            <th>Denda</th>
                            <th>Tanggal Bayar</th>
                            <th>Jalur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pembayaran as $p): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold"><?= esc($p['nama']); ?></div>
                                    <small class="text-muted">ID <?= esc($p['id_anggota']); ?></small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/img/<?= esc($p['sampul']); ?>" alt="Sampul <?= esc($p['judul']); ?>"
                                             class="rounded border" width="34" height="44" style="object-fit: cover;"
                                             onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                                        <a href="/buku/<?= esc($p['id_buku']); ?>" class="fw-semibold text-decoration-none"><?= esc($p['judul']); ?></a>
                                    </div>
                                </td>
                                <td class="fw-semibold text-danger"><?= format_rupiah($p['denda']); ?></td>
                                <td class="small">
                                    <?= empty($p['tanggal_bayar']) ? '<span class="text-muted">-</span>' : esc(date('d M Y H:i', strtotime($p['tanggal_bayar']))); ?>
                                </td>
                                <td>
                                    <?php if (($p['dibayar_oleh'] ?? null) === 'admin'): ?>
                                        <span class="badge text-bg-primary"><i class="bi bi-shield-check me-1"></i>Admin</span>
                                    <?php elseif (($p['dibayar_oleh'] ?? null) === 'mandiri'): ?>
                                        <span class="badge bg-success"><i class="bi bi-person-check me-1"></i>Mandiri</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-secondary border"><i class="bi bi-question-circle me-1"></i>Tidak Tercatat</span>
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