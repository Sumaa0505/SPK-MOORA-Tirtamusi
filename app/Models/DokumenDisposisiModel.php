<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenDisposisiModel extends Model
{
    protected $table      = 'dokumen_disposisi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'nomor_dokumen',
        'judul_dokumen',
        'file_path',
        'status_dokumen',
        'hash_dokumen',
        'created_by',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
