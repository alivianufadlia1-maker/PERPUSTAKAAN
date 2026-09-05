<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $bukuModel = new \App\Models\BukuModel();

        $data = [
            'title'     => 'Katalog Buku',
            'buku'      => $bukuModel->orderBy('id_buku', 'DESC')->findAll(8),
            'totalBuku' => $bukuModel->countAll(),
        ];

        return view('layout/home', $data);
    }
}