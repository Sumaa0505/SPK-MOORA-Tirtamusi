<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

/**
 * PATCH 11 URGENT MOORA WORKFLOW FIX
 *
 * Query layer tunggal untuk membaca hasil MOORA.
 * Prinsip:
 * - Tidak membaca hasil_moora langsung untuk dashboard/ranking.
 * - Sumber utama: v_latest_moora_context / v_moora_global_final.
 * - Jika tersedia, ranking global memakai kolom global_ranking dari SQL view Patch 11.
 * - Latest dikunci per id_usulan + mode_hitung.
 * - Role dipisahkan supaya dashboard tidak saling bocor status workflow.
 * - Patch 10 menambah helper workflow completion agar semua controller memakai query source yang sama.
 */
class MooraResultQueryService
{
    public const MODE_RKA_AGGREGATE = 'rka_aggregate';
    public const MODE_ITEM_BASED    = 'item_based';

    /** Ranking global aktif hanya untuk hasil yang baru selesai dihitung Gudang. */
    public const GLOBAL_RANKING_STATUSES = ['moora_selesai'];

    /** Data locked yang tetap aman untuk histori lintas workflow setelah MOORA final. */
    public const WORKFLOW_LOCKED_STATUSES = [
        'moora_selesai',
        'menunggu_direktur_bidang',
        'menunggu_direktur_utama',
        'menunggu_direktur_umum',
        'disposisi_pengadaan',
        'diproses_pengadaan',
        'menunggu_penerimaan',
        'selesai',
    ];

    /** Status khusus approval Direktur. */
    public const DIREKTUR_APPROVAL_STATUSES = [
        'menunggu_direktur_bidang',
        'menunggu_direktur_utama',
        'menunggu_direktur_umum',
    ];

    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?: \Config\Database::connect();
    }

    /**
     * Kompatibilitas Patch 7 lama.
     */
    public function getLatestByUsulan($db, $idUsulan): array
    {
        $database = $db instanceof BaseConnection ? $db : $this->db;
        return $database->table($this->contextSource())
            ->where('id_usulan', (int) $idUsulan)
            ->orderBy('mode_hitung', 'ASC')
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Kompatibilitas Patch 7 lama: latest RKA aman dari view dual-mode.
     */
    public function getLatestRka($db = null): array
    {
        $database = $db instanceof BaseConnection ? $db : $this->db;
        return $database->table($this->contextSource())
            ->where('mode_hitung', self::MODE_RKA_AGGREGATE)
            ->orderBy('usulan_updated_at', 'DESC')
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Kompatibilitas Patch 7 lama: latest Pesan Cepat aman dari view dual-mode.
     */
    public function getLatestItem($db = null): array
    {
        $database = $db instanceof BaseConnection ? $db : $this->db;
        return $database->table($this->contextSource())
            ->where('mode_hitung', self::MODE_ITEM_BASED)
            ->orderBy('usulan_updated_at', 'DESC')
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getLatestByRka(int $idUsulan): array
    {
        return $this->latestByUsulan($idUsulan, self::MODE_RKA_AGGREGATE);
    }

    public function getLatestByItem(int $idDetailUsulan): array
    {
        return $this->baseQuery($this->contextSource())
            ->where('mode_hitung', self::MODE_ITEM_BASED)
            ->where('id_detail_usulan', $idDetailUsulan)
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function latestByUsulan(int $idUsulan, ?string $mode = null): array
    {
        $builder = $this->baseQuery($this->contextSource())
            ->where('id_usulan', $idUsulan);

        if ($mode !== null && $mode !== '') {
            $builder->where('mode_hitung', $this->normalizeMode($mode));
        }

        return $builder
            ->orderBy('mode_hitung', 'ASC')
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Alias eksplisit Patch 9 untuk hasil aktif resmi.
     * Seluruh role boleh memakai method ini daripada membaca hasil_moora langsung.
     */
    public function activeLatestByUsulan(int $idUsulan, ?string $mode = null): array
    {
        return $this->latestByUsulan($idUsulan, $mode);
    }


    /**
     * Hasil aktif resmi untuk dokumen/approval. Tetap memakai view latest,
     * bukan hasil_moora mentah, sehingga RKA hanya tampil sebagai 1 baris agregat.
     */
    public function workflowLatestByUsulan(int $idUsulan): array
    {
        return $this->baseQuery($this->contextSource())
            ->where('id_usulan', $idUsulan)
            ->whereIn('status', self::WORKFLOW_LOCKED_STATUSES)
            ->orderBy('mode_hitung', 'ASC')
            ->orderBy('ranking', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ringkasan per usulan agar dashboard tidak menampilkan RKA terpecah per barang.
     */
    public function getUsulanSummaryForRole(string $role, ?int $idUser = null, int $limit = 50): array
    {
        $rows = $this->getForRole($role, $idUser, 500);
        $summary = [];

        foreach ($rows as $row) {
            $id = (int) ($row['id_usulan'] ?? 0);
            if ($id < 1) {
                continue;
            }

            if (!isset($summary[$id])) {
                $summary[$id] = $row;
                $summary[$id]['jumlah_hasil'] = 0;
                $summary[$id]['nilai_yi_total'] = 0.0;
                $summary[$id]['nilai_yi_tertinggi'] = (float) ($row['nilai_yi'] ?? 0);
            }

            $nilai = (float) ($row['nilai_yi'] ?? 0);
            $summary[$id]['jumlah_hasil']++;
            $summary[$id]['nilai_yi_total'] += $nilai;
            $summary[$id]['nilai_yi_tertinggi'] = max((float) $summary[$id]['nilai_yi_tertinggi'], $nilai);
        }

        usort($summary, static function ($a, $b) {
            return strcmp((string) ($b['usulan_updated_at'] ?? ''), (string) ($a['usulan_updated_at'] ?? ''));
        });

        return array_slice(array_values($summary), 0, max(1, $limit));
    }

    /**
     * Hasil ranking terbaru untuk dashboard Admin/monitoring tanpa membaca raw hasil_moora.
     */
    public function dashboardRanking(int $limit = 5): array
    {
        return $this->baseQuery($this->contextSource())
            ->whereIn('status', self::WORKFLOW_LOCKED_STATUSES)
            ->orderBy('usulan_updated_at', 'DESC')
            ->orderBy('nilai_yi', 'DESC')
            ->orderBy('ranking', 'ASC')
            ->limit(max(1, $limit))
            ->get()
            ->getResultArray();
    }

    public function hasActiveResult(int $idUsulan): bool
    {
        return count($this->activeLatestByUsulan($idUsulan)) > 0;
    }

    /**
     * Ranking global aktif: hanya status moora_selesai.
     */
    public function getGlobalRanking(?string $mode = null, int $limit = 10): array
    {
        $source = $this->globalSource();
        $builder = $this->baseQuery($source);

        if ($mode !== null && $mode !== '') {
            $builder->where('mode_hitung', $this->normalizeMode($mode));
        }

        if ($this->sourceHasField($source, 'global_ranking')) {
            $builder->orderBy('global_ranking', 'ASC');
        } else {
            $builder->orderBy('nilai_yi', 'DESC')->orderBy('ranking', 'ASC');
        }

        $rows = $builder
            ->limit(max(1, $limit))
            ->get()
            ->getResultArray();

        return $this->withDisplayRanking($rows);
    }

    /**
     * Query role-based supaya dashboard tidak memakai raw global query.
     */
    public function getForRole(string $role, ?int $idUser = null, int $limit = 20, ?string $mode = null): array
    {
        $role = strtolower(trim($role));
        $limit = max(1, min(500, $limit));

        switch ($role) {
            case 'manajer_umum':
                // Patch 11: Manajer Umum membaca ranking global aktif, bukan sorting lokal per-usulan.
                $source = $this->globalSource();
                $builder = $this->baseQuery($source)
                    ->whereIn('status', self::GLOBAL_RANKING_STATUSES);
                break;

            case 'direktur':
                $builder = $this->baseQuery($this->contextSource())
                    ->whereIn('status', self::DIREKTUR_APPROVAL_STATUSES);
                break;

            case 'pengadaan':
                $builder = $this->baseQuery($this->contextSource())
                    ->whereIn('status', ['disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan']);
                break;

            case 'sub_unit':
                $builder = $this->baseQuery($this->contextSource());
                if ($idUser !== null && $idUser > 0) {
                    $builder->where('id_user_pengusul', $idUser);
                }
                break;

            case 'gudang':
                $builder = $this->baseQuery($this->contextSource())
                    ->whereIn('status', array_merge(
                        ['diverifikasi'],
                        self::GLOBAL_RANKING_STATUSES,
                        self::DIREKTUR_APPROVAL_STATUSES,
                        ['disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan', 'selesai']
                    ));
                break;

            default:
                $builder = $this->baseQuery($this->globalSource());
                break;
        }

        if ($mode !== null && $mode !== '') {
            $builder->where('mode_hitung', $this->normalizeMode($mode));
        }

        if (($source ?? null) && $this->sourceHasField($source, 'global_ranking')) {
            $builder->orderBy('global_ranking', 'ASC');
        } else {
            $builder->orderBy('usulan_updated_at', 'DESC')
                ->orderBy('ranking', 'ASC');
        }

        $rows = $builder
            ->limit($limit)
            ->get()
            ->getResultArray();

        return $this->withDisplayRanking($rows);
    }

    public function getChartMooraPerUsulan(string $role = 'manajer_umum', ?int $idUser = null, int $limit = 20): array
    {
        $rows = $this->getForRole($role, $idUser, $limit);
        $grouped = [];

        foreach ($rows as $row) {
            $id = (int) ($row['id_usulan'] ?? 0);
            if ($id < 1) {
                continue;
            }

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'id_usulan'    => $id,
                    'nomor_usulan' => $row['nomor_usulan'] ?? '-',
                    'nilai_yi'     => 0.0,
                    'total_nilai'  => 0.0,
                ];
            }

            $nilai = (float) ($row['nilai_yi'] ?? 0);
            // RKA agregat hanya 1 baris, item_based boleh lebih dari 1 baris.
            $grouped[$id]['nilai_yi'] += $nilai;
            $grouped[$id]['total_nilai'] += $nilai;
        }

        usort($grouped, static fn ($a, $b) => $b['nilai_yi'] <=> $a['nilai_yi']);
        return array_slice($grouped, 0, $limit);
    }

    public function countForRole(string $role, ?int $idUser = null): int
    {
        $rows = $this->getForRole($role, $idUser, 500);
        return count($rows);
    }

    public function auditModeSummary(): array
    {
        $summary = [
            'rka_aggregate'       => 0,
            'rka_masih_item'      => 0,
            'pesan_cepat_item'    => 0,
            'pesan_cepat_lainnya' => 0,
            'belum_ada_hasil'     => 0,
        ];

        $rows = $this->baseQuery($this->contextSource())
            ->select('id_usulan, jenis_usulan, mode_hitung')
            ->groupBy('id_usulan, jenis_usulan, mode_hitung')
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $jenis = strtolower(str_replace([' ', '_', '-'], '', (string) ($row['jenis_usulan'] ?? 'RKA')));
            $mode = $this->normalizeMode((string) ($row['mode_hitung'] ?? ''));

            if ($mode === '') {
                $summary['belum_ada_hasil']++;
            } elseif ($jenis === 'pesancepat') {
                $mode === self::MODE_ITEM_BASED ? $summary['pesan_cepat_item']++ : $summary['pesan_cepat_lainnya']++;
            } else {
                $mode === self::MODE_RKA_AGGREGATE ? $summary['rka_aggregate']++ : $summary['rka_masih_item']++;
            }
        }

        return $summary;
    }

    /**
     * Jika view Patch 11 menyediakan global_ranking, tampilkan angka itu pada field ranking
     * agar view lama otomatis menampilkan ranking global tanpa refactor besar.
     *
     * @param array<int,array<string,mixed>> $rows
     * @return array<int,array<string,mixed>>
     */
    protected function withDisplayRanking(array $rows): array
    {
        foreach ($rows as &$row) {
            if (isset($row['global_ranking']) && $row['global_ranking'] !== null) {
                $row['ranking_lokal'] = $row['ranking'] ?? null;
                $row['ranking'] = (int) $row['global_ranking'];
            }
            if (!isset($row['nama_barang']) && isset($row['nama_alternatif'])) {
                $row['nama_barang'] = $row['nama_alternatif'];
            }
        }
        unset($row);

        return $rows;
    }

    protected function sourceHasField(string $source, string $field): bool
    {
        try {
            return in_array($field, $this->db->getFieldNames($source), true);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function normalizeMode(string $mode): string
    {
        $mode = trim($mode);

        if (in_array($mode, ['aggregate_per_usulan', 'rka', 'rka_aggregated', 'aggregate', 'aggregate_rka', 'rka_aggregate'], true)) {
            return self::MODE_RKA_AGGREGATE;
        }

        if (in_array($mode, ['item_per_detail', 'per_item', 'detail_based', 'item_based'], true)) {
            return self::MODE_ITEM_BASED;
        }

        return $mode;
    }

    protected function baseQuery(?string $source = null)
    {
        return $this->db->table($source ?: $this->contextSource());
    }

    protected function contextSource(): string
    {
        return $this->db->tableExists('v_latest_moora_context') ? 'v_latest_moora_context' : 'v_latest_moora';
    }

    protected function globalSource(): string
    {
        return $this->db->tableExists('v_moora_global_final') ? 'v_moora_global_final' : $this->contextSource();
    }
}
