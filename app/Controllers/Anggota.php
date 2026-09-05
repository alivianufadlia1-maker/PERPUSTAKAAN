<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\UserModel;

class Anggota extends BaseController
{
    protected $AnggotaModel;
    protected $UserModel;

    public function __construct()
    {
        $this->AnggotaModel = new AnggotaModel();
        $this->UserModel    = new UserModel();
    }

    public function index()
    {
        $current = $this->request->getVar('page_anggota') ?? 1;
        $cari    = $this->request->getVar('cari');

        if ($cari) {
            $anggota = $this->AnggotaModel->cari($cari);
            $pager   = null;
        } else {
            $anggota = $this->AnggotaModel->paginate(8, 'anggota');
            $pager   = $this->AnggotaModel->pager;
        }

        $data = [
            'title'      => 'Daftar Anggota',
            'anggota'    => $anggota,
            'pager'      => $pager,
            'current'    => $current,
            'validation' => \Config\Services::validation(),
        ];

        return view('anggota/index', $data);
    }

    public function tambah()
    {
        $data = [
            'title'      => 'Tambah Anggota',
            'validation' => \Config\Services::validation(),
        ];

        return view('anggota/tambah', $data);
    }

    public function simpan()
    {
        if (! $this->validate($this->aturanValidasi())) {
            return redirect()->back()->withInput();
        }

        $foto = $this->prosesUploadFoto();

        $this->AnggotaModel->save([
            'nama'           => $this->request->getVar('nama'),
            'email'          => $this->request->getVar('email'),
            'no_telp'        => $this->request->getVar('no_telp'),
            'alamat'         => $this->request->getVar('alamat'),
            'tanggal_daftar' => $this->request->getVar('tanggal_daftar'),
            'foto'           => $foto,
        ]);

        session()->setFlashdata('pesan', 'Data Anggota Berhasil Ditambahkan.');
        return redirect()->to('/anggota');
    }

    public function ubah($id)
    {
        $data = [
            'title'      => 'Ubah Data Anggota',
            'validation' => \Config\Services::validation(),
            'anggota'    => $this->AnggotaModel->find($id),
        ];

        return view('anggota/ubah', $data);
    }

    public function update($id)
    {
        if (! $this->validate($this->aturanValidasi($id))) {
            return redirect()->back()->withInput();
        }

        $fotoLama = $this->request->getVar('fotoLama');
        $foto     = $this->prosesUploadFoto($fotoLama);

        $this->AnggotaModel->save([
            'id_anggota'     => $id,
            'nama'           => $this->request->getVar('nama'),
            'email'          => $this->request->getVar('email'),
            'no_telp'        => $this->request->getVar('no_telp'),
            'alamat'         => $this->request->getVar('alamat'),
            'tanggal_daftar' => $this->request->getVar('tanggal_daftar'),
            'foto'           => $foto,
        ]);

        session()->setFlashdata('pesan', 'Data Anggota Berhasil Diubah.');
        return redirect()->to('/anggota');
    }

    public function detail($id)
    {
        $data = [
            'title'   => 'Detail Anggota',
            'anggota' => $this->AnggotaModel->find($id),
        ];

        return view('anggota/detail', $data);
    }

    public function hapus($id)
    {
        // Hapus juga akun login anggota terkait (kalau ada)
        $this->UserModel->where('id_anggota', $id)->delete();
        $this->AnggotaModel->delete($id);

        session()->setFlashdata('pesan', 'Data Anggota (beserta akun loginnya, jika ada) Berhasil Dihapus.');
        return redirect()->to('/anggota');
    }

    private function aturanValidasi(?int $id = null): array
    {
        $ruleEmail = $id === null
            ? 'required|valid_email|is_unique[anggota.email]'
            : "required|valid_email|is_unique[anggota.email,id_anggota,{$id}]";

        $rules = [
            'nama' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama lengkap wajib diisi'],
            ],
            'email' => [
                'rules'  => $ruleEmail,
                'errors' => [
                    'required'   => 'Email wajib diisi',
                    'valid_email'=> 'Format email tidak valid',
                    'is_unique'  => 'Email sudah terdaftar',
                ],
            ],
            'no_telp' => [
                'rules'  => 'required',
                'errors' => ['required' => 'No. Telepon wajib diisi'],
            ],
            'alamat' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Alamat wajib diisi'],
            ],
            'tanggal_daftar' => [
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => 'Tanggal daftar wajib diisi',
                    'valid_date' => 'Format tanggal tidak valid',
                ],
            ],
        ];

        // Rule foto hanya berlaku kalau benar-benar ada file yang diunggah (foto opsional)
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto !== null && $fileFoto->getError() !== UPLOAD_ERR_NO_FILE) {
            $rules['foto'] = [
                'rules'  => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran foto maksimal 2 MB',
                    'is_image' => 'File wajib berupa gambar',
                    'mime_in'  => 'Tipe file gambar tidak sesuai (JPG/JPEG/PNG)',
                ],
            ];
        }

        return $rules;
    }

    private function prosesUploadFoto(?string $fotoLama = ''): string
    {
        $file = $this->request->getFile('foto');

        // Tidak ada file baru -> pertahankan foto lama
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $fotoLama ?? '';
        }

        $namaFoto = $file->getRandomName();
        $file->move('img', $namaFoto);

        return $namaFoto;
    }
}