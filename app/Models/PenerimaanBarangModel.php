<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaanBarangModel extends Model
{
    protected $table      = 'penerimaan_barang';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'id_detail_usulan',
        'id_pengadaan_serah',
        'id_alternatif',
        'id_user_gudang',
        'jumlah',
        'status_penerimaan',
        'tanggal',
        'sumber',
        'keterangan',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
