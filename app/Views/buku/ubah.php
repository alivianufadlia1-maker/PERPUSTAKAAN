<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
// Gabungkan error validasi dari session (redirect()->withInput()) dan service validation
$semuaError = array_merge(
    (array) (session('_ci_validation_errors') ?? []),
    (array) $validation->getErrors()
);

// Placeholder untuk sampul yang tidak ada / gagal dimuat
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
        <a href="/buku" class="btn btn-soft" aria-label="Kembali ke daftar buku">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Ubah Buku</h1>
            <p class="text-muted mb-0">Perbarui data buku "<strong><?= esc($buku['judul']); ?></strong>"</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            <form action="/buku/update/<?= esc($buku['id_buku']); ?>" method="post" enctype="multipart/form-data" novalidate>
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="sampulLama" value="<?= esc($buku['sampul']); ?>">

                <!-- Judul -->
                <div class="mb-4">
                    <label for="inputJudul" class="form-label">
                        Judul Buku <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                        <input type="text" class="form-control <?= isset($semuaError['judul']) ? 'is-invalid' : ''; ?>"
                               id="inputJudul" name="judul"
                               value="<?= esc(old('judul') ? old('judul') : $buku['judul']); ?>"
                               placeholder="Contoh: Laskar Pelangi" autofocus>
                        <?php if (isset($semuaError['judul'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['judul']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pengarang -->
                <div class="mb-4">
                    <label for="inputPengarang" class="form-label">Pengarang</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control <?= isset($semuaError['pengarang']) ? 'is-invalid' : ''; ?>"
                               id="inputPengarang" name="pengarang"
                               value="<?= esc(old('pengarang') ? old('pengarang') : $buku['pengarang']); ?>"
                               placeholder="Nama pengarang buku">
                        <?php if (isset($semuaError['pengarang'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['pengarang']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Penerbit -->
                <div class="mb-4">
                    <label for="inputPenerbit" class="form-label">Penerbit</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" class="form-control <?= isset($semuaError['penerbit']) ? 'is-invalid' : ''; ?>"
                               id="inputPenerbit" name="penerbit"
                               value="<?= esc(old('penerbit') ? old('penerbit') : $buku['penerbit']); ?>"
                               placeholder="Nama penerbit">
                        <?php if (isset($semuaError['penerbit'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['penerbit']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tahun Terbit -->
                <div class="mb-4">
                    <label for="inputTahun" class="form-label">Tahun Terbit</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="number" class="form-control <?= isset($semuaError['tahun_terbit']) ? 'is-invalid' : ''; ?>"
                               id="inputTahun" name="tahun_terbit"
                               value="<?= esc(old('tahun_terbit') ? old('tahun_terbit') : $buku['tahun_terbit']); ?>"
                               min="1000" max="<?= date('Y'); ?>" placeholder="<?= date('Y'); ?>">
                        <?php if (isset($semuaError['tahun_terbit'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['tahun_terbit']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sampul -->
                <div class="mb-4">
                    <label for="sampul" class="form-label">Sampul Buku</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-image"></i></span>
                        <input type="file" class="form-control <?= isset($semuaError['sampul']) ? 'is-invalid' : ''; ?>"
                               id="sampul" name="sampul" accept="image/jpeg,image/png,image/jpg"
                               aria-describedby="sampulHelp">
                        <?php if (isset($semuaError['sampul'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['sampul']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="sampulHelp" class="form-text">
                        Format: JPG / JPEG / PNG. Maksimal 10 MB. Biarkan kosong jika sampul tidak diganti.
                    </div>

                    <div class="mt-3">
                        <p class="form-label mb-2 small">Sampul Saat Ini:</p>
                        <div class="d-flex align-items-center gap-3">
                            <img src="/img/<?= esc($buku['sampul']); ?>" alt="Sampul saat ini"
                                 class="buku-cover-sm rounded-3 border shadow-sm"
                                 style="max-width: 100px; height: auto;"
                                 onerror="this.onerror=null;this.src='<?= $placeholderSampul; ?>'">
                            <div class="text-muted small">
                                <i class="bi bi-file-earmark-image me-1"></i>
                                File: <code><?= esc($buku['sampul']); ?></code>
                            </div>
                        </div>
                    </div>

                    <!-- Preview gambar baru -->
                    <div id="previewWrapper" class="d-none mt-3">
                        <p class="form-label mb-2 small">Pratinjau Sampul Baru:</p>
                        <img id="previewSampul" src="#" alt="Pratinjau sampul baru"
                             class="buku-cover-sm rounded-3 border shadow-sm" style="max-width: 160px; height: auto;">
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="d-flex gap-2 mt-5">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                    <a href="/buku" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const fileInput = document.getElementById('sampul');
        const previewWrapper = document.getElementById('previewWrapper');
        const previewImg = document.getElementById('previewSampul');

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                previewWrapper.classList.add('d-none');
                previewImg.removeAttribute('src');
                return;
            }
            if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                previewWrapper.classList.add('d-none');
                previewImg.removeAttribute('src');
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewWrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    })();
</script>

<?= $this->endSection(); ?>