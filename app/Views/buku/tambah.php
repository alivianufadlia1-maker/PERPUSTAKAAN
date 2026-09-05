<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
// Gabungkan error validasi dari session (redirect()->withInput()) dan service validation
$semuaError = array_merge(
    (array) (session('_ci_validation_errors') ?? []),
    (array) $validation->getErrors()
);
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/buku" class="btn btn-soft" aria-label="Kembali ke daftar buku">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Buku</h1>
            <p class="text-muted mb-0">Lengkapi data buku baru di bawah ini</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            <form action="/buku/simpan" method="post" enctype="multipart/form-data" novalidate>
                <?= csrf_field(); ?>

                <!-- Judul -->
                <div class="mb-4">
                    <label for="inputJudul" class="form-label">
                        Judul Buku <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                        <input type="text" class="form-control <?= isset($semuaError['judul']) ? 'is-invalid' : ''; ?>"
                               id="inputJudul" name="judul" value="<?= esc(old('judul')); ?>"
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
                               id="inputPengarang" name="pengarang" value="<?= esc(old('pengarang')); ?>"
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
                               id="inputPenerbit" name="penerbit" value="<?= esc(old('penerbit')); ?>"
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
                        <input type="number" class="form-control <?= isset($semuaError['tahun']) ? 'is-invalid' : ''; ?>"
                               id="inputTahun" name="tahun" value="<?= esc(old('tahun')); ?>"
                               min="1000" max="<?= date('Y'); ?>" placeholder="<?= date('Y'); ?>">
                        <?php if (isset($semuaError['tahun'])): ?>
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['tahun']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sampul -->
                <div class="mb-4">
                    <label for="sampul" class="form-label">
                        Sampul Buku <span class="text-danger">*</span>
                    </label>
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
                        Format: JPG / JPEG / PNG. Maksimal 10 MB.
                    </div>

                    <!-- Preview gambar -->
                    <div id="previewWrapper" class="d-none mt-3">
                        <p class="form-label mb-2 small">Pratinjau Sampul:</p>
                        <img id="previewSampul" src="#" alt="Pratinjau sampul"
                             class="buku-cover-sm rounded-3 border shadow-sm" style="max-width: 160px; height: auto;">
                    </div>
                </div>

                <!-- Tombol aksi -->
                <div class="d-flex gap-2 mt-5">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Buku
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
            // Validasi tipe file secara ringan di sisi klien
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