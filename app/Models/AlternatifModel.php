<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class AlternatifModel extends Model
{
    protected $table      = 'alternatif';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_alternatif',
        'nama_alternatif',
        'jenis_barang',
        'kategori_barang',
        'spesifikasi',
        'satuan',
        'stok',
        'stok_minimum',
        'last_stock_update',
        'kondisi_barang',
        'movement_type',
        'estimasi_harga',
        'keterangan',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;

    /**
     * Patch 11: alternatif.stok tetap menjadi sumber operasional utama,
     * tetapi tabel stok_barang disinkronkan otomatis agar tidak terjadi beda angka.
     */
    protected $afterInsert = ['syncStokBarang'];
    protected $afterUpdate = ['syncStokBarang'];

    /**
     * Ambil semua alternatif aktif untuk Sub Unit
     */
    public function getAktif()
    {
        return $this->where('is_active', 1)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Sinkronisasi non-destruktif ke stok_barang.
     *
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    protected function syncStokBarang(array $data): array
    {
        $payload = $data['data'] ?? [];
        if (!array_key_exists('stok', $payload) && !array_key_exists('stok_minimum', $payload)) {
            return $data;
        }

        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('stok_barang')) {
                return $data;
            }

            $ids = $data['id'] ?? [];
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            foreach ($ids as $id) {
                $id = (int) $id;
                if ($id < 1) {
                    continue;
                }

                $row = $db->table($this->table)
                    ->select('id, stok, stok_minimum')
                    ->where('id', $id)
                    ->get()
                    ->getRowArray();

                if (!$row) {
                    continue;
                }

                $existing = $db->table('stok_barang')
                    ->select('id')
                    ->where('id_alternatif', $id)
                    ->get()
                    ->getRowArray();

                $sync = [
                    'id_alternatif'  => $id,
                    'stok_saat_ini'  => (int) ($row['stok'] ?? 0),
                    'stok_minimum'   => (int) ($row['stok_minimum'] ?? 0),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];

                if ($existing) {
                    $db->table('stok_barang')->where('id', (int) $existing['id'])->update($sync);
                } else {
                    $sync['lokasi_gudang'] = 'Gudang Utama';
                    $sync['created_at'] = date('Y-m-d H:i:s');
                    $db->table('stok_barang')->insert($sync);
                }
            }
        } catch (Throwable $e) {
            log_message('error', 'Gagal sinkron stok_barang dari AlternatifModel: ' . $e->getMessage());
        }

        return $data;
    }
}
