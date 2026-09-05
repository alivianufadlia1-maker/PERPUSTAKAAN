<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
$semuaError = array_merge(
    (array) (session('_ci_validation_errors') ?? []),
    (array) $validation->getErrors()
);
?>

<div class="container">
    <div class="page-header d-flex align-items-center gap-3">
        <a href="/anggota" class="btn btn-soft" aria-label="Kembali ke daftar anggota">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Ubah Data Anggota</h1>
            <p class="text-muted mb-0">Perbarui data anggota di bawah ini</p>
        </div>
    </div>

    <?php if (empty($anggota)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="display-4 mb-3">😕</div>
                <h3 class="h5 fw-bold mb-2">Anggota Tidak Ditemukan</h3>
                <p class="text-muted mb-4">Data anggota yang ingin diubah tidak tersedia atau telah dihapus.</p>
                <a href="/anggota" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Anggota
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form action="/anggota/update/<?= esc($anggota['id_anggota']); ?>" method="post" enctype="multipart/form-data" novalidate>
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="PUT">

                    <div class="row g-4">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <label for="inputNama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control <?= isset($semuaError['nama']) ? 'is-invalid' : ''; ?>"
                                       id="inputNama" name="nama" value="<?= esc(old('nama', $anggota['nama'])); ?>" autofocus>
                                <?php if (isset($semuaError['nama'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['nama']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="inputEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control <?= isset($semuaError['email']) ? 'is-invalid' : ''; ?>"
                                       id="inputEmail" name="email" value="<?= esc(old('email', $anggota['email'])); ?>">
                                <?php if (isset($semuaError['email'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['email']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- No. Telepon -->
                        <div class="col-md-6">
                            <label for="inputNoTelp" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control <?= isset($semuaError['no_telp']) ? 'is-invalid' : ''; ?>"
                                       id="inputNoTelp" name="no_telp" value="<?= esc(old('no_telp', $anggota['no_telp'])); ?>">
                                <?php if (isset($semuaError['no_telp'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['no_telp']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tanggal Daftar -->
                        <div class="col-md-6">
                            <label for="inputTanggal" class="form-label">Tanggal Daftar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" class="form-control <?= isset($semuaError['tanggal_daftar']) ? 'is-invalid' : ''; ?>"
                                       id="inputTanggal" name="tanggal_daftar"
                                       value="<?= esc(old('tanggal_daftar', $anggota['tanggal_daftar'])); ?>">
                                <?php if (isset($semuaError['tanggal_daftar'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['tanggal_daftar']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="col-12">
                            <label for="inputAlamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <textarea class="form-control <?= isset($semuaError['alamat']) ? 'is-invalid' : ''; ?>"
                                          id="inputAlamat" name="alamat" rows="3"><?= esc(old('alamat', $anggota['alamat'])); ?></textarea>
                                <?php if (isset($semuaError['alamat'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['alamat']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="col-md-6">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control <?= isset($semuaError['foto']) ? 'is-invalid' : ''; ?>"
                                       id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                                <?php if (isset($semuaError['foto'])): ?>
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['foto']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
                            <input type="hidden" name="fotoLama" value="<?= esc($anggota['foto']); ?>">

                            <?php if (! empty($anggota['foto'])): ?>
                                <div class="mt-3">
                                    <p class="form-label mb-2 small">Foto Saat Ini:</p>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="/img/<?= esc($anggota['foto']); ?>" alt="Foto <?= esc($anggota['nama']); ?>"
                                             class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover;"
                                             onerror="this.onerror=null;this.style.display='none';">
                                        <span class="small text-muted">
                                            File: <code><?= esc($anggota['foto']); ?></code>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Preview foto baru -->
                            <div id="previewWrapper" class="d-none mt-3">
                                <p class="form-label mb-2 small">Pratinjau Foto Baru:</p>
                                <img id="previewFoto" src="#" alt="Pratinjau foto"
                                     class="rounded-circle border shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol aksi -->
                    <div class="d-flex gap-2 mt-5">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                        <a href="/anggota" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if (! empty($anggota)): ?>
<script>
    (function () {
        const fileInput = document.getElementById('foto');
        const previewWrapper = document.getElementById('previewWrapper');
        const previewImg = document.getElementById('previewFoto');

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file || !file.type.match(/^image\/(jpeg|jpg|png)$/)) {
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
<?php endif; ?>

<?= $this->endSection(); ?>