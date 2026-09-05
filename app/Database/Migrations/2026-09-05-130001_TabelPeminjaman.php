<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peminjaman' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_buku' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_anggota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'tanggal_pinjam' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tanggal_wajib_kembali' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tanggal_kembali' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => "'dipinjam','dikembalikan','terlambat'",
                'null'       => false,
                'default'    => 'dipinjam',
            ],
        ]);

        $this->forge->addPrimaryKey('id_peminjaman');
        $this->forge->addKey('id_buku');
        $this->forge->addKey('id_anggota');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('id_buku', 'buku', 'id_buku', 'CASCADE', 'CASCADE', 'fk_peminjaman_buku');
        $this->forge->addForeignKey('id_anggota', 'anggota', 'id_anggota', 'CASCADE', 'CASCADE', 'fk_peminjaman_anggota');

        $this->forge->createTable('peminjaman', true);
    }

    public function down()
    {
        $this->forge->dropTable('peminjaman', true);
    }
}