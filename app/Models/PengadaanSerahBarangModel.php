<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanSerahBarangModel extends Model
{
    protected $table      = 'pengadaan_serah_barang';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_pengadaan',
        'id_usulan',
        'id_detail_usulan',
        'id_alternatif',
        'jumlah_diserahkan',
        'tanggal_serah',
        'status_serah',
        'catatan_pengadaan',
        'catatan_gudang',
        'created_by',
        'received_by',
        'received_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
