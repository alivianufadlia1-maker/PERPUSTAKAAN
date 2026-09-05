<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'email',
        'username',
        'password_hash',
        'active',
        'force_pass_reset',
        'status',
        'role',
        'id_anggota',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    public function cariByUsernameOrEmail(string $kataKunci)
    {
        return $this->groupStart()
            ->where('username', $kataKunci)
            ->orWhere('email', $kataKunci)
            ->groupEnd()
            ->first();
    }
}