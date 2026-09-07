<?php

namespace App\Libraries;

use App\Models\UserModel;

/**
 * Menjaga keabsahan sesi login.
 *
 * Sesi disimpan sebagai file di writable/session sehingga BISA selamat dari
 * restart server. Akibatnya, cookie lama (misal dari sesi testing sebelumnya,
 * atau dari akun yang sudah dihapus dari database) bisa membuat pengunjung
 * dianggap masih login — inilah penyebab "nama muncul di navbar sebelum login".
 *
 * Aturannya sederhana: sesi yang mengaku "logged in" hanya sah kalau
 *  1) data inti (user_id & role) lengkap, dan
 *  2) user-nya masih benar-benar ada di database.
 * Selain itu, sesi langsung dihancurkan → pengguna kembali jadi guest.
 */
class SessionGuard
{
    public static function validate(): void
    {
        $session = session();

        if (! $session->get('is_logged_in')) {
            return; // tamu biasa, tidak ada yang perlu divalidasi
        }

        $userId = $session->get('user_id');
        $role   = $session->get('role');

        $sah = ! empty($userId)
            && ! empty($role)
            && (new UserModel())->find($userId) !== null;

        if (! $sah) {
            $session->destroy();
        }
    }
}
