<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class Profil extends BaseController
{
    protected $UserModel;
    protected $AnggotaModel;

    public function __construct()
    {
        $this->UserModel    = new UserModel();
        $this->AnggotaModel = new AnggotaModel();
    }

    public function index()
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard');
        }

        $user    = $this->UserModel->find($session->get('user_id'));
        $anggota = $this->AnggotaModel->find($user['id_anggota']);

        $data = [
            'title'      => 'Profil Saya',
            'user'       => $user,
            'anggota'    => $anggota,
            'validation' => \Config\Services::validation(),
        ];

        return view('profil/index', $data);
    }

    public function update()
    {
        $session = session();

        if ($session->get('role') !== 'anggota') {
            return redirect()->to('/dashboard');
        }

        $userId    = $session->get('user_id');
        $user      = $this->UserModel->find($userId);
        $idAnggota = $user['id_anggota'];

        if (! $this->validate([
            'nama' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama lengkap wajib diisi'],
            ],
            'email' => [
                'rules'  => "required|valid_email|is_unique[anggota.email,id_anggota,{$idAnggota}]|is_unique[users.email,id,{$userId}]",
                'errors' => [
                    'required'   => 'Email wajib diisi',
                    'valid_email'=> 'Format email tidak valid',
                    'is_unique'  => 'Email sudah digunakan',
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
            'password' => [
                'rules'  => 'permit_empty|min_length[6]',
                'errors' => ['min_length' => 'Password baru minimal 6 karakter'],
            ],
            'password_confirm' => [
                'rules'  => 'required_with[password]|matches[password]',
                'errors' => [
                    'required_with' => 'Konfirmasi password baru wajib diisi',
                    'matches'       => 'Konfirmasi password tidak sama dengan password baru',
                ],
            ],
        ])) {
            return redirect()->back()->withInput();
        }

        // Foto (opsional, pertahankan lama jika tidak diganti)
        $fotoLama = $this->request->getVar('fotoLama');
        $foto     = $fotoLama;
        $file     = $this->request->getFile('foto');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $foto = $file->getRandomName();
            $file->move('img', $foto);
        }

        $this->AnggotaModel->save([
            'id_anggota'     => $idAnggota,
            'nama'           => $this->request->getVar('nama'),
            'email'          => $this->request->getVar('email'),
            'no_telp'        => $this->request->getVar('no_telp'),
            'alamat'         => $this->request->getVar('alamat'),
            'tanggal_daftar' => $this->request->getVar('tanggal_daftar'),
            'foto'           => $foto,
        ]);

        $updateUser = [
            'id'    => $userId,
            'email' => $this->request->getVar('email'),
        ];

        $passwordBaru = $this->request->getVar('password');
        if (! empty($passwordBaru)) {
            $updateUser['password_hash'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $this->UserModel->save($updateUser);

        session()->set('email', $this->request->getVar('email'));
        session()->setFlashdata('pesan', 'Profil berhasil diperbarui.');

        return redirect()->to('/profil');
    }
}