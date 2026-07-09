<?php

namespace App\Services;

use App\Models\AlternatifModel;
use App\Models\DetailUsulanModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\UsulanPengadaanModel;
use RuntimeException;
use Throwable;

/**
 * PATCH AUTO FIX FULL SYSTEM
 *
 * Service ini sengaja dibuat non-destruktif:
 * - Tidak menghapus histori hasil_moora.
 * - Tidak memaksa perubahan struktur dari PHP runtime.
 * - Mengisi kekosongan data minimum supaya workflow sidang tidak berhenti di error runtime.
 */
class MooraAutoFixService
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailModel;
    protected AlternatifModel $alternatifModel;
    protected PenilaianModel $penilaianModel;
    protected KriteriaModel $kriteriaModel;

    public function __construct()
    {
        $this->usulanModel     = new UsulanPengadaanModel();
        $this->detailModel     = new DetailUsulanModel();
        $this->alternatifModel = new AlternatifModel();
        $this->penilaianModel  = new PenilaianModel();
        $this->kriteriaModel   = new KriteriaModel();
    }

    /**
     * Memastikan sebuah usulan selalu punya minimal satu baris detail_usulan.
     * Jika data asli sudah ada, service tidak mengubah isi detail.
     *
     * @param array<string,mixed>|null $usulan
     * @return array<string,mixed>
     */
    public function ensureDetailUsulan(int $idUsulan, ?array $usulan = null): array
    {
        $idUsulan = (int) $idUsulan;
        if ($idUsulan < 1) {
            throw new RuntimeException('ID usulan tidak valid untuk auto fix detail.');
        }

        $db = \Config\Database::connect();
        if (!$db->tableExists('usulan_pengadaan') || !$db->tableExists('detail_usulan')) {
            throw new RuntimeException('Tabel usulan_pengadaan/detail_usulan belum tersedia. Jalankan SQL patch AUTO FIX terlebih dahulu.');
        }

        $usulan = $usulan ?: $this->usulanModel->find($idUsulan);
        if (!$usulan) {
            throw new RuntimeException('Usulan tidak ditemukan untuk auto fix detail.');
        }

        $count = (int) $this->detailModel->where('id_usulan', $idUsulan)->countAllResults();
        if ($count > 0) {
            return [
                'changed' => false,
                'message' => 'Detail usulan sudah tersedia.',
                'jumlah_detail' => $count,
            ];
        }

        $idAlternatif = $this->ensureRecoveryAlternatif($usulan);
        $alternatif = $this->alternatifModel->find($idAlternatif) ?: [];
        $now = date('Y-m-d H:i:s');

        $payload = $this->filterPayload('detail_usulan', [
            'id_usulan'             => $idUsulan,
            'id_alternatif'         => $idAlternatif,
            'jumlah'                => 1,
            'estimasi_harga_satuan' => (float) ($alternatif['estimasi_harga'] ?? 0),
            'alasan_kebutuhan'      => 'AUTO FIX: detail dibuat otomatis karena usulan belum memiliki detail barang. Silakan edit jika ingin mengganti barang asli.',
            'status'                => $this->normalizeDetailStatus((string) ($usulan['status'] ?? 'draft')),
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $inserted = $db->table('detail_usulan')->insert($payload);
        if (!$inserted) {
            $error = $db->error();
            throw new RuntimeException('Auto fix gagal membuat detail_usulan: ' . ($error['message'] ?? 'unknown error'));
        }

        return [
            'changed' => true,
            'message' => 'Detail usulan otomatis dibuat agar MOORA bisa berjalan.',
            'jumlah_detail' => 1,
            'id_detail_usulan' => (int) $db->insertID(),
            'id_alternatif' => $idAlternatif,
        ];
    }

    /**
     * Memastikan semua detail usulan punya nilai penilaian untuk seluruh kriteria aktif.
     * Skor dibuat konservatif agar tidak meledakkan ranking.
     *
     * @return array<string,mixed>
     */
    public function ensurePenilaianMinimum(int $idUsulan): array
    {
        $this->ensureDetailUsulan($idUsulan);

        $details = $this->detailModel->getDetailByUsulan($idUsulan);
        $kriteria = $this->kriteriaModel->where('is_active', 1)->orderBy('kode_kriteria', 'ASC')->findAll();
        if (empty($details) || empty($kriteria)) {
            return [
                'changed' => false,
                'message' => 'Detail/kriteria belum lengkap.',
                'inserted' => 0,
                'updated' => 0,
            ];
        }

        $db = \Config\Database::connect();
        $inserted = 0;
        $updated = 0;
        $now = date('Y-m-d H:i:s');

        foreach ($details as $detail) {
            foreach ($kriteria as $k) {
                $where = [
                    'id_detail_usulan' => (int) $detail['id'],
                    'id_kriteria'      => (int) $k['id'],
                ];
                $exists = $db->table('penilaian')->select('id')->where($where)->get()->getRowArray();
                if ($exists) {
                    continue;
                }

                $payload = $this->filterPayload('penilaian', array_merge($where, [
                    'nilai'      => 5.0000,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));

                $db->table('penilaian')->insert($payload);
                $inserted++;
            }
        }

        return [
            'changed' => $inserted > 0 || $updated > 0,
            'message' => 'Penilaian minimum dicek/dibuat.',
            'inserted' => $inserted,
            'updated' => $updated,
        ];
    }

    /**
     * Repair ringan untuk banyak usulan. Dipakai oleh CLI command atau controller emergency.
     *
     * @return array<string,mixed>
     */
    public function repairUsulanDataFlow(int $limit = 200): array
    {
        $limit = max(1, min(1000, $limit));
        $rows = $this->usulanModel->orderBy('id', 'DESC')->findAll($limit);

        $summary = [
            'checked' => 0,
            'detail_created' => 0,
            'penilaian_inserted' => 0,
            'failed' => 0,
            'items' => [],
        ];

        foreach ($rows as $row) {
            $summary['checked']++;
            try {
                $detail = $this->ensureDetailUsulan((int) $row['id'], $row);
                $nilai = $this->ensurePenilaianMinimum((int) $row['id']);

                if (!empty($detail['changed'])) {
                    $summary['detail_created']++;
                }
                $summary['penilaian_inserted'] += (int) ($nilai['inserted'] ?? 0);

                $summary['items'][] = [
                    'id_usulan' => (int) $row['id'],
                    'nomor_usulan' => $row['nomor_usulan'] ?? '-',
                    'success' => true,
                    'detail' => $detail,
                    'penilaian' => $nilai,
                ];
            } catch (Throwable $e) {
                $summary['failed']++;
                $summary['items'][] = [
                    'id_usulan' => (int) ($row['id'] ?? 0),
                    'nomor_usulan' => $row['nomor_usulan'] ?? '-',
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $summary;
    }

    /** @param array<string,mixed> $usulan */
    protected function ensureRecoveryAlternatif(array $usulan): int
    {
        $idUsulan = (int) ($usulan['id'] ?? 0);
        $kode = 'AFIX' . str_pad((string) $idUsulan, 6, '0', STR_PAD_LEFT);

        $existing = $this->alternatifModel->where('kode_alternatif', $kode)->first();
        if ($existing) {
            return (int) $existing['id'];
        }

        $jenisUsulan = (string) ($usulan['jenis_usulan'] ?? 'RKA');
        $nama = 'Auto Detail Usulan ' . ($usulan['nomor_usulan'] ?? ('#' . $idUsulan));
        $now = date('Y-m-d H:i:s');

        $id = $this->alternatifModel->insert([
            'kode_alternatif' => $kode,
            'nama_alternatif' => substr($nama, 0, 150),
            'kategori_barang' => 'Auto Recovery Data Flow',
            'jenis_barang'    => 'alat',
            'spesifikasi'     => 'Dibuat otomatis oleh Patch Auto Fix Full System untuk mencegah detail_usulan kosong.',
            'satuan'          => 'unit',
            'stok'            => 0,
            'stok_minimum'    => 0,
            'kondisi_barang'  => 'baik',
            'movement_type'   => str_contains(strtolower($jenisUsulan), 'pesan') ? 'first_moving' : 'slow_moving',
            'estimasi_harga'  => 0,
            'keterangan'      => 'AUTO FIX fallback. Edit master barang/detail usulan bila ingin data asli.',
            'is_active'       => 1,
            'created_at'      => $now,
            'updated_at'      => $now,
        ], true);

        if (!$id) {
            throw new RuntimeException('Gagal membuat alternatif fallback AUTO FIX.');
        }

        return (int) $id;
    }

    protected function normalizeDetailStatus(string $status): string
    {
        $status = strtolower(trim($status));
        return match ($status) {
            'draft' => 'draft',
            'diajukan', 'banding_gudang', 'verifikasi_gudang' => 'diajukan',
            'dikembalikan', 'direvisi' => 'dikembalikan',
            'ditolak' => 'ditolak',
            'diproses_pengadaan' => 'diproses_pengadaan',
            'menunggu_penerimaan', 'selesai_pengadaan' => 'menunggu_penerimaan',
            'selesai', 'direalisasi' => 'selesai',
            default => 'diverifikasi',
        };
    }

    /** @param array<string,mixed> $payload */
    protected function filterPayload(string $table, array $payload): array
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($table);
        return array_intersect_key($payload, array_flip($fields));
    }
}
