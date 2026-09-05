<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table      = 'anggota';
    protected $primaryKey = 'id_anggota';

    protected $allowedFields = [
        'nama',
        'email',
        'no_telp',
        'alamat',
        'tanggal_daftar',
        'foto',
    ];

    protected $returnType = 'array';

    public function cari(string $kataKunci)
    {
        return $this->like('nama', $kataKunci)
            ->orLike('email', $kataKunci)
            ->orLike('no_telp', $kataKunci)
            ->findAll();
    }
}