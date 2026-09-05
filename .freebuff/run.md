# Run doc — Toko Buku (CodeIgniter 4)

Proyek PHP CodeIgniter 4.6 (sistem perpustakaan) yang berjalan di XAMPP (Windows).
Tidak ada dependency Node; yang dibutuhkan hanyalah PHP CLI + MySQL lokal.

## Reproduce artifacts

1. **PHP & XAMPP**: butuh PHP 8.x CLI (`C:\xampp\php\php.exe`) dan MySQL/MariaDB dari XAMPP.
2. **Dependency Composer**: jika folder `vendor/` belum ada, jalankan dari root proyek:
   ```bash
   composer install
   ```
3. **Database**: buat database `db_pustaka` di MySQL lokal (kredensial default `root` tanpa password — lihat `app/Config/Database.php`; tidak ada file `.env` yang perlu disalin).
4. **Migrasi & seeder** (sekali saja):
   ```bash
   php spark migrate
   php spark db:seed AdminSeeder
   ```
   Hasil seeder: akun admin `admin` / `admin123`. Folder `writable/` dan `.freebuff/` sudah ter-commit/ada; pastikan `writable/` bisa ditulis PHP.

## Run server

Jalankan detached via PowerShell (resep Windows; stdout & stderr ke file terpisah):

```powershell
powershell -NoProfile -Command "$p = Start-Process -FilePath 'C:\xampp\php\php.exe' -ArgumentList 'spark','serve','--port','8080','--host','127.0.0.1' -WorkingDirectory 'C:\xampp\htdocs\WEB - PERPUSTAKAAN' -RedirectStandardOutput 'C:\xampp\htdocs\WEB - PERPUSTAKAAN\.freebuff\preview-a9551154-e319-41cd-b8f1-cf08c61236ad.log' -RedirectStandardError 'C:\xampp\htdocs\WEB - PERPUSTAKAAN\.freebuff\preview-a9551154-e319-41cd-b8f1-cf08c61236ad.log.err' -WindowStyle Hidden -PassThru; Write-Output $p.Id"
```

Catatan penting:
- **Harus `--host 127.0.0.1`**: tanpa itu, `php spark serve` di mesin ini hanya bind ke IPv6 `::1` dan request ke `localhost`/`127.0.0.1` dari curl bisa gagal koneksi (000).
- Port default 8080; jika sibuk, ganti `--port` dan sesuaikan.
- Verifikasi: `curl http://127.0.0.1:8080/buku` harus `200`.
- Halaman publik: `/`, `/buku`, `/buku/{id}`, `/login`, `/register`. Akses admin: `/dashboard`, `/anggota` (login `admin`/`admin123`).