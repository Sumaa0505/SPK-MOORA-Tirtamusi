<?php

namespace App\Models;

use CodeIgniter\Model;

class MooraEngineLogModel extends Model
{
    protected $table      = 'moora_engine_log';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'mode_hitung',
        'versi_hitung',
        'jumlah_detail',
        'jumlah_hasil',
        'processed_by',
        'processed_role',
        'checksum_hash',
        'catatan_hitung',
        'created_at',
    ];

    protected $useTimestamps = false;
}
