<?php

namespace App\Controllers;

use App\Models\BukuModel;

class Buku extends BaseController
{
    protected $BukuModel;

    public function __construct()
    {
        $this->BukuModel = new BukuModel();
    }

    public function index()
    {
        $current=$this->request->getVar('page_buku')?? 1;
        $cari = $this->request->getVar('cari');

        if ($cari){
            $buku = $this->BukuModel->findBuku($cari);
            $pager = null;
        }else {
                $buku = $this->BukuModel->paginate(2, 'buku');
            }
        
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $this->BukuModel->paginate(2, 'buku'),
            'pager' => $this->BukuModel->pager,
            'current' => $current
        ];

        return view('buku/index', $data);
    }

    public function detail($idbuku)
    {
        $data = [
            'title' => 'Detail Buku',
            'buku' => $this->BukuModel->getBuku($idbuku)
        ];

        return view('buku/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'title' => 'Form Tambah Data Buku',
            'validation' => \Config\Services::validation()
        ];

        return view('buku/tambah', $data);
    }

    public function simpan()
    {
        //validasi
        if (!$this->validate(
            [
                'judul' => [
                    'rules' => 'required',
                    'errors' => ['required' => '{field} harus diisi']
                ],
                
                'sampul' => [
                'rules' => 'uploaded[sampul]|max_size[sampul,10000]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Gambar Wajib Dipilih.',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'File wajib gambar',
                    'mime_in' => 'Tipe File Gambar Tidak Sesuai'
                ]
            ]]
        )) {
           
            // kirim kembali ke form + input lama + pesan error
            return redirect()->to('/buku/tambah')->withInput();
        }

        $filesampul = $this->request->getFile('sampul');
        $filesampul->move('img');
        $nmsampul = $filesampul->getName();

        // simpan
        $this->BukuModel->save([
            'judul'        => $this->request->getVar('judul'),
            'pengarang'    => $this->request->getVar('pengarang'),
            'penerbit'     => $this->request->getVar('penerbit'),
            'tahun_terbit' => $this->request->getVar('tahun'),
            'sampul'       => $nmsampul
        ]);


        session()->setFlashdata('pesan','Data Berhasil Ditambahkan');
        return redirect()->to('/buku');
    }

    public function hapus($idbuku)
    {
        $this->BukuModel->delete($idbuku);

        session()->setFlashdata('pesan', 'Data Berhasil Dihapus.');
        return redirect()->to('/buku');
    }

    public function ubah($idbuku)
    {
        $data = [
            'title'      => 'Form Ubah Data Buku',
            'validation' => \Config\Services::validation(),
            'buku'       => $this->BukuModel->getBuku($idbuku)
        ];

        return view('buku/ubah', $data);
    }


  public function update($idbuku)
    {
        if (!$this->validate([
            'judul' => [
                'rules'  => 'required',
                'errors' => ['required' => '{field} harus diisi']
            ],
            'sampul' => [
                'rules'  => 'max_size[sampul,10000]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'File wajib gambar',
                    'mime_in'  => 'Tipe File Gambar Tidak Sesuai'
                ]
            ]
        ])) {
            // return redirect()->to('/buku/ubah/' . $idbuku)->withInput();
            // return alert('OK');
        }

        // cek gambar
        $filesampul = $this->request->getFile('sampul');

        if ($filesampul->getError() == 4) {
            // tidak upload baru -> pakai sampul lama
            $nmsampul = $this->request->getVar('sampulLama');
        } else {
            // upload baru
            $nmsampul = $filesampul->getName();
            $filesampul->move('img', $nmsampul);
        }

        // dd($idbuku);

        // simpan perubahan
        $this->BukuModel->save([
            'id_buku'      => $idbuku,
            'judul'        => $this->request->getVar('judul'),
            'pengarang'    => $this->request->getVar('pengarang'),
            'penerbit'     => $this->request->getVar('penerbit'),
            'tahun_terbit' => $this->request->getVar('tahun_terbit'),
            'sampul'       => $nmsampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil Diubah.');
        return redirect()->to('/buku');
    }
}
?>