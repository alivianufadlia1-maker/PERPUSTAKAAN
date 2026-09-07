<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? esc($title) . ' - Toko Buku' : 'Toko Buku - Oursawithd'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #764ba2;
            --primary-dark: #5f3b8c;
            --primary-darker: #4a2d6e;
            --primary-light: #f3eefb;
            --accent: #667eea;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --body-bg: #f7f5fb;
            --card-border: #ece8f3;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }

        /* ===== Tombol ===== */
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
            color: #fff;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.25);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(135deg, #5a70e0 0%, #5f3b8c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(118, 75, 162, 0.35);
            color: #fff;
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-soft {
            background: var(--primary-light);
            color: var(--primary);
            border: none;
        }

        .btn-soft:hover {
            background: #e6dcf5;
            color: var(--primary-darker);
        }

        .btn-warning,
        .btn-danger {
            border: none;
        }

        .btn-warning:hover,
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        /* ===== Navbar ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 2px 20px rgba(74, 45, 110, 0.08);
            padding: 14px 0;
            border-bottom: 1px solid rgba(118, 75, 162, 0.08);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-brand small {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            -webkit-text-fill-color: var(--text-muted);
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
            margin: 0 8px;
            padding: 8px 10px !important;
            border-radius: 0.5rem;
            position: relative;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        .nav-link i {
            margin-right: 6px;
            font-size: 0.9em;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background: var(--gradient);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: var(--primary) !important;
        }

        .nav-link:hover::after {
            width: 60%;
        }

        .nav-link.active {
            color: var(--primary) !important;
            font-weight: 600;
            background: var(--primary-light);
        }

        .nav-link.active::after {
            width: 60%;
        }

        .nav-link.disabled {
            color: #adb5bd !important;
            opacity: 0.7;
        }

        @media (max-width: 991.98px) {
            .nav-link {
                margin: 2px 0;
            }
        }

        /* ===== Dropdown navbar ===== */
        .navbar .dropdown-menu {
            border-radius: 0.9rem;
            border: 1px solid var(--card-border);
            box-shadow: 0 14px 34px rgba(74, 45, 110, 0.16);
            padding: 0.5rem;
            margin-top: 10px;
        }

        .navbar .dropdown-item {
            border-radius: 0.6rem;
            font-weight: 500;
            color: #444;
            padding: 0.55rem 0.9rem;
            display: flex;
            align-items: center;
        }

        .navbar .dropdown-item i {
            margin-right: 10px;
            font-size: 0.95em;
            width: 1.1em;
            text-align: center;
            color: var(--primary);
        }

        .navbar .dropdown-item:hover,
        .navbar .dropdown-item:focus {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .navbar .dropdown-item.active {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 700;
        }

        .navbar .dropdown-divider {
            margin: 0.35rem 0.25rem;
        }

        /* ===== Trigger akun (avatar + dropdown) ===== */
        .account-trigger {
            display: inline-flex !important;
            align-items: center;
            gap: 0.5rem;
            margin: 0 !important;
            padding: 5px 10px 5px 6px !important;
            border: 1px solid var(--card-border);
            border-radius: 50rem;
            background: #fff;
            transition: border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease;
        }

        /* Sembunyikan caret bawaan Bootstrap, pakai ikon chevron sendiri */
        .account-trigger::after,
        .account-trigger.active::after {
            display: none !important;
        }

        .account-trigger:hover,
        .account-trigger:focus,
        .account-trigger.active {
            background: var(--primary-light) !important;
            border-color: rgba(118, 75, 162, 0.35);
            color: var(--primary-dark) !important;
        }

        .account-trigger .nama-user {
            font-weight: 600;
            font-size: 0.875rem;
            color: #444;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .account-trigger:hover .nama-user,
        .account-trigger:focus .nama-user,
        .account-trigger.active .nama-user {
            color: var(--primary-dark);
        }

        /* Avatar bulat berisi inisial user */
        .avatar-inisial {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--gradient);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(118, 75, 162, 0.3);
        }

        .avatar-inisial-lg {
            width: 44px;
            height: 44px;
            font-size: 1.05rem;
        }

        /* Item Logout di dropdown akun berwarna merah */
        .navbar .dropdown-item.text-danger {
            color: #dc3545 !important;
        }

        .navbar .dropdown-item.text-danger:hover,
        .navbar .dropdown-item.text-danger:focus {
            background: #fdeaea;
            color: #b02a37 !important;
        }

        /* ===== Kartu ===== */
        .card {
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(74, 45, 110, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(74, 45, 110, 0.14);
            border-color: rgba(118, 75, 162, 0.35);
        }

        /* Sampul buku dengan rasio tetap supaya grid rapi */
        .buku-cover {
            aspect-ratio: 3 / 4;
            width: 100%;
            object-fit: cover;
            background: var(--primary-light);
            display: block;
            transition: transform 0.35s ease;
        }

        /* Zoom halus pada sampul saat kartu di-hover */
        .card-hover:hover .buku-cover {
            transform: scale(1.05);
        }

        .buku-cover-sm {
            aspect-ratio: 3 / 4;
            object-fit: cover;
            background: var(--primary-light);
        }

        /* ===== Badge tahun ===== */
        .badge-tahun {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border-radius: 50rem;
            padding: 0.4em 0.85em;
        }

        /* ===== Judul kartu: clamp 2 baris ===== */
        .card-title-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.6em;
        }

        /* ===== Alert ===== */
        .alert {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        /* ===== Form ===== */
        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.18);
        }

        .input-group-text {
            background: var(--primary-light);
            color: var(--primary);
            border-color: #d9d0e6;
        }

        /* ===== Pagination ===== */
        .pagination .page-link {
            color: var(--primary);
            border-radius: 0.5rem;
            margin: 0 3px;
            border: 1px solid var(--card-border);
            font-weight: 500;
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient);
            border-color: transparent;
            color: #fff;
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.3);
        }

        .pagination .page-link:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* ===== Footer ===== */
        .footer {
            background: #fff;
            border-top: 1px solid var(--card-border);
            color: var(--text-muted);
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* ===== Heading halaman ===== */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h1,
        .page-header h2 {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #2b2136;
        }

        .page-header .text-muted {
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <?php
    // Deteksi menu aktif berdasarkan URI yang sedang dibuka
    $uriPath = implode('/', service('uri')->getSegments());
    $isHome      = ($uriPath === '' || $uriPath === 'pages');
    $isBuku      = ($uriPath === 'buku' || strpos($uriPath, 'buku/') === 0);
    $isAnggota   = ($uriPath === 'anggota' || strpos($uriPath, 'anggota/') === 0);
    $isDashboard = ($uriPath === 'dashboard');
    $isProfil    = ($uriPath === 'profil');
    $isRiwayatAnggota = ($uriPath === 'peminjaman/riwayat-anggota' || strpos($uriPath, 'peminjaman/riwayat-anggota/') === 0);
    $isStatistikBuku = ($uriPath === 'peminjaman/statistik-buku' || strpos($uriPath, 'peminjaman/statistik-buku/') === 0);
    $isDendaSaya = ($uriPath === 'peminjaman/denda-saya');
    $isLogPembayaran = ($uriPath === 'peminjaman/log-pembayaran');
    $isLaporan = ($uriPath === 'laporan');
    // Kelompok "Laporan & Statistik" (dropdown admin): salah satu halaman di dalamnya sedang dibuka
    $isLaporanStat = ($isLaporan || $isRiwayatAnggota || $isStatistikBuku || $isLogPembayaran);
    $isPeminjaman = (($uriPath === 'peminjaman' || strpos($uriPath, 'peminjaman/') === 0) && ! $isRiwayatAnggota && ! $isStatistikBuku && ! $isDendaSaya && ! $isLogPembayaran);

    // Status login & role
    $sessLogin    = session();
    $isLoggedIn   = (bool) $sessLogin->get('is_logged_in');
    $roleLogin    = $sessLogin->get('role');
    $usernameLogin= $sessLogin->get('username');
    // Inisial user untuk avatar di dropdown akun
    $inisialLogin = $isLoggedIn ? strtoupper(substr((string) $usernameLogin, 0, 1)) : '';
    ?>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/pages">
                📚 Toko Buku
                <small>Oursawithd</small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu navigasi utama -->
                <ul class="navbar-nav me-auto align-items-lg-center gap-lg-1">
                    <?php if (! $isLoggedIn): ?>
                        <!-- Tamu -->
                        <li class="nav-item">
                            <a class="nav-link <?= $isHome ? 'active' : ''; ?>" <?= $isHome ? 'aria-current="page"' : ''; ?> href="/pages">
                                <i class="bi bi-house-door"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isBuku ? 'active' : ''; ?>" <?= $isBuku ? 'aria-current="page"' : ''; ?> href="/buku">
                                <i class="bi bi-journal-bookmark"></i>Daftar Buku
                            </a>
                        </li>
                    <?php elseif ($roleLogin === 'admin'): ?>
                        <!-- Admin -->
                        <li class="nav-item">
                            <a class="nav-link <?= $isDashboard ? 'active' : ''; ?>" <?= $isDashboard ? 'aria-current="page"' : ''; ?> href="/dashboard">
                                <i class="bi bi-speedometer2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isBuku ? 'active' : ''; ?>" <?= $isBuku ? 'aria-current="page"' : ''; ?> href="/buku">
                                <i class="bi bi-journal-bookmark"></i>Daftar Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isAnggota ? 'active' : ''; ?>" <?= $isAnggota ? 'aria-current="page"' : ''; ?> href="/anggota">
                                <i class="bi bi-people"></i>Daftar Anggota
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isPeminjaman ? 'active' : ''; ?>" <?= $isPeminjaman ? 'aria-current="page"' : ''; ?> href="/peminjaman">
                                <i class="bi bi-arrow-left-right"></i>Kelola Peminjaman
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $isLaporanStat ? 'active' : ''; ?>" href="#"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false"
                               <?= $isLaporanStat ? 'aria-current="page"' : ''; ?>>
                                <i class="bi bi-printer"></i>Laporan <i class="bi bi-chevron-down small ms-1"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item <?= $isLaporan ? 'active' : ''; ?>" href="/laporan">
                                        <i class="bi bi-clipboard-data"></i>Pusat Laporan
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item <?= $isRiwayatAnggota ? 'active' : ''; ?>" href="/peminjaman/riwayat-anggota">
                                        <i class="bi bi-person-lines-fill"></i>Riwayat Anggota
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= $isStatistikBuku ? 'active' : ''; ?>" href="/peminjaman/statistik-buku">
                                        <i class="bi bi-graph-up"></i>Statistik Buku
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item <?= $isLogPembayaran ? 'active' : ''; ?>" href="/peminjaman/log-pembayaran">
                                        <i class="bi bi-receipt"></i>Log Pembayaran
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Anggota -->
                        <li class="nav-item">
                            <a class="nav-link <?= $isDashboard ? 'active' : ''; ?>" <?= $isDashboard ? 'aria-current="page"' : ''; ?> href="/dashboard">
                                <i class="bi bi-speedometer2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isBuku ? 'active' : ''; ?>" <?= $isBuku ? 'aria-current="page"' : ''; ?> href="/buku">
                                <i class="bi bi-journal-bookmark"></i>Daftar Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isPeminjaman ? 'active' : ''; ?>" <?= $isPeminjaman ? 'aria-current="page"' : ''; ?> href="/peminjaman/riwayat">
                                <i class="bi bi-clock-history"></i>Riwayat Peminjaman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isDendaSaya ? 'active' : ''; ?>" <?= $isDendaSaya ? 'aria-current="page"' : ''; ?> href="/peminjaman/denda-saya">
                                <i class="bi bi-cash-coin"></i>Denda Saya
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Area akun (kanan) -->
                <ul class="navbar-nav align-items-lg-center gap-lg-1 mt-2 mt-lg-0">
                    <?php if (! $isLoggedIn): ?>
                        <!-- Tamu: tombol login/daftar -->
                        <li class="nav-item">
                            <a class="btn btn-soft btn-sm w-100" href="/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm w-100" href="/register">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Dropdown akun: avatar + nama sebagai trigger -->
                        <li class="nav-item dropdown">
                            <a class="nav-link account-trigger dropdown-toggle <?= $isProfil ? 'active' : ''; ?>"
                               href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                               <?= $isProfil ? 'aria-current="page"' : ''; ?>>
                                <span class="avatar-inisial"><?= esc($inisialLogin); ?></span>
                                <span class="nama-user"><?= esc($usernameLogin); ?></span>
                                <i class="bi bi-chevron-down small text-muted"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <div class="dropdown-item-text d-flex align-items-center gap-3 py-2">
                                        <span class="avatar-inisial avatar-inisial-lg"><?= esc($inisialLogin); ?></span>
                                        <div class="lh-sm">
                                            <div class="fw-bold"><?= esc($usernameLogin); ?></div>
                                            <?php if ($roleLogin === 'admin'): ?>
                                                <span class="badge rounded-pill text-bg-primary mt-1">
                                                    <i class="bi bi-shield-fill-check me-1"></i>Admin
                                                </span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill text-bg-light border mt-1">
                                                    <i class="bi bi-person-badge me-1"></i>Anggota
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if ($roleLogin === 'anggota'): ?>
                                    <li>
                                        <a class="dropdown-item <?= $isProfil ? 'active' : ''; ?>" href="/profil">
                                            <i class="bi bi-person-circle"></i>Profil Saya
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item text-danger" href="/logout">
                                        <i class="bi bi-box-arrow-right"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <?= $this->renderSection('content'); ?>
    </main>

    <?= $this->include('layout/footer'); ?>
</body>
</html>