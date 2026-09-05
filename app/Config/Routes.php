<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===== Halaman publik =====
$routes->get('/', 'Pages::index');
$routes->get('/pages', 'Pages::index');

// ===== AUTH =====
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::prosesLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::prosesRegister');
$routes->get('/logout', 'Auth::logout');

// ===== DASHBOARD (harus login) =====
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// ===== BUKU =====
// Melihat daftar & detail buku: publik (guest, anggota, admin)
$routes->get('/buku', 'Buku::index');

// Aksi CRUD buku: khusus admin yang login
$routes->get('/buku/tambah', 'Buku::tambah', ['filter' => 'adminOnly']);
$routes->put('/buku/update/(:num)', 'Buku::update/$1', ['filter' => 'adminOnly']);
$routes->get('/buku/ubah/(:num)', 'Buku::ubah/$1', ['filter' => 'adminOnly']);
$routes->post('/buku/simpan', 'Buku::simpan', ['filter' => 'adminOnly']);
$routes->delete('/buku/(:num)', 'Buku::hapus/$1', ['filter' => 'adminOnly']);

$routes->get('/buku/(:any)', 'Buku::detail/$1');

// ===== ANGGOTA (CRUD khusus admin) =====
$routes->get('/anggota', 'Anggota::index', ['filter' => 'adminOnly']);
$routes->get('/anggota/tambah', 'Anggota::tambah', ['filter' => 'adminOnly']);
$routes->post('/anggota/simpan', 'Anggota::simpan', ['filter' => 'adminOnly']);
$routes->get('/anggota/ubah/(:num)', 'Anggota::ubah/$1', ['filter' => 'adminOnly']);
$routes->put('/anggota/update/(:num)', 'Anggota::update/$1', ['filter' => 'adminOnly']);
$routes->get('/anggota/(:num)', 'Anggota::detail/$1', ['filter' => 'adminOnly']);
$routes->delete('/anggota/(:num)', 'Anggota::hapus/$1', ['filter' => 'adminOnly']);

// ===== LAPORAN (pusat link cetak PDF, khusus admin) =====
$routes->get('/laporan', 'Laporan::index', ['filter' => 'adminOnly']);

// ===== PROFIL ANGGOTA (harus login) =====
$routes->get('/profil', 'Profil::index', ['filter' => 'auth']);
$routes->post('/profil/update', 'Profil::update', ['filter' => 'auth']);

// ===== PEMINJAMAN =====
// Kelola peminjaman: khusus admin
$routes->get('/peminjaman', 'Peminjaman::index', ['filter' => 'adminOnly']);
$routes->post('/peminjaman/kembalikan/(:num)', 'Peminjaman::kembalikan/$1', ['filter' => 'adminOnly']);
$routes->post('/peminjaman/bayar-denda/(:num)', 'Peminjaman::bayarDenda/$1', ['filter' => 'adminOnly']);
// Pinjam buku & riwayat: khusus anggota yang login
$routes->post('/peminjaman/pinjam/(:num)', 'Peminjaman::pinjam/$1', ['filter' => 'auth']);
$routes->get('/peminjaman/riwayat', 'Peminjaman::riwayatSaya', ['filter' => 'auth']);
// Denda anggota: khusus anggota yang login
$routes->get('/peminjaman/denda-saya', 'Peminjaman::dendaSaya', ['filter' => 'auth']);
$routes->post('/peminjaman/konfirmasi-bayar/(:num)', 'Peminjaman::konfirmasiBayar/$1', ['filter' => 'auth']);
// Riwayat peminjaman per anggota: khusus admin
$routes->get('/peminjaman/riwayat-anggota', 'Peminjaman::riwayatAnggota', ['filter' => 'adminOnly']);
// Didaftarkan sebelum route (:num) supaya "cetak-semua" tidak ke-match sebagai id anggota
$routes->get('/peminjaman/riwayat-anggota/cetak-semua', 'Peminjaman::cetakRiwayatSemuaAnggota', ['filter' => 'adminOnly']);
$routes->get('/peminjaman/riwayat-anggota/(:num)', 'Peminjaman::riwayatAnggotaDetail/$1', ['filter' => 'adminOnly']);
$routes->get('/peminjaman/riwayat-anggota/(:num)/cetak', 'Peminjaman::cetakRiwayatAnggota/$1', ['filter' => 'adminOnly']);
// Statistik peminjaman per buku: khusus admin
$routes->get('/peminjaman/statistik-buku', 'Peminjaman::statistikBuku', ['filter' => 'adminOnly']);
// Didaftarkan sebelum route (:num) supaya "cetak" tidak ke-match sebagai id buku
$routes->get('/peminjaman/statistik-buku/cetak', 'Peminjaman::cetakStatistikBuku', ['filter' => 'adminOnly']);
$routes->get('/peminjaman/statistik-buku/(:num)', 'Peminjaman::statistikBukuDetail/$1', ['filter' => 'adminOnly']);
// Log pembayaran denda: khusus admin
$routes->get('/peminjaman/log-pembayaran', 'Peminjaman::logPembayaran', ['filter' => 'adminOnly']);
$routes->get('/peminjaman/log-pembayaran/export-csv', 'Peminjaman::exportCsv', ['filter' => 'adminOnly']);
$routes->get('/peminjaman/log-pembayaran/export-pdf', 'Peminjaman::exportPdf', ['filter' => 'adminOnly']);