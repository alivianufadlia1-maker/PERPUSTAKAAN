<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'buku'; //protected wajib di codeigniter agar tidak beralih
    protected $primaryKey = 'id_buku';
    protected $allowedFields = ['judul', 'pengarang', 'penerbit', 'tahun_terbit', 'sampul'];

    public function getBuku($idbuku = false) //fungsi untuk mencari id bukua = null engga
    {
        if ($idbuku == false) {
            return $this->findAll();
        }
        return $this->where(['id_buku' => $idbuku])->first();
    }
    public function findBuku($cari){ //fungsi pencarian
        return $this->like('judul', $cari)->findAll(); //jalankan query dan kembalikan array hasil
    }
}
?>