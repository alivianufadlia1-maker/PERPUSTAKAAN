<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahRoleUsersDanKolomAnggota extends Migration
{
    public function up()
    {
        // ============================================================
        // users (tabel sudah ada dari myth/auth) — tambah role & id_anggota
        // ============================================================
        $this->forge->addColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => "'admin','anggota'",
                'null'       => false,
                'default'    => 'anggota',
                'after'      => 'password_hash',
            ],
            'id_anggota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
                'after'      => 'role',
            ],
        ]);

        // Foreign key: users.id_anggota -> anggota.id_anggota (nullable, hanya untuk role anggota)
        $this->db->query('ALTER TABLE users ADD CONSTRAINT fk_users_anggota FOREIGN KEY (id_anggota) REFERENCES anggota (id_anggota) ON DELETE SET NULL ON UPDATE CASCADE');

        // ============================================================
        // anggota (tabel sudah ada) — lengkapi kolom sesuai spesifikasi
        // ============================================================
        $this->forge->addColumn('anggota', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'nama',
            ],
            'no_telp' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'email',
            ],
            'tanggal_daftar' => [
                'type'       => 'DATE',
                'null'       => true,
                'after'      => 'alamat',
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'tanggal_daftar',
            ],
        ]);

        // Backfill email unik untuk data anggota lama, lalu jadikan NOT NULL + UNIQUE
        $this->db->query("UPDATE anggota SET email = CONCAT('anggota', id_anggota, '@perpustakaan.local') WHERE email IS NULL OR email = ''");
        $this->db->query('ALTER TABLE anggota MODIFY email VARCHAR(100) NOT NULL');
        $this->db->query('ALTER TABLE anggota ADD UNIQUE KEY uq_anggota_email (email)');

        // Salin telp -> no_telp (format string), lalu hapus kolom telp lama
        $this->db->query('UPDATE anggota SET no_telp = telp WHERE no_telp IS NULL');
        $this->forge->dropColumn('anggota', 'telp');

        // id_anggota menjadi AUTO_INCREMENT supaya penambahan anggota baru otomatis
        $this->db->query('ALTER TABLE anggota MODIFY id_anggota INT(11) NOT NULL AUTO_INCREMENT');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE users DROP FOREIGN KEY fk_users_anggota');
        $this->forge->dropColumn('users', ['id_anggota', 'role']);

        $this->db->query('ALTER TABLE anggota DROP INDEX uq_anggota_email');
        $this->forge->addColumn('anggota', [
            'telp' => [
                'type'       => 'INT',
                'constraint' => 20,
                'null'       => true,
            ],
        ]);
        $this->db->query('UPDATE anggota SET telp = no_telp');
        $this->forge->dropColumn('anggota', ['foto', 'tanggal_daftar', 'no_telp', 'email']);
        $this->db->query('ALTER TABLE anggota MODIFY id_anggota INT(11) NOT NULL');
    }
}