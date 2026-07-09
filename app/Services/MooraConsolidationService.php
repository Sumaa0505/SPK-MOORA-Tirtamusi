<?php

namespace App\Services;

use App\Models\HasilMooraModel;
use App\Models\MooraEngineLogModel;
use App\Models\UsulanPengadaanModel;
use App\Models\DetailUsulanModel;
use RuntimeException;
use Throwable;

/**
 * PATCH V6 - Konsolidasi Engine MOORA
 *
 * Tujuan:
 * - Menghapus ambiguitas legacy mode aggregate_per_usulan.
 * - Mengunci mode final RKA = rka_aggregate.
 * - Mengunci mode final Pesan Cepat = item_based.
 * - Menjalankan recalculation historis melalui MooraService agar RKA benar-benar
 *   menghasilkan 1 baris hasil agregat, bukan sekadar rename mode.
 */
class MooraConsolidationService
{
    /**
     * Normalisasi ringan untuk data legacy tanpa menghapus hasil.
     * Recalculate final tetap dilakukan oleh consolidateAll().
     *
     * @return array<string,int>
     */
    public function normalizeLegacyMode(): array
    {
        $db = \Config\Database::connect();
        $summary = [
            'hasil_rka_renamed' => 0,
            'hasil_pesan_cepat_fixed' => 0,
            'log_rka_renamed' => 0,
            'log_pesan_cepat_fixed' => 0,
        ];

        if ($db->tableExists('hasil_moora') && $db->tableExists('usulan_pengadaan')) {
            $db->query("\n                UPDATE hasil_moora hm\n                JOIN usulan_pengadaan up ON up.id = hm.id_usulan\n                SET hm.mode_hitung = 'rka_aggregate',\n                    hm.jenis_keputusan = 'RKA',\n                    hm.catatan_hitung = COALESCE(hm.catatan_hitung, 'Patch V6: mode legacy RKA dinormalisasi ke rka_aggregate.'),\n                    hm.updated_at = NOW()\n                WHERE up.jenis_usulan = 'RKA'\n                  AND (hm.mode_hitung IS NULL OR hm.mode_hitung <> 'rka_aggregate')\n            ");
            $summary['hasil_rka_renamed'] = $db->affectedRows();

            $db->query("\n                UPDATE hasil_moora hm\n                JOIN usulan_pengadaan up ON up.id = hm.id_usulan\n                SET hm.mode_hitung = 'item_based',\n                    hm.jenis_keputusan = 'Pesan Cepat',\n                    hm.catatan_hitung = COALESCE(hm.catatan_hitung, 'Patch V6: mode Pesan Cepat dikunci ke item_based.'),\n                    hm.updated_at = NOW()\n                WHERE up.jenis_usulan = 'Pesan Cepat'\n                  AND (hm.mode_hitung IS NULL OR hm.mode_hitung <> 'item_based')\n            ");
            $summary['hasil_pesan_cepat_fixed'] = $db->affectedRows();
        }

        if ($db->tableExists('moora_engine_log') && $db->tableExists('usulan_pengadaan')) {
            $db->query("\n                UPDATE moora_engine_log mel\n                JOIN usulan_pengadaan up ON up.id = mel.id_usulan\n                SET mel.mode_hitung = 'rka_aggregate'\n                WHERE up.jenis_usulan = 'RKA'\n                  AND (mel.mode_hitung IS NULL OR mel.mode_hitung <> 'rka_aggregate')\n            ");
            $summary['log_rka_renamed'] = $db->affectedRows();

            $db->query("\n                UPDATE moora_engine_log mel\n                JOIN usulan_pengadaan up ON up.id = mel.id_usulan\n                SET mel.mode_hitung = 'item_based'\n                WHERE up.jenis_usulan = 'Pesan Cepat'\n                  AND (mel.mode_hitung IS NULL OR mel.mode_hitung <> 'item_based')\n            ");
            $summary['log_pesan_cepat_fixed'] = $db->affectedRows();
        }

        return $summary;
    }

    /**
     * Konsolidasi penuh: normalisasi legacy mode lalu recalculate hasil historis
     * lewat MooraService. Ini menjaga RKA = 1 hasil agregat dan Pesan Cepat = per item.
     *
     * @return array<string,mixed>
     */
    public function consolidateAll(int $limit = 500, array $options = []): array
    {
        $limit = max(1, min(500, $limit));
        $normalisasi = $this->normalizeLegacyMode();

        try {
            $service = new MooraService();
            $summary = $service->konsolidasiHistoris($limit, [
                'engine_log_role' => $options['engine_log_role'] ?? 'v6_consolidation',
            ]);
        } catch (Throwable $e) {
            throw new RuntimeException('Konsolidasi V6 gagal: ' . $e->getMessage(), 0, $e);
        }

        $summary['normalisasi_awal'] = $normalisasi;
        $summary['engine_version'] = 'V6_BUGFIX_AUDIT_ENGINE';

        return $summary;
    }

    /**
     * Laporan cepat untuk CLI/controller lama.
     *
     * @return array<string,mixed>
     */
    public function auditSnapshot(): array
    {
        $hasil = new HasilMooraModel();
        $log = new MooraEngineLogModel();
        $usulan = new UsulanPengadaanModel();
        $detail = new DetailUsulanModel();

        return [
            'total_usulan_non_draft' => $usulan->where('status !=', 'draft')->countAllResults(),
            'total_detail' => $detail->countAllResults(),
            'hasil_rka_aggregate' => $hasil->where('mode_hitung', MooraService::MODE_RKA_AGGREGATE)->countAllResults(),
            'hasil_item_based' => $hasil->where('mode_hitung', MooraService::MODE_ITEM_BASED)->countAllResults(),
            'engine_log' => $log->countAllResults(),
        ];
    }
}
