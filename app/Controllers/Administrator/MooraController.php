<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\HasilMooraModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\SettingModel;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraService;
use App\Services\MooraResultQueryService;
use Throwable;

class MooraController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected HasilMooraModel $hasilModel;
    protected KriteriaModel $kriteriaModel;
    protected PenilaianModel $penilaianModel;
    protected SettingModel $settingModel;
    protected MooraService $mooraService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        helper(['form', 'url']);

        $this->usulanModel    = new UsulanPengadaanModel();
        $this->hasilModel     = new HasilMooraModel();
        $this->kriteriaModel  = new KriteriaModel();
        $this->penilaianModel = new PenilaianModel();
        $this->settingModel   = new SettingModel();
        $this->mooraService   = new MooraService();
        $this->mooraQuery     = new MooraResultQueryService();
    }

    /**
     * V4 FINAL: halaman Admin menjadi monitoring/training, bukan engine proses operasional.
     */
    public function index()
    {
        $statusFilter = trim((string) $this->request->getGet('status'));
        $tanggalAwal  = trim((string) $this->request->getGet('tanggal_awal'));
        $tanggalAkhir = trim((string) $this->request->getGet('tanggal_akhir'));

        $builder = $this->usulanModel
            ->select("usulan_pengadaan.*,
                users.nama_lengkap AS nama_pengusul,
                COUNT(DISTINCT detail_usulan.id) AS jumlah_item,
                COUNT(DISTINCT penilaian.id) AS jumlah_nilai,
                MAX(hasil_moora.tanggal_hitung) AS terakhir_dihitung,
                MAX(hasil_moora.versi_hitung) AS versi_terakhir")
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->join('detail_usulan', 'detail_usulan.id_usulan = usulan_pengadaan.id', 'left')
            ->join('penilaian', 'penilaian.id_detail_usulan = detail_usulan.id', 'left')
            ->join('hasil_moora', 'hasil_moora.id_usulan = usulan_pengadaan.id', 'left')
            ->where('usulan_pengadaan.status !=', 'draft')
            ->groupBy('usulan_pengadaan.id')
            ->orderBy('usulan_pengadaan.tanggal_usulan', 'DESC')
            ->orderBy('usulan_pengadaan.id', 'DESC');

        if ($statusFilter !== '') {
            $builder->where('usulan_pengadaan.status', $statusFilter);
        }
        if ($tanggalAwal !== '') {
            $builder->where('usulan_pengadaan.tanggal_usulan >=', $tanggalAwal);
        }
        if ($tanggalAkhir !== '') {
            $builder->where('usulan_pengadaan.tanggal_usulan <=', $tanggalAkhir);
        }

        $usulan = $builder->findAll();
        $kriteria = $this->kriteriaModel->where('is_active', 1)->orderBy('kode_kriteria', 'ASC')->findAll();
        $cekBobot = $this->mooraService->validateBobot($kriteria);

        $totalUsulan = count($usulan);
        $sudahHitung = 0;
        $siapHitung = 0;
        foreach ($usulan as $row) {
            if (!empty($row['terakhir_dihitung'])) {
                $sudahHitung++;
            }
            if ((int) ($row['jumlah_item'] ?? 0) > 0) {
                $siapHitung++;
            }
        }

        return view('Administrator/moora/index', [
            'title'             => 'Training & Audit MOORA V6',
            'usulan'            => $usulan,
            'kriteria'          => $kriteria,
            'cekBobot'          => $cekBobot,
            'totalUsulan'       => $totalUsulan,
            'sudahHitung'       => $sudahHitung,
            'siapHitung'        => $siapHitung,
            'filter'            => [
                'status'        => $statusFilter,
                'tanggal_awal'  => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
            ],
            'setting'           => $this->settingModel->getMap('moora'),
            'auditSummary'      => $this->mooraService->auditModeSummary(),
            'defaultStatusList' => ['diverifikasi'],
            'adminTrainingMode' => true,
        ]);
    }

    public function trigger($idUsulan)
    {
        return redirect()
            ->to(site_url('administrator/kalkulasi-moora'))
            ->with('warning', 'V4 FINAL: proses MOORA operasional sudah dipindahkan ke Gudang. Admin hanya monitoring/training bobot dan hasil. Gunakan menu Gudang > Engine MOORA.');
    }

    public function batch()
    {
        return redirect()
            ->to(site_url('administrator/kalkulasi-moora'))
            ->with('warning', 'V4 FINAL: batch MOORA operasional dikunci dari Admin. Proses final dilakukan oleh role Gudang agar sesuai rancangan workflow.');
    }

    /**
     * V5: Maintenance non-destruktif untuk menyelaraskan hasil lama.
     * Tidak mengubah status workflow dan tidak membuat notifikasi ke Manajer.
     */
    public function recalculateV5()
    {
        $limit = (int) ($this->request->getPost('limit') ?? 100);
        $limit = max(1, min(500, $limit));

        try {
            $summary = $this->mooraService->konsolidasiHistoris($limit, [
                'engine_log_role' => 'administrator_maintenance',
            ]);

            $this->logAktivitas(
                $this->currentUserId(),
                'Patch V6 Bugfix Audit Engine',
                'Administrator',
                'Konsolidasi historis V6 dijalankan. Berhasil: ' . ($summary['processed'] ?? 0) . ', gagal: ' . ($summary['failed'] ?? 0) . '.'
            );

            session()->setFlashdata('v6_recalculate_result', $summary);

            return redirect()
                ->to(site_url('administrator/moora-audit'))
                ->with('success', 'Konsolidasi V6 selesai. Berhasil: ' . ($summary['processed'] ?? 0) . ', gagal: ' . ($summary['failed'] ?? 0) . '.');
        } catch (Throwable $e) {
            return redirect()
                ->to(site_url('administrator/kalkulasi-moora'))
                ->with('error', 'Konsolidasi V6 gagal: ' . $e->getMessage());
        }
    }

    /** Patch V6 route alias. */
    public function recalculateV6()
    {
        return $this->recalculateV5();
    }

    public function globalRkaRecalculate()
    {
        try {
            $summary = $this->mooraService->prosesGlobalRkaAktif(['moora_selesai'], [
                'engine_log_role' => 'administrator_global_rka_patch_11',
                'cost_mode'       => 'standard_moora',
            ]);

            session()->setFlashdata('patch11_global_rka_result', $summary);

            return redirect()
                ->to(site_url('administrator/kalkulasi-moora'))
                ->with('success', 'Ranking global RKA selesai. Diproses: ' . (int) ($summary['processed'] ?? 0) . ', gagal: ' . (int) ($summary['failed'] ?? 0) . '.');
        } catch (Throwable $e) {
            return redirect()
                ->to(site_url('administrator/kalkulasi-moora'))
                ->with('error', 'Ranking global RKA gagal: ' . $e->getMessage());
        }
    }

    public function hasil($idUsulan)
    {
        $usulan = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->where('usulan_pengadaan.id', (int) $idUsulan)
            ->first();

        if (!$usulan) {
            return redirect()->to(site_url('administrator/kalkulasi-moora'))
                ->with('error', 'Usulan tidak ditemukan.');
        }

        // PATCH 9: hasil aktif memakai single source v_latest_moora_context.
        // Histori audit tetap dibaca dari hasil_moora langsung untuk kebutuhan Admin.
        $hasil = $this->mooraQuery->activeLatestByUsulan((int) $idUsulan);

        $riwayatHasil = $this->hasilModel
            ->select('hasil_moora.*, detail_usulan.jumlah, detail_usulan.estimasi_harga_satuan, detail_usulan.total_estimasi, detail_usulan.alasan_kebutuhan, alternatif.kode_alternatif, alternatif.nama_alternatif, alternatif.kategori_barang, alternatif.satuan')
            ->join('detail_usulan', 'detail_usulan.id = hasil_moora.id_detail_usulan', 'left')
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
            ->where('hasil_moora.id_usulan', (int) $idUsulan)
            ->orderBy('hasil_moora.versi_hitung', 'DESC')
            ->orderBy('hasil_moora.ranking', 'ASC')
            ->findAll();

        $kriteria = $this->kriteriaModel->where('is_active', 1)
            ->orderBy('kode_kriteria', 'ASC')
            ->findAll();

        $penilaianRows = [];
        if (!empty($hasil)) {
            $detailIds = array_values(array_unique(array_map('intval', array_column($hasil, 'id_detail_usulan'))));
            if (!empty($detailIds)) {
                $penilaianRows = $this->penilaianModel
                    ->select('penilaian.*, kriteria.kode_kriteria, kriteria.nama_kriteria, kriteria.jenis, kriteria.bobot')
                    ->join('kriteria', 'kriteria.id = penilaian.id_kriteria', 'left')
                    ->whereIn('penilaian.id_detail_usulan', $detailIds)
                    ->orderBy('kriteria.kode_kriteria', 'ASC')
                    ->findAll();
            }
        }

        $penilaianMap = [];
        foreach ($penilaianRows as $p) {
            $penilaianMap[$p['id_detail_usulan']][$p['id_kriteria']] = $p;
        }

        return view('Administrator/moora/hasil', [
            'title'        => 'Hasil Kalkulasi MOORA',
            'usulan'       => $usulan,
            'hasil'        => $hasil,
            'kriteria'     => $kriteria,
            'penilaianMap' => $penilaianMap,
            'riwayatHasil' => $riwayatHasil ?? [],
        ]);
    }
}
