<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class Auth extends BaseController
{
    protected $UserModel;
    protected $AnggotaModel;

    public function __construct()
    {
        $this->UserModel    = new UserModel();
        $this->AnggotaModel = new AnggotaModel();
    }

    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title'      => 'Login',
            'validation' => \Config\Services::validation(),
        ];

        return view('auth/login', $data);
    }

    public function prosesLogin()
    {
        if (! $this->validate([
            'username' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Username atau email wajib diisi'],
            ],
            'password' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Password wajib diisi'],
            ],
        ])) {
            return redirect()->back()->withInput();
        }

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $this->UserModel->cariByUsernameOrEmail((string) $username);

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->to('/login')->withInput()->with('error', 'Username atau password salah.');
        }

        session()->set([
            'is_logged_in' => true,
            'user_id'      => $user['id'],
            'username'     => $user['username'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'id_anggota'   => $user['id_anggota'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title'      => 'Daftar Anggota',
            'validation' => \Config\Services::validation(),
        ];

        return view('auth/register', $data);
    }

    public function prosesRegister()
    {
        if (! $this->validate([
            'nama' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Nama lengkap wajib diisi'],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[anggota.email]|is_unique[users.email]',
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
            'username' => [
                'rules'  => 'required|is_unique[users.username]|min_length[3]',
                'errors' => [
                    'required'   => 'Username wajib diisi',
                    'is_unique'  => 'Username sudah dipakai',
                    'min_length' => 'Username minimal 3 karakter',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password wajib diisi',
                    'min_length' => 'Password minimal 6 karakter',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi',
                    'matches'  => 'Konfirmasi password tidak sama dengan password',
                ],
            ],
        ])) {
            return redirect()->back()->withInput();
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->AnggotaModel->save([
            'nama'            => $this->request->getVar('nama'),
            'email'           => $this->request->getVar('email'),
            'no_telp'         => $this->request->getVar('no_telp'),
            'alamat'          => $this->request->getVar('alamat'),
            'tanggal_daftar'  => date('Y-m-d'),
        ]);

        $idAnggota = $this->AnggotaModel->getInsertID();

        $this->UserModel->save([
            'email'           => $this->request->getVar('email'),
            'username'        => $this->request->getVar('username'),
            'password_hash'   => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'active'          => 1,
            'force_pass_reset'=> 0,
            'role'            => 'anggota',
            'id_anggota'      => $idAnggota,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal mendaftar. Silakan coba lagi.');
        }

        return redirect()->to('/login')->with('success', 'Pendaftaran berhasil! Silakan login dengan akun baru Anda.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}