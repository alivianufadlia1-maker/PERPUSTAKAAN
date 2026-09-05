<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Anggota - Toko Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #764ba2;
            --primary-dark: #5f3b8c;
            --primary-light: #f3eefb;
            --accent: #667eea;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient);
            min-height: 100vh;
        }
        .auth-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.1rem;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }
        .auth-card {
            border-radius: 1.25rem;
            animation: fadeUp 0.45s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: none; }
        }
        .btn-primary {
            background: var(--gradient);
            border: none;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.25);
            transition: all 0.2s ease;
        }
        .btn-primary:hover,
        .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 75, 162, 0.4);
            background: var(--gradient);
        }
        .form-label { font-weight: 600; color: #333; }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.18);
        }
        .input-group-text {
            background: var(--primary-light);
            color: var(--primary);
            border-color: #d9d0e6;
        }
        .alert { border-radius: 0.75rem; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-11 col-sm-10 col-md-8 col-lg-6">
                <div class="text-center mb-4">
                    <div class="auth-logo">📚</div>
                    <h1 class="h4 fw-bold text-white mb-1">Toko Buku</h1>
                    <p class="text-white-50 small mb-0">Oursawithd — Sistem Perpustakaan</p>
                </div>

                <div class="card auth-card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h5 fw-bold mb-1">Daftar sebagai Anggota ✍️</h2>
                        <p class="text-muted small mb-4">Buat akun Anda untuk mulai meminjam buku.</p>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger d-flex align-items-center py-2" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= esc(session()->getFlashdata('error')); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php
                        $semuaError = array_merge(
                            (array) (session('_ci_validation_errors') ?? []),
                            (array) $validation->getErrors()
                        );
                        ?>

                        <form action="/register" method="post" novalidate>
                            <?= csrf_field(); ?>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control <?= isset($semuaError['nama']) ? 'is-invalid' : ''; ?>"
                                               id="nama" name="nama" value="<?= esc(old('nama')); ?>"
                                               placeholder="Contoh: Budi Santoso" autofocus>
                                        <?php if (isset($semuaError['nama'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['nama']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control <?= isset($semuaError['email']) ? 'is-invalid' : ''; ?>"
                                               id="email" name="email" value="<?= esc(old('email')); ?>"
                                               placeholder="nama@email.com">
                                        <?php if (isset($semuaError['email'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['email']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="no_telp" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control <?= isset($semuaError['no_telp']) ? 'is-invalid' : ''; ?>"
                                               id="no_telp" name="no_telp" value="<?= esc(old('no_telp')); ?>"
                                               placeholder="08xxxxxxxxxx">
                                        <?php if (isset($semuaError['no_telp'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['no_telp']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <textarea class="form-control <?= isset($semuaError['alamat']) ? 'is-invalid' : ''; ?>"
                                                  id="alamat" name="alamat" rows="2"
                                                  placeholder="Alamat lengkap tempat tinggal"><?= esc(old('alamat')); ?></textarea>
                                        <?php if (isset($semuaError['alamat'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['alamat']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" class="form-control <?= isset($semuaError['username']) ? 'is-invalid' : ''; ?>"
                                               id="username" name="username" value="<?= esc(old('username')); ?>"
                                               placeholder="Minimal 3 karakter">
                                        <?php if (isset($semuaError['username'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['username']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control <?= isset($semuaError['password']) ? 'is-invalid' : ''; ?>"
                                               id="password" name="password" placeholder="Minimal 6 karakter">
                                        <?php if (isset($semuaError['password'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['password']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="password_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control <?= isset($semuaError['password_confirm']) ? 'is-invalid' : ''; ?>"
                                               id="password_confirm" name="password_confirm" placeholder="Ulangi password">
                                        <?php if (isset($semuaError['password_confirm'])): ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['password_confirm']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 mt-4">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-white-50 small mt-4 mb-0">
                    Sudah punya akun?
                    <a href="/login" class="text-white fw-semibold">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>