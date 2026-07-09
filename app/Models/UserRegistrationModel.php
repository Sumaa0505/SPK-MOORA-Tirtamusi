<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegistrationModel extends Model
{
    protected $table      = 'user_registration';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'role',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}
