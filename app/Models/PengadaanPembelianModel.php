<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanPembelianModel extends Model
{
    protected $table      = 'pengadaan_pembelian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'nomor_pengadaan',
        'nomor_po',
        'vendor',
        'tanggal_pengadaan',
        'tanggal_po',
        'total_pengadaan',
        'status_pengadaan',
        'catatan',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
