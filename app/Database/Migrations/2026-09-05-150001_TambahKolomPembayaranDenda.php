<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TambahKolomPembayaranDenda extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'tanggal_bayar' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'default'    => null,
                'after'      => 'status_denda',
            ],
            'dibayar_oleh' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'mandiri'],
                'null'       => true,
                'default'    => null,
                'after'      => 'tanggal_bayar',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', ['tanggal_bayar', 'dibayar_oleh']);
    }
}