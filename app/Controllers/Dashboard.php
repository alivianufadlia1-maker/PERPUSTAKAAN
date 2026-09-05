<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\AnggotaModel;
use App\Models\PeminjamanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session         = session();
        $PeminjamanModel = new PeminjamanModel();

        $data = [
            'title'      => 'Dashboard',
            'role'       => $session->get('role'),
            'username'   => $session->get('username'),
            'jumlahBuku' => (new BukuModel())->countAll(),
        ];

        if ($session->get('role') === 'admin') {
            $data['jumlahAnggota']        = (new AnggotaModel())->countAll();
            $data['jumlahDipinjam']       = $PeminjamanModel->countDipinjam();
            $data['jumlahTerlambat']      = $PeminjamanModel->countTerlambat();
            $data['totalDendaBelumBayar'] = $PeminjamanModel->totalDendaBelumBayar();

            // Buku terpopuler: total peminjaman tertinggi (null kalau belum ada data)
            $statistikBuku = $PeminjamanModel->statistikPerBuku();
            usort($statistikBuku, fn ($a, $b) => (int) $b['total_dipinjam'] <=> (int) $a['total_dipinjam']);
            $data['bukuTerpopuler'] = (! empty($statistikBuku) && (int) $statistikBuku[0]['total_dipinjam'] > 0)
                ? $statistikBuku[0]
                : null;
        } else {
            $pinjamanAktif = $PeminjamanModel->getAktifByAnggota($session->get('id_anggota'));
            $data['pinjamanAktif'] = array_map(function (array $row) use ($PeminjamanModel) {
                return $row + ['status_tampil' => $PeminjamanModel->statusAktual($row)];
            }, $pinjamanAktif);
            $data['totalDendaSaya'] = $PeminjamanModel->totalDendaBelumBayarAnggota($session->get('id_anggota'));
        }

        return view('dashboard', $data);
    }
}