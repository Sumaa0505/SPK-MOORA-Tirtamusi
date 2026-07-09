<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianModel extends Model
{
    protected $table      = 'penilaian';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_detail_usulan',
        'id_kriteria',
        'nilai',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function upsertNilai(int $idDetailUsulan, int $idKriteria, float $nilai): bool
    {
        $existing = $this->where('id_detail_usulan', $idDetailUsulan)
            ->where('id_kriteria', $idKriteria)
            ->first();

        if ($existing) {
            return (bool) $this->update((int) $existing['id'], [
                'nilai' => $nilai,
            ]);
        }

        return (bool) $this->insert([
            'id_detail_usulan' => $idDetailUsulan,
            'id_kriteria' => $idKriteria,
            'nilai' => $nilai,
        ]);
    }
}
