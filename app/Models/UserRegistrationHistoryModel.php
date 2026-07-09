<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRegistrationHistoryModel extends Model
{
    protected $table      = 'user_registration_history';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'registration_id',
        'status',
        'changed_by',
        'note',
        'created_at',
    ];

    protected $useTimestamps = false;
}
