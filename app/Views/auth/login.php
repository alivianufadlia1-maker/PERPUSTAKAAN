<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Toko Buku</title>
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
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                <div class="text-center mb-4">
                    <div class="auth-logo">📚</div>
                    <h1 class="h4 fw-bold text-white mb-1">Toko Buku</h1>
                    <p class="text-white-50 small mb-0">Oursawithd — Sistem Perpustakaan</p>
                </div>

                <div class="card auth-card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h5 fw-bold mb-1">Selamat Datang 👋</h2>
                        <p class="text-muted small mb-4">Masuk untuk melanjutkan ke dashboard Anda.</p>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success d-flex align-items-center py-2" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <div><?= esc(session()->getFlashdata('success')); ?></div>
                            </div>
                        <?php endif; ?>

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

                        <form action="/login" method="post" novalidate>
                            <?= csrf_field(); ?>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username / Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control <?= isset($semuaError['username']) ? 'is-invalid' : ''; ?>"
                                           id="username" name="username" value="<?= esc(old('username')); ?>"
                                           placeholder="Masukkan username atau email" autofocus>
                                    <?php if (isset($semuaError['username'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['username']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control <?= isset($semuaError['password']) ? 'is-invalid' : ''; ?>"
                                           id="password" name="password" placeholder="Masukkan password">
                                    <?php if (isset($semuaError['password'])): ?>
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i><?= esc($semuaError['password']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-white-50 small mt-4 mb-0">
                    Belum punya akun?
                    <a href="/register" class="text-white fw-semibold">Daftar sebagai Anggota</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>