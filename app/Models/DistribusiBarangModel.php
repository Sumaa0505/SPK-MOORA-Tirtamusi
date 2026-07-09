<?php

namespace App\Models;

use CodeIgniter\Model;

class DistribusiBarangModel extends Model
{
    protected $table      = 'distribusi_barang';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'id_detail_usulan',
        'id_pengadaan_serah',
        'id_alternatif',
        'id_user_pengusul',
        'jenis_distribusi',
        'status_distribusi',
        'jumlah',
        'tanggal_jadwal',
        'tanggal_realisasi',
        'diterima_oleh_pengusul_at',
        'catatan_gudang',
        'catatan_pengusul',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByPengusul($idUser): array
    {
        return $this->select('
                distribusi_barang.*,
                usulan_pengadaan.nomor_usulan,
                usulan_pengadaan.unit_pengusul,
                usulan_pengadaan.jenis_usulan,
                alternatif.kode_alternatif,
                alternatif.nama_alternatif,
                alternatif.kategori_barang,
                alternatif.satuan
            ')
            ->join('usulan_pengadaan', 'usulan_pengadaan.id = distribusi_barang.id_usulan', 'left')
            ->join('alternatif', 'alternatif.id = distribusi_barang.id_alternatif', 'left')
            ->where('distribusi_barang.id_user_pengusul', $idUser)
            ->orderBy('distribusi_barang.id', 'DESC')
            ->findAll();
    }
}
