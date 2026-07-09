<?php

namespace App\Models;

use CodeIgniter\Model;

class PerbaikanAlatModel extends Model
{
    protected $table      = 'perbaikan_alat';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_alternatif',
        'unit_pemakai',
        'lokasi_unit',
        'penanggung_jawab',
        'tanggal_perbaikan',
        'tanggal_target',
        'tanggal_selesai',
        'kerusakan',
        'tindakan_perbaikan',
        'biaya_perbaikan',
        'prioritas',
        'status_perbaikan',
        'catatan',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;

    public function getPerbaikanLengkap()
    {
        return $this->select('
                perbaikan_alat.*,
                alternatif.kode_alternatif,
                alternatif.nama_alternatif,
                alternatif.kategori_barang,
                alternatif.satuan,
                alternatif.kondisi_barang
            ')
            ->join('alternatif', 'alternatif.id = perbaikan_alat.id_alternatif', 'left')
            ->orderBy('perbaikan_alat.id', 'DESC')
            ->findAll();
    }

    public function getDetail($id)
    {
        return $this->select('
                perbaikan_alat.*,
                alternatif.kode_alternatif,
                alternatif.nama_alternatif,
                alternatif.kategori_barang,
                alternatif.satuan,
                alternatif.kondisi_barang
            ')
            ->join('alternatif', 'alternatif.id = perbaikan_alat.id_alternatif', 'left')
            ->where('perbaikan_alat.id', $id)
            ->first();
    }
}