<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahKolomDendaPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'denda' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
                'after'      => 'status',
            ],
            'status_denda' => [
                'type'       => 'ENUM',
                'constraint' => ['belum_bayar', 'lunas'],
                'null'       => true,
                'default'    => null,
                'after'      => 'denda',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', ['denda', 'status_denda']);
    }
}