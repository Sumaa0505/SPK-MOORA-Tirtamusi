<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatValidasiModel extends Model
{
    protected $table      = 'riwayat_validasi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_usulan',
        'id_user',
        'role_user',
        'aksi',
        'catatan',
        'tanggal_aksi',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}