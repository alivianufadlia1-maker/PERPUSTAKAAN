<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<?php
$semuaError = array_merge(
    (array) (session('_ci_validation_errors') ?? []),
    (array) $validation->getErrors()
);

$placeholderFoto = 'data:image/svg+xml;charset=utf-8,' . rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' .
    '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' .
    '<stop offset="0" stop-color="#667eea"/><stop offset="1" stop-color="#764ba2"/>' .
    '</linearGradient></defs>' .
    '<rect width="300" height="300" fill="url(#g)"/>' .
    '<text x="150" y="185" font-size="110" text-anchor="middle" fill="#ffffff" opacity="0.85">👤</text>' .
    '</svg>'
);
?>

<div class="container">
    <div class="page-header">
        <h1 class="h3 mb-0"><i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya</h1>
        <p class="text-muted mb-0">Lihat dan perbarui informasi akun Anda</p>
    </div>

    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= esc(session()->getFlashdata('pesan')); ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Ringkasan profil -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body p-4">
                    <img src="/img/<?= esc($anggota['foto']); ?>" alt="Foto <?= esc($anggota['nama']); ?>"
                         class="rounded-circle border shadow-sm mb-3"
                         style="width: 120px; height: 120px; object-fit: cover;"
                         onerror="this.onerror=null;this.src='<?= $placeholderFoto; ?>'">
                    <h2 class="h5 fw-bold mb-1"><?= esc($anggota['nama']); ?></h2>
                    <p class="text-muted small mb-3"><?= esc($user['email']); ?></p>
                    <span class="badge-tahun">
                        <i class="bi bi-person-badge me-1"></i><?= esc(ucfirst($user['role'])); ?>
                    </span>
                    <hr>
                    <div class="text-start small">
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted"><i class="bi bi-hash me-1"></i>ID Anggota</span>
                            <span class="fw-semibold"><?= esc($anggota['id_anggota']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted"><i class="bi bi-person me-1"></i>Username</span>
                            <span class="fw-semibold"><?= esc($user['username']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Terdaftar</span>
                            <span class="fw-semibold"><?= empty($anggota['tanggal_daftar']) ? '-' : esc($anggota['tanggal_daftar']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form edit profil -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h3 class="h5 fw-bold mb-4"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profil</h3>

                    <form action="/profil/update" method="post" enctype="multipart/form-data" novalidate>
                        <?= csrf_field(); ?>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="inputNama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control <?= isset($semuaError['nama']) ? 'is-invalid' : ''; ?>"
                                           id="inputNama" name="nama" value="<?= esc(old('nama', $anggota['nama'])); ?>">
                                    <?php if (isset($semuaError['nama'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['nama']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

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
                            </div>

                            <div class="col-12">
                                <label for="inputAlamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <textarea class="form-control <?= isset($semuaError['alamat']) ? 'is-invalid' : ''; ?>"
                                              id="inputAlamat" name="alamat" rows="2"><?= esc(old('alamat', $anggota['alamat'])); ?></textarea>
                                    <?php if (isset($semuaError['alamat'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['alamat']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="tanggal_daftar" value="<?= esc($anggota['tanggal_daftar']); ?>">

                        <hr class="my-4">
                        <h4 class="h6 fw-bold mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Ganti Password (Opsional)</h4>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="inputPassword" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control <?= isset($semuaError['password']) ? 'is-invalid' : ''; ?>"
                                           id="inputPassword" name="password" placeholder="Minimal 6 karakter">
                                    <?php if (isset($semuaError['password'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['password']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="inputPasswordConfirm" class="form-label">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control <?= isset($semuaError['password_confirm']) ? 'is-invalid' : ''; ?>"
                                           id="inputPasswordConfirm" name="password_confirm" placeholder="Ulangi password baru">
                                    <?php if (isset($semuaError['password_confirm'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['password_confirm']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                            <a href="/dashboard" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>