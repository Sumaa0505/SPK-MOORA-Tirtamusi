<?php

namespace App\Models;

use CodeIgniter\Model;

class ApprovalDirekturModel extends Model
{
    protected $table      = 'approval_direktur';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'tahap_approval',
        'urutan',
        'aksi',
        'catatan',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function ensureStage(int $idUsulan, string $stage): array
    {
        $urutan = match ($stage) {
            'direktur_bidang' => 1,
            'direktur_utama'  => 2,
            'direktur_umum'   => 3,
            default           => 1,
        };

        $row = $this->where('id_usulan', $idUsulan)
            ->where('tahap_approval', $stage)
            ->first();

        if ($row) {
            return $row;
        }

        $id = $this->insert([
            'id_usulan'       => $idUsulan,
            'tahap_approval'  => $stage,
            'urutan'          => $urutan,
            'aksi'            => 'menunggu',
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ], true);

        return $this->find($id);
    }
}
