<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailUsulanModel extends Model
{
    protected $table      = 'detail_usulan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_usulan',
        'id_alternatif',
        'jumlah',
        'estimasi_harga_satuan',
        'total_estimasi',
        'alasan_kebutuhan',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua detail usulan beserta info alternatif.
     * Patch 11: stok dibaca dari sumber yang paling baru antara alternatif dan stok_barang,
     * sehingga nilai otomatis MOORA tidak lagi membaca angka stok yang stale.
     */
    public function getDetailByUsulan(int $idUsulan): array
    {
        return $this->select("detail_usulan.*,
                alternatif.kode_alternatif,
                alternatif.nama_alternatif,
                alternatif.kategori_barang,
                alternatif.jenis_barang,
                alternatif.spesifikasi,
                alternatif.satuan,
                alternatif.estimasi_harga,
                CASE
                    WHEN stok_barang.id IS NOT NULL
                     AND COALESCE(stok_barang.updated_at, '1970-01-01 00:00:00') > COALESCE(alternatif.updated_at, '1970-01-01 00:00:00')
                    THEN stok_barang.stok_saat_ini
                    ELSE alternatif.stok
                END AS stok,
                CASE
                    WHEN stok_barang.id IS NOT NULL
                     AND COALESCE(stok_barang.updated_at, '1970-01-01 00:00:00') > COALESCE(alternatif.updated_at, '1970-01-01 00:00:00')
                    THEN stok_barang.stok_minimum
                    ELSE alternatif.stok_minimum
                END AS stok_minimum,
                stok_barang.lokasi_gudang,
                alternatif.kondisi_barang,
                alternatif.movement_type")
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
            ->join('stok_barang', 'stok_barang.id_alternatif = detail_usulan.id_alternatif', 'left')
            ->where('detail_usulan.id_usulan', $idUsulan)
            ->orderBy('detail_usulan.id', 'ASC')
            ->findAll();
    }

    /**
     * Update status detail usulan (misal: draft, diajukan, diverifikasi)
     */
    public function updateStatusByUsulan(int $idUsulan, string $status)
    {
        return $this->where('id_usulan', $idUsulan)
                    ->set('status', $status)
                    ->update();
    }

    /**
     * Hitung total estimasi semua detail usulan
     */
    public function totalEstimasiByUsulan(int $idUsulan): float
    {
        $total = $this->selectSum('total_estimasi')
                      ->where('id_usulan', $idUsulan)
                      ->first();

        return (float) ($total['total_estimasi'] ?? 0);
    }
}
