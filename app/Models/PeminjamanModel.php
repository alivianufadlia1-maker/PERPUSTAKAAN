<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    /** Tarif denda keterlambatan per hari (dalam rupiah). */
    public const TARIF_DENDA_PER_HARI = 1000;

    protected $table      = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $allowedFields = [
        'id_buku',
        'id_anggota',
        'tanggal_pinjam',
        'tanggal_wajib_kembali',
        'tanggal_kembali',
        'status',
        'denda',
        'status_denda',
        'tanggal_bayar',
        'dibayar_oleh',
    ];

    protected $returnType = 'array';

    /**
     * Cek apakah buku sedang dipinjam (status aktif: dipinjam / terlambat).
     */
    public function bukuSedangDipinjam($idBuku): bool
    {
        return $this->where('id_buku', $idBuku)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->countAllResults() > 0;
    }

    /**
     * Semua peminjaman dengan info buku & anggota (untuk admin).
     * Bisa difilter berdasarkan kata kunci (nama anggota / judul buku) dan status.
     */
    public function getAll(?string $cari = null, ?string $status = null)
    {
        $this->select('peminjaman.*, buku.judul, buku.sampul, anggota.nama')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota')
            ->orderBy('peminjaman.id_peminjaman', 'DESC');

        if ($cari !== null && $cari !== '') {
            $this->groupStart()
                ->like('anggota.nama', $cari)
                ->orLike('buku.judul', $cari)
                ->groupEnd();
        }

        if ($status === 'terlambat') {
            // Terlambat dihitung saat render: status masih dipinjam tapi sudah lewat tanggal wajib kembali
            $this->where('peminjaman.status', 'dipinjam')
                ->where('peminjaman.tanggal_wajib_kembali <', date('Y-m-d'));
        } elseif ($status !== null && $status !== '') {
            $this->where('peminjaman.status', $status);
        }

        return $this->findAll();
    }

    /**
     * Riwayat peminjaman milik satu anggota (untuk halaman "Riwayat Peminjaman Saya").
     */
    public function getByAnggota($idAnggota)
    {
        return $this->select('peminjaman.*, buku.judul, buku.sampul')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_anggota', $idAnggota)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();
    }

    /**
     * Peminjaman aktif milik anggota (untuk dashboard anggota).
     */
    public function getAktifByAnggota($idAnggota)
    {
        return $this->select('peminjaman.*, buku.judul, buku.sampul')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_anggota', $idAnggota)
            ->whereIn('peminjaman.status', ['dipinjam', 'terlambat'])
            ->orderBy('peminjaman.tanggal_wajib_kembali', 'ASC')
            ->findAll();
    }

    /**
     * Status yang benar-benar tampil: 'terlambat' dihitung saat render
     * (status DB masih 'dipinjam' tapi tanggal wajib kembali sudah lewat).
     */
    public function statusAktual(array $row): string
    {
        if ($row['status'] === 'dikembalikan') {
            return 'dikembalikan';
        }

        if ($row['status'] === 'dipinjam' && strtotime($row['tanggal_wajib_kembali']) < strtotime(date('Y-m-d'))) {
            return 'terlambat';
        }

        return $row['status'];
    }

    public function countDipinjam(): int
    {
        return $this->whereIn('status', ['dipinjam', 'terlambat'])->countAllResults();
    }

    public function countTerlambat(): int
    {
        return $this->where('status', 'dipinjam')
            ->where('tanggal_wajib_kembali <', date('Y-m-d'))
            ->countAllResults();
    }

    /**
     * Statistik peminjaman per anggota (untuk halaman admin "Riwayat Anggota").
     * Anggota yang belum pernah meminjam tetap muncul (right join) dengan nilai 0.
     */
    public function statistikPerAnggota()
    {
        $today = date('Y-m-d');

        $rows = $this->select("anggota.id_anggota, anggota.nama, anggota.email, anggota.no_telp,
                COUNT(peminjaman.id_peminjaman) AS total_pinjam,
                SUM(CASE WHEN peminjaman.status = 'dipinjam' THEN 1 ELSE 0 END) AS aktif,
                SUM(CASE WHEN peminjaman.status = 'dipinjam' AND peminjaman.tanggal_wajib_kembali < '{$today}' THEN 1 ELSE 0 END) AS terlambat,
                MAX(peminjaman.tanggal_pinjam) AS terakhir_pinjam")
            ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota', 'right')
            ->groupBy('anggota.id_anggota, anggota.nama, anggota.email, anggota.no_telp')
            ->findAll();

        // Urutkan: yang paling banyak meminjam di paling atas
        usort($rows, fn ($a, $b) => (int) $b['total_pinjam'] <=> (int) $a['total_pinjam']);

        return $rows;
    }

    /**
     * Riwayat lengkap peminjaman satu anggota (untuk halaman detail admin).
     */
    public function riwayatAnggota($idAnggota)
    {
        return $this->select('peminjaman.*, buku.judul, buku.sampul, buku.id_buku')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_anggota', $idAnggota)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();
    }

    /**
     * Hitung denda keterlambatan: selisih hari antara tanggal kembali aktual dan tanggal wajib
     * kembali, dikalikan TARIF_DENDA_PER_HARI. Kalau kembali tepat waktu / lebih cepat = 0.
     */
    public function hitungDenda(string $tanggalWajibKembali, ?string $tanggalKembaliAktual): int
    {
        if (empty($tanggalKembaliAktual)) {
            return 0;
        }

        $wajib  = new \DateTime($tanggalWajibKembali);
        $kembali = new \DateTime($tanggalKembaliAktual);

        if ($kembali <= $wajib) {
            return 0;
        }

        $selisihHari = $wajib->diff($kembali)->days;

        return $selisihHari * self::TARIF_DENDA_PER_HARI;
    }

    /**
     * Total denda yang belum lunas di seluruh sistem (untuk dashboard admin).
     */
    public function totalDendaBelumBayar(): int
    {
        $row = $this->selectSum('denda', 'total')
            ->where('status_denda', 'belum_bayar')
            ->first();

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Total denda yang sudah lunas di seluruh sistem (untuk kartu ringkas log pembayaran).
     */
    public function totalDendaTerbayar(): int
    {
        $row = $this->selectSum('denda', 'total')
            ->where('status_denda', 'lunas')
            ->first();

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Log pembayaran denda: semua baris yang sudah lunas, join anggota & buku,
     * urut tanggal bayar terbaru dulu. Bisa difilter cari (nama anggota/judul buku)
     * dan/atau jalur pembayaran (admin / mandiri). Baris legacy yang lunas sebelum fitur
     * ini ada (tanggal_bayar/dibayar_oleh NULL) tetap ikut tampil.
     */
    public function logPembayaran(?string $cari = null, ?string $dibayarOleh = null)
    {
        $this->select('peminjaman.*, anggota.id_anggota, anggota.nama, buku.judul, buku.sampul')
            ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.status_denda', 'lunas')
            ->orderBy('peminjaman.tanggal_bayar', 'DESC');

        if ($cari !== null && $cari !== '') {
            $this->groupStart()
                ->like('anggota.nama', $cari)
                ->orLike('buku.judul', $cari)
                ->groupEnd();
        }

        if ($dibayarOleh === 'admin' || $dibayarOleh === 'mandiri') {
            $this->where('peminjaman.dibayar_oleh', $dibayarOleh);
        }

        return $this->findAll();
    }

    /**
     * Total denda belum lunas milik satu anggota (untuk banner peringatan).
     */
    public function totalDendaBelumBayarAnggota($idAnggota): int
    {
        $row = $this->selectSum('denda', 'total')
            ->where('status_denda', 'belum_bayar')
            ->where('id_anggota', $idAnggota)
            ->first();

        return (int) ($row['total'] ?? 0);
    }

    /**
     * Statistik peminjaman per buku (untuk halaman admin "Statistik Buku").
     * Buku yang belum pernah dipinjam tetap muncul (right join dari buku) dengan nilai 0.
     * jumlah_terlambat mencakup yang sedang terlambat + yang pernah terlambat saat dikembalikan.
     */
    public function statistikPerBuku(?string $cari = null)
    {
        $today = date('Y-m-d');

        $this->select("buku.id_buku, buku.judul, buku.sampul, buku.pengarang,
                COUNT(peminjaman.id_peminjaman) AS total_dipinjam,
                SUM(CASE WHEN peminjaman.status IN ('dipinjam','terlambat') THEN 1 ELSE 0 END) AS sedang_dipinjam,
                SUM(CASE WHEN (peminjaman.status IN ('dipinjam','terlambat') AND peminjaman.tanggal_wajib_kembali < '{$today}')
                         OR (peminjaman.status = 'dikembalikan' AND peminjaman.tanggal_kembali > peminjaman.tanggal_wajib_kembali)
                         THEN 1 ELSE 0 END) AS jumlah_terlambat,
                MAX(peminjaman.tanggal_pinjam) AS terakhir_dipinjam")
            ->join('buku', 'buku.id_buku = peminjaman.id_buku', 'right')
            ->groupBy('buku.id_buku, buku.judul, buku.sampul, buku.pengarang');

        if ($cari !== null && $cari !== '') {
            $this->like('buku.judul', $cari);
        }

        return $this->findAll();
    }

    /**
     * Riwayat lengkap peminjaman satu buku (untuk halaman detail statistik admin).
     */
    public function detailStatistikBuku($idBuku)
    {
        return $this->select('peminjaman.*, anggota.id_anggota, anggota.nama')
            ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota')
            ->where('peminjaman.id_buku', $idBuku)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();
    }

    /**
     * Ringkasan peminjaman satu buku untuk halaman detail: total peminjaman sepanjang masa
     * + maksimal 3 riwayat terakhir (nama anggota, tanggal pinjam/kembali, status).
     */
    public function ringkasanPeminjamanBuku($idBuku)
    {
        $total = $this->where('id_buku', $idBuku)->countAllResults();

        $riwayat = $this->select('peminjaman.*, anggota.id_anggota, anggota.nama')
            ->join('anggota', 'anggota.id_anggota = peminjaman.id_anggota')
            ->where('peminjaman.id_buku', $idBuku)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->limit(3)
            ->findAll();

        return [
            'total'   => $total,
            'riwayat' => $riwayat,
        ];
    }

    /**
     * Estimasi ketersediaan buku: kalau ada peminjaman aktif (status dipinjam/terlambat),
     * kembalikan tanggal wajib kembali peminjaman tersebut; kalau tidak, null (buku tersedia).
     */
    public function estimasiTersedia($idBuku): ?string
    {
        $row = $this->select('tanggal_wajib_kembali')
            ->where('id_buku', $idBuku)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->orderBy('tanggal_pinjam', 'DESC')
            ->first();

        return $row['tanggal_wajib_kembali'] ?? null;
    }

    /**
     * Semua peminjaman milik satu anggota yang punya denda > 0 (belum bayar maupun lunas),
     * lengkap dengan judul buku — untuk halaman "Denda Saya" anggota.
     */
    public function dendaAnggota($idAnggota)
    {
        return $this->select('peminjaman.*, buku.judul, buku.sampul')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.id_anggota', $idAnggota)
            ->where('peminjaman.denda >', 0)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->findAll();
    }
}