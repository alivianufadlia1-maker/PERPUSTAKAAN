<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\BukuModel;
use App\Models\PeminjamanModel;

/**
 * Halaman pusat "Laporan" — pintu masuk ke fitur cetak PDF yang sudah ada.
 * Murni index yang me-link ke route-route export, tidak menduplikasi fungsinya.
 */
class Laporan extends BaseController
{
    public function index()
    {
        $peminjamanModel = new PeminjamanModel();

        $data = [
            'title'               => 'Laporan',
            // Ringkasan untuk preview tiap kartu (reuse method model yang sudah ada)
            'totalTransaksiLunas' => $peminjamanModel->where('status_denda', 'lunas')->countAllResults(),
            'totalDendaTerbayar'  => $peminjamanModel->totalDendaTerbayar(),
            'totalAnggota'        => (new AnggotaModel())->countAllResults(),
            'totalBuku'           => (new BukuModel())->countAllResults(),
        ];

        return view('laporan/index', $data);
    }
}
