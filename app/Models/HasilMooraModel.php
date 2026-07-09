<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilMooraModel extends Model
{
    protected $table      = 'hasil_moora';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'id_detail_usulan',
        'nilai_yi',
        'nilai_y',
        'ranking',
        'tanggal_hitung',
        'versi_hitung',
        'mode_hitung',
        'jenis_keputusan',
        'nilai_benefit',
        'nilai_cost',
        'rincian_json',
        'catatan_hitung',
        'checksum_hash',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
