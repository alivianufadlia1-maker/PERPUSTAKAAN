<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Peminjaman extends BaseController
{
    protected $PeminjamanModel;
    protected $BukuModel;

    public function __construct()
    {
        $this->PeminjamanModel = new PeminjamanModel();
        $this->BukuModel       = new BukuModel();
    }

    /**
     * Kelola Peminjaman — khusus admin (route pakai filter adminOnly).
     */
    public function index()
    {
        $cari   = $this->request->getGet('cari');
        $status = $this->request->getGet('status');

        $rows = $this->PeminjamanModel->getAll($cari, $status);

        $data = [
            'title'           => 'Kelola Peminjaman',
            'peminjaman'      => $this->dekorasiStatus($rows),
            'cari'            => $cari,
            'statusFilter'    => $status,
            'jumlahDipinjam'  => $this->PeminjamanModel->countDipinjam(),
            'jumlahTerlambat' => $this->PeminjamanModel->countTerlambat(),
        ];

        return view('peminjaman/index', $data);
    }

    /**
     * Pinjam buku — khusus anggota yang login (route pakai filter auth).
     */
    public function pinjam($idBuku)
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard')->with('error', 'Hanya anggota yang dapat meminjam buku.');
        }

        $buku = $this->BukuModel->find($idBuku);
        if (! $buku) {
            return redirect()->to('/buku')->with('error', 'Buku tidak ditemukan.');
        }

        if ($this->PeminjamanModel->bukuSedangDipinjam($idBuku)) {
            return redirect()->to('/buku/' . $idBuku)->with('error', 'Buku ini sedang dipinjam anggota lain.');
        }

        $this->PeminjamanModel->save([
            'id_buku'               => $idBuku,
            'id_anggota'            => $session->get('id_anggota'),
            'tanggal_pinjam'        => date('Y-m-d'),
            'tanggal_wajib_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status'                => 'dipinjam',
        ]);

        return redirect()->to('/peminjaman/riwayat')
            ->with('pesan', 'Buku berhasil dipinjam. Harap kembalikan sebelum tanggal wajib kembali.');
    }

    /**
     * Tandai dikembalikan — khusus admin (route pakai filter adminOnly).
     * Denda keterlambatan dihitung otomatis dari tanggal kembali vs tanggal wajib kembali.
     */
    public function kembalikan($idPeminjaman)
    {
        $row = $this->PeminjamanModel->find($idPeminjaman);

        if (! $row) {
            return redirect()->to('/peminjaman')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($row['status'] === 'dikembalikan') {
            return redirect()->to('/peminjaman')->with('error', 'Peminjaman ini sudah dikembalikan sebelumnya.');
        }

        $tanggalKembali = date('Y-m-d');
        $denda          = $this->PeminjamanModel->hitungDenda($row['tanggal_wajib_kembali'], $tanggalKembali);

        $this->PeminjamanModel->save([
            'id_peminjaman'   => $idPeminjaman,
            'tanggal_kembali' => $tanggalKembali,
            'status'          => 'dikembalikan',
            'denda'           => $denda,
            'status_denda'    => $denda > 0 ? 'belum_bayar' : null,
        ]);

        $pesan = 'Buku berhasil ditandai dikembalikan.';
        if ($denda > 0) {
            $pesan .= ' Denda keterlambatan: ' . format_rupiah($denda) . '.';
        }

        return redirect()->to('/peminjaman')->with('pesan', $pesan);
    }

    /**
     * Halaman "Denda Saya" — khusus anggota yang login (route pakai filter auth).
     */
    public function dendaSaya()
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard');
        }

        $rows = $this->PeminjamanModel->dendaAnggota($session->get('id_anggota'));

        $data = [
            'title'           => 'Denda Saya',
            'denda'           => $rows,
            'totalBelumBayar' => $this->PeminjamanModel->totalDendaBelumBayarAnggota($session->get('id_anggota')),
        ];

        return view('peminjaman/denda_saya', $data);
    }

    /**
     * Konfirmasi pembayaran denda (simulasi) — khusus anggota yang login, HANYA untuk baris
     * miliknya sendiri (route pakai filter auth).
     */
    public function konfirmasiBayar($idPeminjaman)
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard');
        }

        $row = $this->PeminjamanModel->find($idPeminjaman);

        // Baris harus milik anggota yang login — jangan sampai bisa melunasi denda anggota lain.
        if (! $row || (int) $row['id_anggota'] !== (int) $session->get('id_anggota')) {
            return redirect()->to('/peminjaman/denda-saya')->with('error', 'Data denda tidak ditemukan.');
        }

        if ($row['status_denda'] !== 'belum_bayar') {
            return redirect()->to('/peminjaman/denda-saya')->with('error', 'Denda ini sudah dikonfirmasi lunas sebelumnya.');
        }

        $this->PeminjamanModel->save([
            'id_peminjaman' => $idPeminjaman,
            'status_denda'  => 'lunas',
            'tanggal_bayar' => date('Y-m-d H:i:s'),
            'dibayar_oleh'  => 'mandiri',
        ]);

        return redirect()->to('/peminjaman/denda-saya')
            ->with('pesan', 'Terima kasih, denda telah dikonfirmasi lunas (simulasi pembayaran).');
    }

    /**
     * Log pembayaran denda — khusus admin (route pakai filter adminOnly).
     */
    public function logPembayaran()
    {
        $cari       = $this->request->getGet('cari');
        $dibayarOleh = $this->request->getGet('dibayar_oleh');

        $rows = $this->PeminjamanModel->logPembayaran($cari, $dibayarOleh);

        $data = [
            'title'           => 'Log Pembayaran Denda',
            'pembayaran'      => $rows,
            'cari'            => $cari,
            'filterJalur'     => $dibayarOleh,
            'totalTerbayar'   => $this->PeminjamanModel->totalDendaTerbayar(),
            'jumlahTransaksi' => $this->PeminjamanModel->where('status_denda', 'lunas')->countAllResults(),
        ];

        return view('peminjaman/log_pembayaran', $data);
    }

    /**
     * Export log pembayaran ke CSV — khusus admin (route pakai filter adminOnly).
     * Filter cari/dibayar_oleh dari query string ikut diterapkan, sama seperti halaman log.
     */
    public function exportCsv()
    {
        $cari       = $this->request->getGet('cari');
        $dibayarOleh = $this->request->getGet('dibayar_oleh');

        $rows = $this->PeminjamanModel->logPembayaran($cari, $dibayarOleh);

        $filename = 'log-pembayaran-' . date('Y-m-d') . '.csv';

        $handle = fopen('php://temp', 'w');
        // BOM UTF-8 supaya karakter Indonesia terbaca benar di Excel
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['Nama Anggota', 'Judul Buku', 'Nominal Denda', 'Tanggal Bayar', 'Jalur Bayar']);

        foreach ($rows as $r) {
            fputcsv($handle, [
                $r['nama'],
                $r['judul'],
                (int) $r['denda'],
                empty($r['tanggal_bayar']) ? '-' : date('d M Y H:i', strtotime($r['tanggal_bayar'])),
                match ($r['dibayar_oleh'] ?? null) {
                    'admin'   => 'Admin',
                    'mandiri' => 'Mandiri (Anggota)',
                    default   => '-',
                },
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Length', (string) strlen($csv))
            ->setBody($csv);
    }

    /**
     * Export log pembayaran ke PDF (dompdf) — khusus admin (route pakai filter adminOnly).
     */
    public function exportPdf()
    {
        $cari       = $this->request->getGet('cari');
        $dibayarOleh = $this->request->getGet('dibayar_oleh');

        $rows = $this->PeminjamanModel->logPembayaran($cari, $dibayarOleh);

        $html = view('peminjaman/pdf_pembayaran', [
            'pembayaran'     => $rows,
            'totalTerbayar'  => array_sum(array_map(fn ($r) => (int) $r['denda'], $rows)),
            'jumlahTransaksi'=> count($rows),
            'tanggalCetak'   => date('d M Y H:i'),
            'cari'           => $cari,
            'filterJalur'    => $dibayarOleh,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'log-pembayaran-' . date('Y-m-d') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    /**
     * Tandai denda lunas — khusus admin (route pakai filter adminOnly).
     */
    public function bayarDenda($idPeminjaman)
    {
        $row = $this->PeminjamanModel->find($idPeminjaman);

        if (! $row) {
            return redirect()->to('/peminjaman')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($row['status_denda'] !== 'belum_bayar') {
            return redirect()->to('/peminjaman')->with('error', 'Tidak ada denda yang perlu dibayar untuk peminjaman ini.');
        }

        $this->PeminjamanModel->save([
            'id_peminjaman' => $idPeminjaman,
            'status_denda'  => 'lunas',
            'tanggal_bayar' => date('Y-m-d H:i:s'),
            'dibayar_oleh'  => 'admin',
        ]);

        return redirect()->to('/peminjaman')->with('pesan', 'Denda berhasil ditandai lunas.');
    }

    /**
     * Riwayat Peminjaman Saya — khusus anggota yang login (route pakai filter auth).
     */
    public function riwayatSaya()
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard');
        }

        $rows = $this->PeminjamanModel->getByAnggota($session->get('id_anggota'));

        $data = [
            'title'      => 'Riwayat Peminjaman Saya',
            'peminjaman' => $this->dekorasiStatus($rows),
        ];

        return view('peminjaman/riwayat', $data);
    }

    /**
     * Riwayat peminjaman per anggota — khusus admin (route pakai filter adminOnly).
     */
    public function riwayatAnggota()
    {
        $statistik = $this->PeminjamanModel->statistikPerAnggota();

        $data = [
            'title'                => 'Riwayat Peminjaman Anggota',
            'statistik'            => $statistik,
            'jumlahAnggotaPeminjam' => count(array_filter($statistik, fn ($r) => (int) $r['total_pinjam'] > 0)),
            'jumlahDipinjam'       => $this->PeminjamanModel->countDipinjam(),
            'jumlahTerlambat'      => $this->PeminjamanModel->countTerlambat(),
        ];

        return view('peminjaman/riwayat_anggota', $data);
    }

    /**
     * Statistik peminjaman per buku — khusus admin (route pakai filter adminOnly).
     */
    public function statistikBuku()
    {
        $cari = $this->request->getGet('cari');
        $sort = $this->request->getGet('sort');

        $rows = $this->PeminjamanModel->statistikPerBuku($cari);

        // Urutkan: default paling sering dipinjam; alternatif judul A-Z
        if ($sort === 'judul') {
            usort($rows, fn ($a, $b) => strcasecmp($a['judul'], $b['judul']));
        } else {
            usort($rows, fn ($a, $b) => (int) $b['total_dipinjam'] <=> (int) $a['total_dipinjam']
                ?: strcasecmp($a['judul'], $b['judul']));
        }

        $data = [
            'title'                 => 'Statistik Peminjaman Buku',
            'statistik'             => $rows,
            'cari'                  => $cari,
            'sort'                  => $sort,
            'bukuTerpopuler'        => $rows[0] ?? null,
            'jumlahBelumDipinjam'   => count(array_filter($rows, fn ($r) => (int) $r['total_dipinjam'] === 0)),
            'jumlahTerlambat'       => array_sum(array_map(fn ($r) => (int) $r['jumlah_terlambat'], $rows)),
        ];

        return view('peminjaman/statistik_buku', $data);
    }

    /**
     * Cetak rekap statistik peminjaman SEMUA buku ke PDF (dompdf) — khusus admin.
     * Selalu memuat semua buku (tanpa filter pencarian halaman).
     */
    public function cetakStatistikBuku()
    {
        $rows = $this->PeminjamanModel->statistikPerBuku();

        // Urutkan: paling sering dipinjam dulu (sama seperti urutan default halaman statistik)
        usort($rows, fn ($a, $b) => (int) $b['total_dipinjam'] <=> (int) $a['total_dipinjam']
            ?: strcasecmp($a['judul'], $b['judul']));

        $html = view('peminjaman/pdf_statistik_buku', [
            'statistik'           => $rows,
            'totalBuku'           => count($rows),
            'bukuTerpopuler'      => $rows[0] ?? null,
            'jumlahBelumDipinjam' => count(array_filter($rows, fn ($r) => (int) $r['total_dipinjam'] === 0)),
            'jumlahTerlambat'     => array_sum(array_map(fn ($r) => (int) $r['jumlah_terlambat'], $rows)),
            'tanggalCetak'        => date('d M Y H:i'),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'rekap-statistik-buku-' . date('Y-m-d') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    /**
     * Detail statistik satu buku — khusus admin (route pakai filter adminOnly).
     */
    public function statistikBukuDetail($idBuku)
    {
        $buku = (new \App\Models\BukuModel())->find($idBuku);

        if (! $buku) {
            return redirect()->to('/peminjaman/statistik-buku')->with('error', 'Buku tidak ditemukan.');
        }

        $rows = $this->PeminjamanModel->detailStatistikBuku($idBuku);

        $data = [
            'title'        => 'Statistik — ' . $buku['judul'],
            'buku'         => $buku,
            'peminjaman'   => $this->dekorasiStatus($rows),
            'totalDipinjam'=> count($rows),
        ];

        return view('peminjaman/statistik_buku_detail', $data);
    }

    /**
     * Detail riwayat peminjaman satu anggota — khusus admin (route pakai filter adminOnly).
     */
    public function riwayatAnggotaDetail($idAnggota)
    {
        $anggota = (new \App\Models\AnggotaModel())->find($idAnggota);

        if (! $anggota) {
            return redirect()->to('/peminjaman/riwayat-anggota')->with('error', 'Anggota tidak ditemukan.');
        }

        $rows = $this->PeminjamanModel->riwayatAnggota($idAnggota);

        $data = [
            'title'      => 'Riwayat Peminjaman — ' . $anggota['nama'],
            'anggota'    => $anggota,
            'peminjaman' => $this->dekorasiStatus($rows),
        ];

        return view('peminjaman/riwayat_anggota_detail', $data);
    }

    /**
     * Cetak rekap ringkasan peminjaman SEMUA anggota ke PDF (dompdf) — khusus admin.
     * Selalu memuat semua anggota (tanpa filter pencarian halaman list).
     */
    public function cetakRiwayatSemuaAnggota()
    {
        $statistik = $this->PeminjamanModel->statistikPerAnggota();

        // PDF disusun urut nama A-Z
        usort($statistik, fn ($a, $b) => strcasecmp($a['nama'], $b['nama']));

        $html = view('peminjaman/pdf_rekap_anggota', [
            'statistik'    => $statistik,
            'totalAnggota' => count($statistik),
            'totalPinjam'  => array_sum(array_map(fn ($r) => (int) $r['total_pinjam'], $statistik)),
            'totalAktif'   => array_sum(array_map(fn ($r) => (int) $r['aktif'], $statistik)),
            'totalTerlambat' => array_sum(array_map(fn ($r) => (int) $r['terlambat'], $statistik)),
            'tanggalCetak' => date('d M Y H:i'),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'rekap-anggota-' . date('Y-m-d') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    /**
     * Cetak riwayat peminjaman satu anggota ke PDF (dompdf) — khusus admin.
     */
    public function cetakRiwayatAnggota($idAnggota)
    {
        $anggota = (new \App\Models\AnggotaModel())->find($idAnggota);

        if (! $anggota) {
            return redirect()->to('/peminjaman/riwayat-anggota')->with('error', 'Anggota tidak ditemukan.');
        }

        $rows = $this->PeminjamanModel->riwayatAnggota($idAnggota);
        $rows = $this->dekorasiStatus($rows);

        $html = view('peminjaman/pdf_riwayat_anggota', [
            'anggota'         => $anggota,
            'peminjaman'      => $rows,
            'totalPinjam'     => count($rows),
            'sedangDipinjam'  => count(array_filter($rows, fn ($r) => $r['status_tampil'] === 'dipinjam')),
            'terlambat'       => count(array_filter($rows, fn ($r) => $r['status_tampil'] === 'terlambat')),
            'totalDenda'      => $this->PeminjamanModel->totalDendaBelumBayarAnggota($idAnggota),
            'tanggalCetak'    => date('d M Y H:i'),
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $slug = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $anggota['nama'])), '-');
        $filename = 'riwayat-' . ($slug ?: 'anggota-' . $idAnggota) . '-' . date('Y-m-d') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    /**
     * Tambahkan key 'status_tampil' pada tiap baris (dihitung saat render).
     */
    private function dekorasiStatus(array $rows): array
    {
        return array_map(function (array $row) {
            return $row + ['status_tampil' => $this->PeminjamanModel->statusAktual($row)];
        }, $rows);
    }
}