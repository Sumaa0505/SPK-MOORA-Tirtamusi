<?php

namespace App\Services;

use App\Models\DetailUsulanModel;
use App\Models\HasilMooraModel;
use App\Models\KriteriaModel;
use App\Models\LogAktivitasModel;
use App\Models\NotificationModel;
use App\Models\PenilaianModel;
use App\Models\SettingModel;
use App\Models\UsulanPengadaanModel;
use RuntimeException;
use Throwable;

/**
 * PATCH 11 URGENT MOORA WORKFLOW FIX
 *
 * Prinsip final:
 * - Gudang adalah engine operasional MOORA.
 * - Admin hanya master data, bobot, setting, dan training/simulasi.
 * - RKA dihitung sebagai 1 keputusan agregat dari seluruh barang di dokumen RKA.
 * - Pesan Cepat dihitung per item barang.
 */
class MooraService
{
    public const MODE_RKA_AGGREGATE = 'rka_aggregate';
    public const MODE_ITEM_BASED    = 'item_based';

    protected DetailUsulanModel $detailUsulanModel;
    protected PenilaianModel $penilaianModel;
    protected HasilMooraModel $hasilMooraModel;
    protected KriteriaModel $kriteriaModel;
    protected UsulanPengadaanModel $usulanModel;
    protected LogAktivitasModel $logModel;
    protected SettingModel $settingModel;
    protected WorkflowUsulanService $workflowService;
    protected NotificationModel $notificationModel;
    protected MooraAutoFixService $autoFixService;

    public function __construct()
    {
        $this->detailUsulanModel = new DetailUsulanModel();
        $this->penilaianModel    = new PenilaianModel();
        $this->hasilMooraModel   = new HasilMooraModel();
        $this->kriteriaModel     = new KriteriaModel();
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->logModel          = new LogAktivitasModel();
        $this->settingModel      = new SettingModel();
        $this->workflowService   = new WorkflowUsulanService();
        $this->notificationModel = new NotificationModel();
        $this->autoFixService   = new MooraAutoFixService();
    }

    public function validateBobot(array $kriteria): array
    {
        $totalBobot = 0.0;
        foreach ($kriteria as $k) {
            $totalBobot += (float) ($k['bobot'] ?? 0);
        }

        return [
            'valid'       => abs($totalBobot - 1.0) < 0.00001,
            'total_bobot' => $totalBobot,
        ];
    }

    public function resolveMode(array $usulan): string
    {
        $jenis = strtolower(str_replace([' ', '_', '-'], '', (string) ($usulan['jenis_usulan'] ?? 'RKA')));
        return $jenis === 'pesancepat' ? self::MODE_ITEM_BASED : self::MODE_RKA_AGGREGATE;
    }

    public function modeLabel(string $mode): string
    {
        return $mode === self::MODE_RKA_AGGREGATE
            ? 'RKA - Agregasi Dokumen'
            : 'Pesan Cepat - Per Item Barang';
    }

    /**
     * Preview nilai otomatis tanpa menyimpan ke database.
     */
    public function previewPenilaianOtomatis(int $idUsulan): array
    {
        [$usulan, $detailUsulan, $kriteria] = $this->loadDataset($idUsulan);

        $generated = $this->generateNilaiDetail($usulan, $detailUsulan, $kriteria);
        $mode      = $this->resolveMode($usulan);
        $rows      = $this->buildDecisionRows($usulan, $detailUsulan, $kriteria, $generated, $mode);
        $hasil     = $this->calculateFromDecisionRows($rows, $kriteria, (int) $usulan['id'], 'standard_moora');

        return [
            'usulan'        => $usulan,
            'detailUsulan'  => $detailUsulan,
            'kriteria'      => $kriteria,
            'generated'     => $generated,
            'mode_hitung'   => $mode,
            'mode_label'    => $this->modeLabel($mode),
            'decision_rows' => $rows,
            'hasil_preview' => $hasil,
        ];
    }

    /**
     * Generate dan simpan penilaian C1-C5 otomatis berdasarkan data barang, stok, biaya,
     * jenis usulan, alasan kebutuhan, kondisi, dan movement type.
     */
    public function generateDanSimpanPenilaian(int $idUsulan): array
    {
        [$usulan, $detailUsulan, $kriteria] = $this->loadDataset($idUsulan);
        $generated = $this->generateNilaiDetail($usulan, $detailUsulan, $kriteria);
        $this->saveGeneratedPenilaian($generated);

        return $generated;
    }

    /**
     * Proses MOORA final untuk satu usulan.
     * - RKA: hasil_moora menyimpan 1 baris agregat dengan id_detail_usulan anchor pertama.
     * - Pesan Cepat: hasil_moora menyimpan ranking per barang/detail.
     *
     * @param array<string,mixed> $options
     */
    public function prosesUsulan(int $idUsulan, array $options = []): array
    {
        [$usulan, $detailUsulan, $kriteria] = $this->loadDataset($idUsulan);

        $statusUsulan = $this->workflowService->normalizeStatus($usulan['status'] ?? 'draft');
        $allowedStatus = [WorkflowUsulanService::STATUS_DIVERIFIKASI, WorkflowUsulanService::STATUS_MOORA_SELESAI];
        if (empty($options['allow_any_status']) && !in_array($statusUsulan, $allowedStatus, true)) {
            throw new RuntimeException('MOORA hanya boleh diproses oleh Gudang untuk usulan berstatus diverifikasi/moora_selesai. Status saat ini: ' . $statusUsulan);
        }

        $cekBobot = $this->validateBobot($kriteria);
        if (!$cekBobot['valid']) {
            throw new RuntimeException('Total bobot kriteria aktif harus 1.00. Total saat ini: ' . number_format($cekBobot['total_bobot'], 4));
        }

        $mode = $this->resolveMode($usulan);
        $autoGenerate = array_key_exists('auto_generate', $options) ? (bool) $options['auto_generate'] : true;

        if ($autoGenerate) {
            $generated = $this->generateDanSimpanPenilaian($idUsulan);
        } else {
            $generated = $this->loadPenilaianMap($detailUsulan, $kriteria);
            $this->assertNilaiLengkap($detailUsulan, $kriteria, $generated);
        }

        $rows = $this->buildDecisionRows($usulan, $detailUsulan, $kriteria, $generated, $mode);
        $costMode = (string) ($options['cost_mode'] ?? $this->settingModel->getValue('moora_cost_mode', 'standard_moora'));
        if (!in_array($costMode, ['standard_moora', 'inverse_before_weight'], true)) {
            $costMode = 'standard_moora';
        }

        $hasil       = $this->calculateFromDecisionRows($rows, $kriteria, $idUsulan, $costMode);
        $versiHitung = $this->generateVersiHitung($idUsulan);
        $tanggalNow  = date('Y-m-d H:i:s');
        $batchInsert = [];

        foreach ($hasil as $row) {
            $payload = [
                'id_usulan'        => (int) ($row['id_usulan'] ?? $idUsulan),
                'id_detail_usulan' => (int) $row['id_detail_usulan'],
                'nilai_yi'         => (float) $row['nilai_yi'],
                'ranking'          => (int) $row['ranking'],
                'tanggal_hitung'   => $tanggalNow,
                'versi_hitung'     => $versiHitung,
                'mode_hitung'      => $mode,
                'jenis_keputusan'  => $mode === self::MODE_RKA_AGGREGATE ? 'RKA' : 'Pesan Cepat',
                'nilai_benefit'    => (float) $row['benefit'],
                'nilai_cost'       => (float) $row['cost'],
                'rincian_json'     => json_encode($row['rincian'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'catatan_hitung'   => $mode === self::MODE_RKA_AGGREGATE
                    ? 'Hasil agregasi seluruh barang dalam dokumen RKA.'
                    : 'Hasil per item barang Pesan Cepat.',
                'checksum_hash'    => hash('sha256', implode('|', [
                    $idUsulan,
                    (int) $row['id_detail_usulan'],
                    $versiHitung,
                    $mode,
                    number_format((float) $row['nilai_yi'], 8, '.', ''),
                    json_encode($row['rincian'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ])),
                'created_at'       => $tanggalNow,
                'updated_at'       => $tanggalNow,
            ];

            $batchInsert[] = $this->filterPayloadForTable('hasil_moora', $payload);
        }

        if (empty($batchInsert)) {
            throw new RuntimeException('Tidak ada hasil MOORA yang dapat disimpan.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // PATCH 9: jangan hapus histori hasil_moora.
            // hasil_moora = audit trail semua versi, v_latest_moora = hasil aktif terbaru.
            $this->hasilMooraModel->insertBatch($batchInsert);
            $this->saveEngineLog(
                $idUsulan,
                $mode,
                $versiHitung,
                count($detailUsulan),
                count($hasil),
                $tanggalNow,
                $options['engine_log_role'] ?? null
            );
            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw new RuntimeException('Transaksi penyimpanan hasil MOORA gagal: ' . $e->getMessage());
        }

        $idUser = $this->currentUserId();
        $role   = (string) (session()->get('role') ?: ($options['engine_log_role'] ?? 'gudang'));

        if (empty($options['skip_workflow'])) {
            try {
                $this->workflowService->transition(
                    $idUsulan,
                    WorkflowUsulanService::STATUS_MOORA_SELESAI,
                    $idUser,
                    $role,
                    'proses_moora',
                    'Gudang memproses MOORA final mode ' . $this->modeLabel($mode) . '. Versi hitung: ' . $versiHitung,
                    'Gudang',
                    [
                        'status_validasi' => 'moora_selesai',
                    ]
                );
            } catch (Throwable $e) {
                log_message('error', 'Hasil MOORA tersimpan tetapi workflow gagal dicatat: ' . $e->getMessage());
                throw new RuntimeException('Hasil MOORA tersimpan, tetapi update workflow gagal: ' . $e->getMessage());
            }
        } else {
            try {
                $request = service('request');
                $this->logModel->insert([
                    'id_user'    => $idUser,
                    'aktivitas'  => 'Konsolidasi MOORA V6',
                    'modul'      => 'MOORA Engine',
                    'keterangan' => 'Recalculate historis untuk usulan ' . ($usulan['nomor_usulan'] ?? '#' . $idUsulan) . ' mode ' . $this->modeLabel($mode) . '. Status usulan tidak diubah. Versi hitung: ' . $versiHitung,
                    'ip_address' => is_cli() ? '127.0.0.1' : ($request->getIPAddress() ?? '127.0.0.1'),
                    'created_at' => $tanggalNow,
                ]);
            } catch (Throwable $e) {
                log_message('error', 'Gagal mencatat log konsolidasi MOORA V6: ' . $e->getMessage());
            }
        }

        if (empty($options['skip_notification'])) {
            try {
                $this->notificationModel->createForRole(
                    'manajer_umum',
                    'Hasil MOORA Siap Direview',
                    'Usulan ' . ($usulan['nomor_usulan'] ?? $idUsulan) . ' selesai dihitung oleh Gudang dengan mode ' . $this->modeLabel($mode) . '.',
                    'manajer-umum/usulan/detail/' . $idUsulan,
                    'moora',
                    $idUsulan,
                    $idUser
                );
            } catch (Throwable $e) {
                log_message('error', 'Gagal membuat notifikasi hasil MOORA V6: ' . $e->getMessage());
            }
        }

        return [
            'success'       => true,
            'id_usulan'     => $idUsulan,
            'nomor_usulan'  => $usulan['nomor_usulan'] ?? '-',
            'jenis_usulan'  => $usulan['jenis_usulan'] ?? '-',
            'mode_hitung'   => $mode,
            'mode_label'    => $this->modeLabel($mode),
            'jumlah_detail' => count($detailUsulan),
            'jumlah_hasil'  => count($hasil),
            'versi_hitung'  => $versiHitung,
            'tanggal'       => $tanggalNow,
            'hasil'         => $hasil,
        ];
    }


    /**
     * Patch 11: hitung ulang ranking global RKA secara benar dalam satu dataset.
     *
     * Perbedaan dari prosesUsulan():
     * - prosesUsulan() menghitung satu usulan saja.
     * - metode ini mengumpulkan seluruh RKA aktif, membentuk satu baris agregat per dokumen,
     *   lalu melakukan normalisasi MOORA secara global antar dokumen RKA.
     *
     * Tidak mengubah status workflow dan tidak menghapus histori hasil_moora.
     *
     * @param array<int,string> $statuses
     * @return array<string,mixed>
     */
    public function prosesGlobalRkaAktif(array $statuses = [WorkflowUsulanService::STATUS_MOORA_SELESAI], array $options = []): array
    {
        $statuses = array_values(array_filter(array_map('strval', $statuses)));
        if (empty($statuses)) {
            $statuses = [WorkflowUsulanService::STATUS_MOORA_SELESAI];
        }

        $db = \Config\Database::connect();
        $usulanRows = $db->table('usulan_pengadaan up')
            ->select('up.id')
            ->join('detail_usulan du', 'du.id_usulan = up.id', 'inner')
            ->where('up.jenis_usulan', 'RKA')
            ->whereIn('up.status', $statuses)
            ->groupBy('up.id')
            ->orderBy('up.tanggal_usulan', 'ASC')
            ->orderBy('up.id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($usulanRows)) {
            return [
                'success' => true,
                'processed' => 0,
                'failed' => 0,
                'message' => 'Tidak ada RKA aktif yang perlu dihitung global.',
                'items' => [],
            ];
        }

        $kriteria = $this->kriteriaModel->where('is_active', 1)
            ->orderBy('kode_kriteria', 'ASC')
            ->findAll();

        $cekBobot = $this->validateBobot($kriteria);
        if (!$cekBobot['valid']) {
            throw new RuntimeException('Total bobot kriteria aktif harus 1.00 sebelum ranking global RKA dijalankan. Total saat ini: ' . number_format($cekBobot['total_bobot'], 4));
        }

        $decisionRows = [];
        $datasets = [];
        $failed = [];

        foreach ($usulanRows as $r) {
            $idUsulan = (int) ($r['id'] ?? 0);
            if ($idUsulan < 1) {
                continue;
            }

            try {
                [$usulan, $detailUsulan] = $this->loadDataset($idUsulan);
                $generated = $this->generateNilaiDetail($usulan, $detailUsulan, $kriteria);

                // Simpan nilai otomatis C1-C5 supaya audit penilaian tetap lengkap.
                $this->saveGeneratedPenilaian($generated);

                $row = $this->buildAggregateRkaRow($usulan, $detailUsulan, $kriteria, $generated);
                $row['id_usulan'] = $idUsulan;
                $decisionRows[] = $row;
                $datasets[$idUsulan] = [
                    'usulan' => $usulan,
                    'detail_count' => count($detailUsulan),
                ];
            } catch (Throwable $e) {
                $failed[] = [
                    'id_usulan' => $idUsulan,
                    'error' => $e->getMessage(),
                ];
            }
        }

        if (empty($decisionRows)) {
            return [
                'success' => false,
                'processed' => 0,
                'failed' => count($failed),
                'message' => 'Tidak ada RKA valid yang bisa dihitung global.',
                'items' => $failed,
            ];
        }

        $costMode = (string) ($options['cost_mode'] ?? $this->settingModel->getValue('moora_cost_mode', 'standard_moora'));
        if (!in_array($costMode, ['standard_moora', 'inverse_before_weight'], true)) {
            $costMode = 'standard_moora';
        }

        $hasil = $this->calculateFromDecisionRows($decisionRows, $kriteria, 0, $costMode);
        $tanggalNow = date('Y-m-d H:i:s');
        $batchInsert = [];
        $summaryItems = [];
        $versions = [];

        foreach ($hasil as $row) {
            $idUsulan = (int) ($row['id_usulan'] ?? 0);
            if ($idUsulan < 1) {
                continue;
            }

            $versions[$idUsulan] = $this->generateVersiHitung($idUsulan);
            $versiHitung = $versions[$idUsulan];

            $payload = [
                'id_usulan'        => $idUsulan,
                'id_detail_usulan' => (int) $row['id_detail_usulan'],
                'nilai_yi'         => (float) $row['nilai_yi'],
                'ranking'          => (int) $row['ranking'],
                'tanggal_hitung'   => $tanggalNow,
                'versi_hitung'     => $versiHitung,
                'mode_hitung'      => self::MODE_RKA_AGGREGATE,
                'jenis_keputusan'  => 'RKA',
                'nilai_benefit'    => (float) $row['benefit'],
                'nilai_cost'       => (float) $row['cost'],
                'rincian_json'     => json_encode($row['rincian'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'catatan_hitung'   => 'Patch 11: Ranking global RKA dihitung dalam satu dataset antar dokumen RKA aktif.',
                'checksum_hash'    => hash('sha256', implode('|', [
                    'patch11-global-rka',
                    $idUsulan,
                    (int) $row['id_detail_usulan'],
                    $versiHitung,
                    number_format((float) $row['nilai_yi'], 8, '.', ''),
                    json_encode($row['rincian'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ])),
                'created_at'       => $tanggalNow,
                'updated_at'       => $tanggalNow,
            ];

            $batchInsert[] = $this->filterPayloadForTable('hasil_moora', $payload);
            $summaryItems[] = [
                'id_usulan' => $idUsulan,
                'nomor_usulan' => $datasets[$idUsulan]['usulan']['nomor_usulan'] ?? ('#' . $idUsulan),
                'ranking' => (int) $row['ranking'],
                'nilai_yi' => (float) $row['nilai_yi'],
                'versi_hitung' => $versiHitung,
            ];
        }

        if (empty($batchInsert)) {
            throw new RuntimeException('Ranking global RKA gagal dibuat: hasil insert kosong.');
        }

        $db->transBegin();
        try {
            $this->hasilMooraModel->insertBatch($batchInsert);
            foreach ($summaryItems as $item) {
                $this->saveEngineLog(
                    (int) $item['id_usulan'],
                    self::MODE_RKA_AGGREGATE,
                    (int) $item['versi_hitung'],
                    (int) ($datasets[(int) $item['id_usulan']]['detail_count'] ?? 0),
                    1,
                    $tanggalNow,
                    $options['engine_log_role'] ?? 'administrator_global_rka_patch_11'
                );
            }

            $this->settingModel->setValue('moora_global_rka_last_run', $tanggalNow, [
                'setting_label' => 'Terakhir Ranking Global RKA',
                'setting_group' => 'moora',
                'setting_type'  => 'text',
                'description'   => 'Waktu terakhir Patch 11 menjalankan ranking global antar dokumen RKA aktif.',
            ]);

            $db->transCommit();
        } catch (Throwable $e) {
            $db->transRollback();
            throw new RuntimeException('Transaksi ranking global RKA gagal: ' . $e->getMessage());
        }

        try {
            $this->logModel->insert([
                'id_user' => $this->currentUserId(),
                'aktivitas' => 'Patch 11 Global RKA Ranking',
                'modul' => 'MOORA Engine',
                'keterangan' => 'Ranking global RKA dijalankan untuk ' . count($summaryItems) . ' dokumen. Gagal: ' . count($failed) . '.',
                'ip_address' => is_cli() ? '127.0.0.1' : (service('request')->getIPAddress() ?? '127.0.0.1'),
                'created_at' => $tanggalNow,
            ]);
        } catch (Throwable $e) {
            log_message('error', 'Gagal mencatat log Patch 11 Global RKA Ranking: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'processed' => count($summaryItems),
            'failed' => count($failed),
            'items' => array_merge($summaryItems, $failed),
        ];
    }

    /**
     * Simpan nilai C1-C5 otomatis untuk banyak detail.
     *
     * @param array<int,array<int,float>> $generated
     */
    protected function saveGeneratedPenilaian(array $generated): void
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('penilaian')) {
            throw new RuntimeException('Tabel penilaian belum tersedia.');
        }

        $now = date('Y-m-d H:i:s');
        $fields = array_flip($db->getFieldNames('penilaian'));

        foreach ($generated as $idDetail => $nilaiPerKriteria) {
            foreach ($nilaiPerKriteria as $idKriteria => $nilai) {
                $where = [
                    'id_detail_usulan' => (int) $idDetail,
                    'id_kriteria'      => (int) $idKriteria,
                ];

                $existing = $db->table('penilaian')
                    ->select('id')
                    ->where($where)
                    ->get()
                    ->getRowArray();

                if ($existing) {
                    $payloadUpdate = array_intersect_key([
                        'nilai'      => (float) $nilai,
                        'updated_at' => $now,
                    ], $fields);

                    $db->table('penilaian')
                        ->where('id', (int) $existing['id'])
                        ->update($payloadUpdate);
                } else {
                    $payloadInsert = array_intersect_key(array_merge($where, [
                        'nilai'      => (float) $nilai,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]), $fields);

                    $db->table('penilaian')->insert($payloadInsert);
                }
            }
        }
    }

    /**
     * V6: konsolidasi hasil historis tanpa mengubah status workflow.
     * Dipakai untuk menyelaraskan data hasil lama agar RKA menjadi rka_aggregate
     * dan Pesan Cepat tetap item_based.
     *
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    public function konsolidasiHistoris(int $limit = 100, array $options = []): array
    {
        $limit = max(1, min(500, $limit));
        $db = \Config\Database::connect();

        $statusKandidat = $options['statuses'] ?? [
            WorkflowUsulanService::STATUS_DIVERIFIKASI,
            WorkflowUsulanService::STATUS_MOORA_SELESAI,
            WorkflowUsulanService::STATUS_MENUNGGU_DIREKTUR_BIDANG,
            WorkflowUsulanService::STATUS_MENUNGGU_DIREKTUR_UTAMA,
            WorkflowUsulanService::STATUS_MENUNGGU_DIREKTUR_UMUM,
            WorkflowUsulanService::STATUS_DISPOSISI_PENGADAAN,
            WorkflowUsulanService::STATUS_DIPROSES_PENGADAAN,
            WorkflowUsulanService::STATUS_MENUNGGU_PENERIMAAN,
            WorkflowUsulanService::STATUS_SELESAI,
            'direkomendasikan',
            'disetujui',
            'selesai_pengadaan',
            'direalisasi',
        ];

        $rows = $db->table('usulan_pengadaan up')
            ->select('up.id, up.nomor_usulan, up.jenis_usulan, up.status, COUNT(DISTINCT du.id) AS jumlah_detail, COUNT(DISTINCT hm.id) AS jumlah_hasil')
            ->join('detail_usulan du', 'du.id_usulan = up.id', 'inner')
            ->join('hasil_moora hm', 'hm.id_usulan = up.id', 'left')
            ->whereIn('up.status', $statusKandidat)
            ->groupBy('up.id')
            ->orderBy('up.updated_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        $summary = [
            'processed' => 0,
            'failed'    => 0,
            'skipped'   => 0,
            'items'     => [],
        ];

        foreach ($rows as $row) {
            try {
                $result = $this->prosesUsulan((int) $row['id'], [
                    'allow_any_status' => true,
                    'skip_workflow'    => true,
                    'skip_notification'=> true,
                    'auto_generate'    => true,
                    'cost_mode'        => 'standard_moora',
                    'engine_log_role'  => $options['engine_log_role'] ?? 'administrator_maintenance',
                ]);

                $summary['processed']++;
                $summary['items'][] = [
                    'id_usulan'     => (int) $row['id'],
                    'nomor_usulan'  => $row['nomor_usulan'] ?? '-',
                    'jenis_usulan'  => $row['jenis_usulan'] ?? '-',
                    'status'        => $row['status'] ?? '-',
                    'mode_hitung'   => $result['mode_hitung'] ?? '-',
                    'jumlah_detail' => $result['jumlah_detail'] ?? 0,
                    'jumlah_hasil'  => $result['jumlah_hasil'] ?? 0,
                    'versi_hitung'  => $result['versi_hitung'] ?? null,
                    'success'       => true,
                ];
            } catch (Throwable $e) {
                $summary['failed']++;
                $summary['items'][] = [
                    'id_usulan'    => (int) $row['id'],
                    'nomor_usulan' => $row['nomor_usulan'] ?? '-',
                    'jenis_usulan' => $row['jenis_usulan'] ?? '-',
                    'status'       => $row['status'] ?? '-',
                    'success'      => false,
                    'error'        => $e->getMessage(),
                ];
            }
        }

        return $summary;
    }

    /**
     * V6: data ringkas audit engine untuk dashboard audit tanpa membuat model baru wajib.
     */
    public function auditEngineRows(int $limit = 100): array
    {
        $limit = max(1, min(500, $limit));
        $db = \Config\Database::connect();

        if (!$db->tableExists('moora_engine_log')) {
            return [];
        }

        return $db->table('moora_engine_log mel')
            ->select('mel.*, up.nomor_usulan, up.jenis_usulan, up.status, users.nama_lengkap AS processed_name')
            ->join('usulan_pengadaan up', 'up.id = mel.id_usulan', 'left')
            ->join('users', 'users.id = mel.processed_by', 'left')
            ->orderBy('mel.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * V6: ringkasan kompatibilitas hasil terbaru terhadap rancangan final.
     */
    public function auditModeSummary(): array
    {
        $db = \Config\Database::connect();
        $summary = [
            'rka_aggregate'       => 0,
            'rka_masih_item'      => 0,
            'pesan_cepat_item'    => 0,
            'pesan_cepat_lainnya' => 0,
            'belum_ada_hasil'     => 0,
        ];

        $latest = $db->table('hasil_moora')
            ->select('id_usulan, MAX(versi_hitung) AS versi_terakhir')
            ->groupBy('id_usulan');

        $rows = $db->table('usulan_pengadaan up')
            ->select('up.id, up.jenis_usulan, hm.mode_hitung')
            ->join('(' . $latest->getCompiledSelect(false) . ') latest', 'latest.id_usulan = up.id', 'left')
            ->join('hasil_moora hm', 'hm.id_usulan = up.id AND hm.versi_hitung = latest.versi_terakhir', 'left')
            ->where('up.status !=', WorkflowUsulanService::STATUS_DRAFT)
            ->groupBy('up.id, up.jenis_usulan, hm.mode_hitung')
            ->get()
            ->getResultArray();

        foreach ($rows as $row) {
            $jenis = strtolower(str_replace([' ', '_', '-'], '', (string) ($row['jenis_usulan'] ?? 'RKA')));
            $mode = $this->normalizeModeAlias((string) ($row['mode_hitung'] ?? ''));

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
     * V6: normalisasi alias mode legacy supaya audit tidak lagi salah baca.
     */
    protected function normalizeModeAlias(string $mode): string
    {
        $mode = trim($mode);
        $legacyRka = ['aggregate_per_usulan', 'rka', 'rka_aggregated', 'aggregate', 'aggregate_rka'];
        if (in_array($mode, $legacyRka, true)) {
            return self::MODE_RKA_AGGREGATE;
        }

        $legacyItem = ['item_per_detail', 'per_item', 'detail_based'];
        if (in_array($mode, $legacyItem, true)) {
            return self::MODE_ITEM_BASED;
        }

        return $mode;
    }

    /** Kompatibilitas lama. */
    public function buildMatriks(array $detailUsulan, array $penilaian): array
    {
        $matriks = [];
        foreach ($penilaian as $p) {
            $matriks[(int) $p['id_detail_usulan']][(int) $p['id_kriteria']] = (float) $p['nilai'];
        }
        foreach ($detailUsulan as $d) {
            $matriks[(int) $d['id']] = $matriks[(int) $d['id']] ?? [];
        }
        return $matriks;
    }

    /** Kompatibilitas lama. */
    public function validateKelengkapan(array $detailUsulan, array $kriteria, array $matriks): array
    {
        $kurang = [];
        foreach ($detailUsulan as $d) {
            foreach ($kriteria as $k) {
                $idDetail = (int) $d['id'];
                $idKriteria = (int) $k['id'];
                if (!isset($matriks[$idDetail][$idKriteria]) || $matriks[$idDetail][$idKriteria] === '') {
                    $kurang[] = [
                        'detail_id'  => $idDetail,
                        'alternatif' => $d['nama_alternatif'] ?? $d['kode_alternatif'] ?? 'Alternatif',
                        'kriteria'   => $k['nama_kriteria'] ?? '-',
                    ];
                }
            }
        }
        return ['valid' => empty($kurang), 'kurang' => $kurang];
    }

    /** Kompatibilitas lama. */
    public function hitungPembagi(array $detailUsulan, array $kriteria, array $matriks): array
    {
        $rows = [];
        foreach ($detailUsulan as $d) {
            $criteriaValues = [];
            foreach ($kriteria as $k) {
                $criteriaValues[(int) $k['id']] = (float) ($matriks[(int) $d['id']][(int) $k['id']] ?? 0);
            }
            $rows[] = ['criteria_values' => $criteriaValues];
        }
        return $this->buildPembagiFromRows($rows, $kriteria);
    }

    /** Kompatibilitas lama. */
    public function normalisasi(array $detailUsulan, array $kriteria, array $matriks, array $pembagi): array
    {
        $normalisasi = [];
        foreach ($detailUsulan as $d) {
            $idDetail = (int) $d['id'];
            foreach ($kriteria as $k) {
                $idKriteria = (int) $k['id'];
                $nilai = (float) ($matriks[$idDetail][$idKriteria] ?? 0);
                $normalisasi[$idDetail][$idKriteria] = $nilai / ((float) ($pembagi[$idKriteria] ?? 1) ?: 1);
            }
        }
        return $normalisasi;
    }

    /** Kompatibilitas lama. */
    public function hitungHasil(array $detailUsulan, array $kriteria, array $normalisasi, int $idUsulan, string $costMode = 'standard_moora'): array
    {
        $rows = [];
        foreach ($detailUsulan as $d) {
            $criteriaValues = [];
            foreach ($kriteria as $k) {
                $criteriaValues[(int) $k['id']] = (float) ($normalisasi[(int) $d['id']][(int) $k['id']] ?? 0);
            }
            $rows[] = [
                'id_detail_usulan' => (int) $d['id'],
                'kode_alternatif'  => $d['kode_alternatif'] ?? '-',
                'nama_alternatif'  => $d['nama_alternatif'] ?? '-',
                'jumlah'           => $d['jumlah'] ?? 0,
                'satuan'           => $d['satuan'] ?? '',
                'total_estimasi'   => $d['total_estimasi'] ?? 0,
                'criteria_values'  => $criteriaValues,
                'already_normalized' => true,
            ];
        }
        return $this->calculateFromDecisionRows($rows, $kriteria, $idUsulan, $costMode, true);
    }

    protected function generateVersiHitung(int $idUsulan): int
    {
        $candidate = (int) (microtime(true) * 1000);
        $last = $this->hasilMooraModel
            ->selectMax('versi_hitung', 'max_versi')
            ->where('id_usulan', $idUsulan)
            ->first();

        $lastVersi = (int) ($last['max_versi'] ?? 0);
        return max($candidate, $lastVersi + 1);
    }

    /** @return array{0:array,1:array,2:array} */
    protected function loadDataset(int $idUsulan): array
    {
        $usulan = $this->usulanModel->find($idUsulan);
        if (!$usulan) {
            throw new RuntimeException('Usulan tidak ditemukan.');
        }

        // PATCH AUTO FIX FULL SYSTEM:
        // Detail usulan wajib ada sebagai anchor FK hasil_moora. Jika data lama/bug membuatnya kosong,
        // sistem membuat fallback non-destruktif agar engine tetap berjalan dan tidak runtime error.
        $this->autoFixService->ensureDetailUsulan($idUsulan, $usulan);

        $detailUsulan = $this->detailUsulanModel->getDetailByUsulan($idUsulan);
        if (empty($detailUsulan)) {
            throw new RuntimeException('Detail usulan belum tersedia dan auto fix gagal membuat detail. Jalankan SQL patch AUTO FIX lalu coba lagi.');
        }

        $kriteria = $this->kriteriaModel->where('is_active', 1)
            ->orderBy('kode_kriteria', 'ASC')
            ->findAll();
        if (empty($kriteria)) {
            throw new RuntimeException('Kriteria aktif belum tersedia.');
        }

        return [$usulan, $detailUsulan, $kriteria];
    }

    protected function generateNilaiDetail(array $usulan, array $detailUsulan, array $kriteria): array
    {
        $generated = [];
        foreach ($detailUsulan as $detail) {
            $idDetail = (int) $detail['id'];
            $generated[$idDetail] = [];
            foreach ($kriteria as $k) {
                $generated[$idDetail][(int) $k['id']] = $this->nilaiOtomatisPerKriteria($usulan, $detail, $k);
            }
        }
        return $generated;
    }

    protected function nilaiOtomatisPerKriteria(array $usulan, array $detail, array $kriteria): float
    {
        $kode = strtoupper((string) ($kriteria['kode_kriteria'] ?? ''));
        $nama = strtolower((string) ($kriteria['nama_kriteria'] ?? ''));
        $key = $kode !== '' ? $kode : $nama;

        return match (true) {
            $key === 'C1' || str_contains($nama, 'urgensi') => $this->scoreUrgensi($usulan, $detail),
            $key === 'C2' || str_contains($nama, 'biaya') => $this->scoreBiaya($detail),
            $key === 'C3' || str_contains($nama, 'rusak') || str_contains($nama, 'kerusakan') || str_contains($nama, 'kondisi') => $this->scoreKerusakan($detail),
            $key === 'C4' || str_contains($nama, 'frekuensi') || str_contains($nama, 'penggunaan') => $this->scoreFrekuensi($detail),
            $key === 'C5' || str_contains($nama, 'dampak') || str_contains($nama, 'operasional') => $this->scoreDampak($usulan, $detail),
            default => 5.0,
        };
    }

    protected function scoreUrgensi(array $usulan, array $detail): float
    {
        $jenis = strtolower((string) ($usulan['jenis_usulan'] ?? ''));
        $text = $this->joinedText($usulan, $detail);
        $score = str_contains($jenis, 'pesan') ? 9.0 : 6.0;

        foreach (['darurat', 'emergency', 'urgent', 'mendesak', 'segera', 'gangguan', 'bocor', 'mati', 'rusak berat'] as $kw) {
            if (str_contains($text, $kw)) {
                $score += 1.5;
                break;
            }
        }

        if ((int) ($detail['stok'] ?? 0) <= 0) {
            $score += 1.0;
        } elseif ((int) ($detail['stok'] ?? 0) <= (int) ($detail['stok_minimum'] ?? 0)) {
            $score += 0.75;
        }

        return $this->clamp($score);
    }

    protected function scoreBiaya(array $detail): float
    {
        $jumlah = max(1, (int) ($detail['jumlah'] ?? 1));
        $harga = (float) ($detail['estimasi_harga_satuan'] ?? $detail['estimasi_harga'] ?? 0);
        $total = $jumlah * $harga;

        return match (true) {
            $total <= 500000       => 1.0,
            $total <= 1000000      => 2.0,
            $total <= 2500000      => 3.0,
            $total <= 5000000      => 4.0,
            $total <= 10000000     => 5.0,
            $total <= 25000000     => 6.0,
            $total <= 50000000     => 7.0,
            $total <= 75000000     => 8.0,
            $total <= 100000000    => 9.0,
            default                => 10.0,
        };
    }

    protected function scoreKerusakan(array $detail): float
    {
        $kondisi = strtolower((string) ($detail['kondisi_barang'] ?? 'baik'));
        $score = match ($kondisi) {
            'tidak_layak' => 10.0,
            'rusak'       => 9.0,
            'diperbaiki'  => 7.0,
            default       => 2.0,
        };

        $stok = (int) ($detail['stok'] ?? 0);
        $min  = (int) ($detail['stok_minimum'] ?? 0);
        if ($stok <= 0) {
            $score = max($score, 7.0);
        } elseif ($min > 0 && $stok <= $min) {
            $score = max($score, 6.0);
        }

        return $this->clamp($score);
    }

    protected function scoreFrekuensi(array $detail): float
    {
        $movement = strtolower((string) ($detail['movement_type'] ?? 'slow_moving'));
        $score = match ($movement) {
            'first_moving' => 9.0,
            'non_moving'   => 3.0,
            default        => 6.0,
        };

        $jumlah = (int) ($detail['jumlah'] ?? 1);
        if ($jumlah >= 20) {
            $score += 1.0;
        } elseif ($jumlah >= 5) {
            $score += 0.5;
        }

        return $this->clamp($score);
    }

    protected function scoreDampak(array $usulan, array $detail): float
    {
        $text = $this->joinedText($usulan, $detail);
        $score = 6.0;

        foreach (['distribusi air', 'air bersih', 'pompa', 'pipa', 'genset', 'tekanan air', 'operasional', 'produksi'] as $kw) {
            if (str_contains($text, $kw)) {
                $score += 1.2;
            }
        }

        if (str_contains($text, 'cadangan listrik') || str_contains($text, 'gangguan operasional')) {
            $score += 1.0;
        }

        return $this->clamp($score);
    }

    protected function joinedText(array $usulan, array $detail): string
    {
        return strtolower(implode(' ', array_filter([
            $usulan['unit_pengusul'] ?? '',
            $usulan['catatan_pengusul'] ?? '',
            $detail['nama_alternatif'] ?? '',
            $detail['kategori_barang'] ?? '',
            $detail['spesifikasi'] ?? '',
            $detail['alasan_kebutuhan'] ?? '',
        ])));
    }

    protected function clamp(float $value, float $min = 1.0, float $max = 10.0): float
    {
        return round(max($min, min($max, $value)), 4);
    }

    protected function buildDecisionRows(array $usulan, array $detailUsulan, array $kriteria, array $generated, string $mode): array
    {
        if ($mode === self::MODE_RKA_AGGREGATE) {
            return [$this->buildAggregateRkaRow($usulan, $detailUsulan, $kriteria, $generated)];
        }

        $rows = [];
        foreach ($detailUsulan as $detail) {
            $idDetail = (int) $detail['id'];
            $rows[] = [
                'id_usulan'        => (int) ($usulan['id'] ?? 0),
                'id_detail_usulan' => $idDetail,
                'kode_alternatif'  => $detail['kode_alternatif'] ?? '-',
                'nama_alternatif'  => $detail['nama_alternatif'] ?? '-',
                'jumlah'           => (int) ($detail['jumlah'] ?? 0),
                'satuan'           => $detail['satuan'] ?? '',
                'total_estimasi'   => (float) (($detail['total_estimasi'] ?? 0) ?: ((float) ($detail['estimasi_harga_satuan'] ?? 0) * (int) ($detail['jumlah'] ?? 1))),
                'criteria_values'  => $generated[$idDetail] ?? [],
                'source_details'   => [$idDetail],
            ];
        }

        return $rows;
    }

    protected function buildAggregateRkaRow(array $usulan, array $detailUsulan, array $kriteria, array $generated): array
    {
        $anchor = (int) ($detailUsulan[0]['id'] ?? 0);
        $criteriaValues = [];
        $totalEstimasi = 0.0;
        $sourceDetails = [];

        foreach ($detailUsulan as $detail) {
            $totalEstimasi += (float) (($detail['total_estimasi'] ?? 0) ?: ((float) ($detail['estimasi_harga_satuan'] ?? 0) * (int) ($detail['jumlah'] ?? 1)));
            $sourceDetails[] = (int) $detail['id'];
        }

        foreach ($kriteria as $k) {
            $idKriteria = (int) $k['id'];
            $kode = strtoupper((string) ($k['kode_kriteria'] ?? ''));
            $nama = strtolower((string) ($k['nama_kriteria'] ?? ''));
            $values = [];

            foreach ($detailUsulan as $detail) {
                $values[] = (float) ($generated[(int) $detail['id']][$idKriteria] ?? 0);
            }

            if (empty($values)) {
                $criteriaValues[$idKriteria] = 0.0;
                continue;
            }

            if ($kode === 'C1' || str_contains($nama, 'urgensi') || $kode === 'C5' || str_contains($nama, 'dampak')) {
                $criteriaValues[$idKriteria] = max($values);
            } elseif ($kode === 'C2' || str_contains($nama, 'biaya')) {
                // RKA mengikuti rancangan final: biaya agregat = SUM biaya per barang.
                // Karena nilai per barang sudah berupa skala 1-10, hasil SUM menggambarkan beban biaya dokumen.
                $criteriaValues[$idKriteria] = array_sum($values);
            } elseif ($kode === 'C3' || str_contains($nama, 'rusak') || str_contains($nama, 'kerusakan') || str_contains($nama, 'kondisi')) {
                $criteriaValues[$idKriteria] = array_sum($values) / count($values);
            } elseif ($kode === 'C4' || str_contains($nama, 'frekuensi') || str_contains($nama, 'penggunaan')) {
                // RKA mengikuti rancangan final: frekuensi agregat = SUM kebutuhan/frekuensi barang.
                $criteriaValues[$idKriteria] = array_sum($values);
            } else {
                $criteriaValues[$idKriteria] = array_sum($values) / count($values);
            }
        }

        return [
            'id_usulan'        => (int) ($usulan['id'] ?? 0),
            'id_detail_usulan' => $anchor,
            'kode_alternatif'  => $usulan['nomor_usulan'] ?? ('RKA-' . ($usulan['id'] ?? '-')),
            'nama_alternatif'  => 'Agregasi Dokumen RKA - ' . ($usulan['unit_pengusul'] ?? '-'),
            'jumlah'           => count($detailUsulan),
            'satuan'           => 'dokumen',
            'total_estimasi'   => $totalEstimasi,
            'criteria_values'  => $criteriaValues,
            'source_details'   => $sourceDetails,
            'aggregate_meta'   => [
                'jenis_agregasi' => [
                    'C1' => 'MAX',
                    'C2' => 'SUM',
                    'C3' => 'AVG',
                    'C4' => 'SUM',
                    'C5' => 'MAX',
                ],
                'jumlah_barang' => count($detailUsulan),
            ],
        ];
    }

    protected function calculateFromDecisionRows(array $rows, array $kriteria, int $idUsulan, string $costMode = 'standard_moora', bool $rowsAlreadyNormalized = false): array
    {
        $pembagi = $rowsAlreadyNormalized ? [] : $this->buildPembagiFromRows($rows, $kriteria);
        $hasil = [];

        foreach ($rows as $row) {
            $benefit = 0.0;
            $cost = 0.0;
            $rincian = [];

            foreach ($kriteria as $k) {
                $idKriteria = (int) $k['id'];
                $raw = (float) ($row['criteria_values'][$idKriteria] ?? 0);
                $r = $rowsAlreadyNormalized ? $raw : ($raw / ((float) ($pembagi[$idKriteria] ?? 1) ?: 1));
                $bobot = (float) ($k['bobot'] ?? 0);
                $jenis = strtolower((string) ($k['jenis'] ?? 'benefit'));

                if ($jenis === 'cost') {
                    $nilaiCost = $costMode === 'inverse_before_weight'
                        ? ($r > 0 ? 1 / $r : 0)
                        : $r;
                    $terbobot = $nilaiCost * $bobot;
                    $cost += $terbobot;
                } else {
                    $terbobot = $r * $bobot;
                    $benefit += $terbobot;
                }

                $rincian[$idKriteria] = [
                    'kode_kriteria' => $k['kode_kriteria'] ?? '',
                    'nama_kriteria' => $k['nama_kriteria'] ?? '',
                    'jenis'         => $jenis,
                    'bobot'         => $bobot,
                    'nilai_awal'    => $raw,
                    'pembagi'       => $rowsAlreadyNormalized ? 1 : ($pembagi[$idKriteria] ?? 1),
                    'normalisasi'   => $r,
                    'terbobot'      => $terbobot,
                ];
            }

            $hasil[] = [
                'id_usulan'        => (int) ($row['id_usulan'] ?? $idUsulan),
                'id_detail_usulan' => (int) $row['id_detail_usulan'],
                'kode_alternatif'  => $row['kode_alternatif'] ?? '-',
                'nama_alternatif'  => $row['nama_alternatif'] ?? '-',
                'jumlah'           => $row['jumlah'] ?? 0,
                'satuan'           => $row['satuan'] ?? '',
                'total_estimasi'   => $row['total_estimasi'] ?? 0,
                'benefit'          => $benefit,
                'cost'             => $cost,
                'nilai_yi'         => $benefit - $cost,
                'rincian'          => [
                    'kriteria'       => $rincian,
                    'source_details' => $row['source_details'] ?? [],
                    'aggregate_meta' => $row['aggregate_meta'] ?? null,
                ],
            ];
        }

        usort($hasil, static fn ($a, $b) => $b['nilai_yi'] <=> $a['nilai_yi']);
        foreach ($hasil as $i => $row) {
            $hasil[$i]['ranking'] = $i + 1;
        }

        return $hasil;
    }

    protected function buildPembagiFromRows(array $rows, array $kriteria): array
    {
        $pembagi = [];
        foreach ($kriteria as $k) {
            $idKriteria = (int) $k['id'];
            $sumKuadrat = 0.0;
            foreach ($rows as $row) {
                $nilai = (float) ($row['criteria_values'][$idKriteria] ?? 0);
                $sumKuadrat += $nilai ** 2;
            }

            if (count($rows) === 1) {
                $raw = sqrt($sumKuadrat);
                $skalaMax = (float) ($k['skala_max'] ?? 10);
                $pembagi[$idKriteria] = max($raw, $skalaMax, 1.0);
            } else {
                $pembagi[$idKriteria] = $sumKuadrat > 0 ? sqrt($sumKuadrat) : 1.0;
            }
        }
        return $pembagi;
    }

    protected function loadPenilaianMap(array $detailUsulan, array $kriteria): array
    {
        $detailIds = array_map(static fn ($row) => (int) $row['id'], $detailUsulan);
        $rows = $this->penilaianModel->whereIn('id_detail_usulan', $detailIds)->findAll();
        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['id_detail_usulan']][(int) $row['id_kriteria']] = (float) $row['nilai'];
        }
        return $map;
    }

    protected function assertNilaiLengkap(array $detailUsulan, array $kriteria, array $map): void
    {
        $cek = $this->validateKelengkapan($detailUsulan, $kriteria, $map);
        if (!$cek['valid']) {
            $pesan = [];
            foreach (array_slice($cek['kurang'], 0, 5) as $item) {
                $pesan[] = ($item['alternatif'] ?? '-') . ' - ' . ($item['kriteria'] ?? '-');
            }
            throw new RuntimeException('Penilaian belum lengkap: ' . implode(', ', $pesan));
        }
    }

    protected function filterPayloadForTable(string $table, array $payload): array
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($table);
        return array_intersect_key($payload, array_flip($fields));
    }

    protected function saveEngineLog(int $idUsulan, string $mode, int $versiHitung, int $jumlahDetail, int $jumlahHasil, string $createdAt, ?string $processedRole = null): void
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('moora_engine_log')) {
            return;
        }

        $payload = [
            'id_usulan'      => $idUsulan,
            'mode_hitung'    => $mode,
            'versi_hitung'   => $versiHitung,
            'jumlah_detail'  => $jumlahDetail,
            'jumlah_hasil'   => $jumlahHasil,
            'processed_by'   => $this->currentUserId(),
            'processed_role' => (string) ($processedRole ?: (session()->get('role') ?: 'gudang')),
            'checksum_hash'  => hash('sha256', implode('|', [$idUsulan, $mode, $versiHitung, $jumlahDetail, $jumlahHasil])),
            'catatan_hitung' => $mode === self::MODE_RKA_AGGREGATE
                ? 'V6 audit: RKA dihitung sebagai satu keputusan agregat dokumen.'
                : 'V6 audit: Pesan Cepat dihitung per item/detail barang.',
            'created_at'     => $createdAt,
        ];

        $fields = $db->getFieldNames('moora_engine_log');
        $db->table('moora_engine_log')->insert(array_intersect_key($payload, array_flip($fields)));
    }

    protected function currentUserId(): ?int
    {
        $id = session()->get('id_user') ?? session()->get('id');
        return $id ? (int) $id : null;
    }
}
