<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\DetailUsulanModel;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraResultQueryService;
use App\Services\MooraService;
use CodeIgniter\Exceptions\PageNotFoundException;
use Throwable;

class HasilMooraController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailModel;
    protected MooraService $mooraService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel  = new UsulanPengadaanModel();
        $this->detailModel  = new DetailUsulanModel();
        $this->mooraService = new MooraService();
        $this->mooraQuery   = new MooraResultQueryService();
    }

    public function index()
    {
        $rankingMoora = $this->mooraQuery->getForRole('gudang', null, 200);

        return view('Gudang/moora/hasil', [
            'title'        => 'Hasil MOORA Gudang',
            'rankingMoora' => $rankingMoora,
            'auditSummary' => method_exists($this->mooraService, 'auditModeSummary')
                ? $this->mooraService->auditModeSummary()
                : $this->mooraQuery->auditModeSummary(),
        ]);
    }

    public function detail($idUsulan)
    {
        $idUsulan = (int) $idUsulan;
        $usulan = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->where('usulan_pengadaan.id', $idUsulan)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan.');
        }

        $hasil = $this->mooraQuery->latestByUsulan($idUsulan);

        foreach ($hasil as &$row) {
            $decoded = [];
            if (!empty($row['rincian_json'])) {
                $decoded = json_decode((string) $row['rincian_json'], true) ?: [];
            }
            $row['rincian_decoded'] = $decoded;
            $row['source_details']  = $decoded['source_details'] ?? [];
            $row['aggregate_meta']  = $decoded['aggregate_meta'] ?? null;
        }
        unset($row);

        $detailBarang = $this->detailModel->getDetailByUsulan($idUsulan);
        $detailMap = [];
        foreach ($detailBarang as $detail) {
            $detailMap[(int) $detail['id']] = $detail;
        }

        return view('Gudang/moora/detail', [
            'title'        => 'Detail Hasil MOORA',
            'usulan'       => $usulan,
            'hasil'        => $hasil,
            'detailBarang' => $detailBarang,
            'detailMap'    => $detailMap,
            'versi'        => $hasil[0]['versi_hitung'] ?? null,
        ]);
    }

    public function proses($idUsulan)
    {
        try {
            $result = $this->mooraService->prosesUsulan((int) $idUsulan, [
                'allow_any_status' => true,
                'auto_generate'   => true,
                'cost_mode'       => 'standard_moora',
                'engine_log_role' => 'gudang_operasional_patch_7_2',
            ]);

            return redirect()
                ->to(site_url('gudang/hasil-moora/detail/' . (int) $idUsulan))
                ->with('success', 'MOORA berhasil diproses oleh Gudang untuk ' . $result['nomor_usulan'] . ' (' . $result['mode_label'] . ').');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
