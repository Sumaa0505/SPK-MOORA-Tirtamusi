<?php

namespace App\Models;

use CodeIgniter\Model;

class QrDisposisiModel extends Model
{
    protected $table      = 'qr_disposisi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_dokumen',
        'id_usulan',
        'qr_hash',
        'verification_url',
        'qr_file_path',
        'is_valid',
        'created_at',
    ];

    protected $useTimestamps = false;
}
