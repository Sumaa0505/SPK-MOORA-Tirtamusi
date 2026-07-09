<?php

namespace App\Services;

/**
 * PATCH V6 - Audit konsistensi engine MOORA.
 * Service ini tidak mengubah data. Fungsinya mendeteksi bukti hasil yang belum
 * sesuai single source of truth:
 * - RKA harus rka_aggregate dan hanya 1 hasil aktif per versi terakhir.
 * - Pesan Cepat harus item_based dan jumlah hasilnya mengikuti jumlah detail.
 * - Setiap versi hasil terbaru harus punya moora_engine_log.
 */
class MooraAuditService
{
    public function summary(): array
    {
        $db = \Config\Database::connect();

        if (!$db->tableExists('hasil_moora')) {
            return [];
        }

        return $db->table('hasil_moora')
            ->select('mode_hitung, COUNT(*) AS total, AVG(nilai_yi) AS avg_yi')
            ->groupBy('mode_hitung')
            ->orderBy('mode_hitung', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function detectIssues(int $limit = 200): array
    {
        $limit = max(1, min(500, $limit));
        $db = \Config\Database::connect();

        if (!$db->tableExists('usulan_pengadaan') || !$db->tableExists('hasil_moora')) {
            return [];
        }

        if (!$db->tableExists('moora_engine_log')) {
            // SQL Patch V6 belum diimport. Tetap tampilkan issue log agar admin tahu langkah lanjut.
            return [[
                'id_usulan' => 0,
                'nomor_usulan' => '-',
                'jenis_usulan' => '-',
                'status' => '-',
                'versi_terakhir' => null,
                'jumlah_detail' => 0,
                'jumlah_hasil_terbaru' => 0,
                'mode_terbaru' => '-',
                'jumlah_log' => 0,
                'kode_issue' => 'tabel_moora_engine_log_belum_ada',
                'pesan' => 'Import SQL Patch V6 terlebih dahulu agar tabel audit engine tersedia.',
            ]];
        }

        $latest = $db->table('hasil_moora')
            ->select('id_usulan, MAX(versi_hitung) AS versi_terakhir')
            ->groupBy('id_usulan');

        $rows = $db->table('usulan_pengadaan up')
            ->select("\n                up.id, up.nomor_usulan, up.jenis_usulan, up.status,\n                latest.versi_terakhir,\n                COUNT(DISTINCT du.id) AS jumlah_detail,\n                COUNT(DISTINCT hm.id) AS jumlah_hasil_terbaru,\n                GROUP_CONCAT(DISTINCT hm.mode_hitung ORDER BY hm.mode_hitung SEPARATOR ', ') AS mode_terbaru,\n                COUNT(DISTINCT mel.id) AS jumlah_log\n            ")
            ->join('detail_usulan du', 'du.id_usulan = up.id', 'left')
            ->join('(' . $latest->getCompiledSelect(false) . ') latest', 'latest.id_usulan = up.id', 'left')
            ->join('hasil_moora hm', 'hm.id_usulan = up.id AND hm.versi_hitung = latest.versi_terakhir', 'left')
            ->join('moora_engine_log mel', 'mel.id_usulan = up.id AND mel.versi_hitung = latest.versi_terakhir', 'left')
            ->where('up.status !=', WorkflowUsulanService::STATUS_DRAFT)
            ->groupBy('up.id, latest.versi_terakhir')
            ->orderBy('up.updated_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $issues = [];
        foreach ($rows as $row) {
            $jenis = strtolower(str_replace([' ', '_', '-'], '', (string) ($row['jenis_usulan'] ?? 'RKA')));
            $mode = (string) ($row['mode_terbaru'] ?? '');
            $jumlahDetail = (int) ($row['jumlah_detail'] ?? 0);
            $jumlahHasil = (int) ($row['jumlah_hasil_terbaru'] ?? 0);
            $jumlahLog = (int) ($row['jumlah_log'] ?? 0);

            if ($jumlahHasil === 0) {
                $issues[] = $this->issue($row, 'belum_ada_hasil', 'Usulan belum memiliki hasil MOORA terbaru.');
                continue;
            }

            if ($jenis === 'pesancepat') {
                if ($mode !== MooraService::MODE_ITEM_BASED) {
                    $issues[] = $this->issue($row, 'mode_pesan_cepat_salah', 'Pesan Cepat harus item_based.');
                }
                if ($jumlahDetail > 0 && $jumlahHasil !== $jumlahDetail) {
                    $issues[] = $this->issue($row, 'jumlah_hasil_item_tidak_sesuai', 'Pesan Cepat seharusnya menghasilkan ranking per detail barang.');
                }
            } else {
                if ($mode !== MooraService::MODE_RKA_AGGREGATE) {
                    $issues[] = $this->issue($row, 'mode_rka_salah', 'RKA harus rka_aggregate.');
                }
                if ($jumlahHasil !== 1) {
                    $issues[] = $this->issue($row, 'rka_lebih_dari_satu_hasil', 'RKA final harus memiliki 1 hasil agregat pada versi terbaru.');
                }
            }

            if ($jumlahLog === 0) {
                $issues[] = $this->issue($row, 'log_engine_tidak_ada', 'Versi hasil terbaru belum memiliki moora_engine_log.');
            }
        }

        return $issues;
    }

    /** Alias kompatibilitas method lama. */
    public function detectBrokenRka(): array
    {
        return array_values(array_filter($this->detectIssues(), static function ($item) {
            return str_starts_with((string) ($item['kode_issue'] ?? ''), 'mode_rka')
                || (string) ($item['kode_issue'] ?? '') === 'rka_lebih_dari_satu_hasil';
        }));
    }

    protected function issue(array $row, string $code, string $message): array
    {
        return [
            'id_usulan' => (int) ($row['id'] ?? 0),
            'nomor_usulan' => $row['nomor_usulan'] ?? '-',
            'jenis_usulan' => $row['jenis_usulan'] ?? '-',
            'status' => $row['status'] ?? '-',
            'versi_terakhir' => $row['versi_terakhir'] ?? null,
            'jumlah_detail' => (int) ($row['jumlah_detail'] ?? 0),
            'jumlah_hasil_terbaru' => (int) ($row['jumlah_hasil_terbaru'] ?? 0),
            'mode_terbaru' => $row['mode_terbaru'] ?? '-',
            'jumlah_log' => (int) ($row['jumlah_log'] ?? 0),
            'kode_issue' => $code,
            'pesan' => $message,
        ];
    }
}
