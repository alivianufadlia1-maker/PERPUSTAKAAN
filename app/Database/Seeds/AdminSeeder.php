<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $exists = $builder->where('username', 'admin')->countAllResults();

        if ($exists > 0) {
            echo "Admin sudah ada (username: admin) — seeder dilewati.\n";
            return;
        }

        $builder->insert([
            'email'           => 'admin@perpustakaan.test',
            'username'        => 'admin',
            'password_hash'   => password_hash('admin123', PASSWORD_DEFAULT),
            'active'          => 1,
            'force_pass_reset'=> 0,
            'role'            => 'admin',
            'id_anggota'      => null,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        echo "Admin berhasil dibuat — username: admin / password: admin123\n";
    }
}