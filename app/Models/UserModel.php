<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login',
        'registration_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
