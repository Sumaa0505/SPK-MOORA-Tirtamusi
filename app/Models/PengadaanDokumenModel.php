<?php

namespace App\Models;

use CodeIgniter\Model;

class PengadaanDokumenModel extends Model
{
    protected $table      = 'pengadaan_dokumen';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_pengadaan',
        'id_usulan',
        'jenis_dokumen',
        'nomor_dokumen',
        'nama_file',
        'file_path',
        'mime_type',
        'uploaded_by',
        'uploaded_at',
        'catatan',
    ];

    protected $useTimestamps = false;
}
