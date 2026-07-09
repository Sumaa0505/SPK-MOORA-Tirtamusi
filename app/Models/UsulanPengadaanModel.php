<?php

namespace App\Models;

use CodeIgniter\Model;

class UsulanPengadaanModel extends Model
{
    protected $table      = 'usulan_pengadaan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nomor_usulan',
        'tanggal_usulan',
        'unit_pengusul',
        'id_user_pengusul',
        'status',
        'status_validasi',
        'approval_stage',
        'jenis_usulan',
        'catatan_validasi',
        'validated_by',
        'validated_at',
        'catatan_pengusul',
        'file_rka_path',
        'file_rka_excel_path',
        'file_rka_dokumen_path',
        'catatan_verifikasi',
        'catatan_manajer',
        'catatan_banding_gudang',
        'banding_by',
        'banding_at',
        'catatan_direksi',
        'catatan_pengadaan',
        'catatan_penerimaan',
        'nomor_disposisi',
        'tanggal_disposisi',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function withPengusul()
    {
        return $this->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul, users.email AS email_pengusul')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left');
    }

    public function getUsulanSiapManajer(): array
    {
        return $this->withPengusul()
            ->whereIn('usulan_pengadaan.status', ['moora_selesai', 'direkomendasikan'])
            ->orderBy('usulan_pengadaan.updated_at', 'DESC')
            ->findAll();
    }

    public function getUsulanDiverifikasiGudang(): array
    {
        return $this->withPengusul()
            ->whereIn('usulan_pengadaan.status', ['diverifikasi', 'moora_selesai'])
            ->orderBy('usulan_pengadaan.updated_at', 'DESC')
            ->findAll();
    }
}
