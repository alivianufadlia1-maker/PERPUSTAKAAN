<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
// Placeholder foto anggota
$placeholderFoto = 'data:image/svg+xml;charset=utf-8,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200">' .
    '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' .
    '<stop offset="0" stop-color="#667eea"/><stop offset="1" stop-color="#764ba2"/>' .
    '</linearGradient></defs>' .
    '<rect width="200" height="200" fill="url(#g)"/>' .
    '<text x="100" y="125" font-size="70" text-anchor="middle" fill="#ffffff" opacity="0.85">👤</text>' .
    '</svg>'
);

$kataKunci = trim((string) service('request')->getGet('cari'));
$isPencarian = ($kataKunci !== '');
?>

<div class="container">
    <!-- Header halaman -->
    <div class="page-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="/dashboard" class="btn btn-soft" aria-label="Kembali ke dashboard">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0"><i class="bi bi-people me-2 text-primary"></i>Daftar Anggota</h1>
                <p class="text-muted mb-0">Kelola data anggota perpustakaan</p>
            </div>
        </div>
        <a href="/anggota/tambah" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Anggota
        </a>
    </div>

    <!-- Form pencarian -->
    <form action="/anggota" method="get" class="mb-4">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Cari nama, email, atau no. telepon..." name="cari"
                   value="<?= esc($kataKunci); ?>" aria-label="Cari anggota">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
            <?php if ($isPencarian): ?>
                <a href="/anggota" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Reset
                </a>
            <?php endif; ?>
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

    <?php if (empty($anggota)): ?>
        <!-- Empty state -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3"><?= $isPencarian ? '🔍' : '👥'; ?></div>
                <h3 class="h5 fw-bold mb-2">
                    <?= $isPencarian ? 'Hasil Pencarian Tidak Ditemukan' : 'Belum Ada Anggota'; ?>
                </h3>
                <p class="text-muted mb-4">
                    <?= $isPencarian
                        ? 'Tidak ada anggota yang cocok dengan kata kunci "<strong>' . esc($kataKunci) . '</strong>".'
                        : 'Belum ada anggota yang terdaftar di perpustakaan.'; ?>
                </p>
                <?php if ($isPencarian): ?>
                    <a href="/anggota" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i>Tampilkan Semua Anggota
                    </a>
                <?php else: ?>
                    <a href="/anggota/tambah" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Anggota Pertama
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Anggota</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Terdaftar</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anggota as $a): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/img/<?= esc($a['foto']); ?>" alt="Foto <?= esc($a['nama']); ?>"
                                             class="rounded-circle border" width="42" height="42" style="object-fit: cover;"
                                             onerror="this.onerror=null;this.src='<?= $placeholderFoto; ?>'">
                                        <div>
                                            <div class="fw-semibold"><?= esc($a['nama']); ?></div>
                                            <small class="text-muted">ID <?= esc($a['id_anggota']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="bi bi-envelope me-1 text-muted"></i><?= esc($a['email']); ?>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-telephone me-1"></i><?= esc($a['no_telp']); ?>
                                    </small>
                                </td>
                                <td class="text-muted small"><?= esc($a['alamat']); ?></td>
                                <td class="small"><?= empty($a['tanggal_daftar']) ? '-' : esc($a['tanggal_daftar']); ?></td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/anggota/<?= esc($a['id_anggota']); ?>" class="btn btn-soft btn-sm"
                                       title="Lihat detail" aria-label="Lihat detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/anggota/ubah/<?= esc($a['id_anggota']); ?>" class="btn btn-warning btn-sm"
                                       title="Ubah" aria-label="Ubah data anggota">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btnHapus"
                                            title="Hapus" aria-label="Hapus anggota"
                                            data-bs-toggle="modal" data-bs-target="#modalHapus"
                                            data-nama="<?= esc($a['nama']); ?>"
                                            data-action="/anggota/<?= esc($a['id_anggota']); ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager !== null): ?>
            <div class="mt-4">
                <?= $pager->links('anggota', 'page_buku'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Modal konfirmasi hapus (dipakai bersama semua baris) -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-body text-center p-4 p-md-5">
                <div class="display-5 mb-3">🗑️</div>
                <h3 class="h5 fw-bold mb-2" id="modalHapusLabel">Hapus Anggota Ini?</h3>
                <p class="text-muted mb-4">
                    Anggota "<strong id="namaAnggota">...</strong>" akan dihapus permanen
                    dan tidak dapat dikembalikan.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <form id="formHapus" action="#" method="post" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btnHapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('namaAnggota').textContent = this.dataset.nama;
            document.getElementById('formHapus').setAttribute('action', this.dataset.action);
        });
    });
</script>

<?= $this->endSection(); ?>