<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminUserApprovalLogModel extends Model
{
    protected $table      = 'admin_user_approval_log';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'registration_id',
        'admin_id',
        'action',
        'catatan',
        'created_at',
    ];

    protected $useTimestamps = false;
}
