<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table      = 'kriteria';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode_kriteria',
        'nama_kriteria',
        'jenis',
        'bobot',
        'skala_min',
        'skala_max',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;

    public function getAktif()
    {
        return $this->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function totalBobotAktif()
    {
        $row = $this->selectSum('bobot')
            ->where('is_active', 1)
            ->first();

        return (float) ($row['bobot'] ?? 0);
    }
}