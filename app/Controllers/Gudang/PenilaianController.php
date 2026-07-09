<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\DetailUsulanModel;
use App\Models\HasilMooraModel;
use App\Models\KriteriaModel;
use App\Models\PenilaianModel;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraService;
use App\Services\MooraResultQueryService;
use Throwable;

class PenilaianController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailUsulanModel;
    protected KriteriaModel $kriteriaModel;
    protected PenilaianModel $penilaianModel;
    protected HasilMooraModel $hasilMooraModel;
    protected MooraService $mooraService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailUsulanModel = new DetailUsulanModel();
        $this->kriteriaModel     = new KriteriaModel();
        $this->penilaianModel    = new PenilaianModel();
        $this->hasilMooraModel   = new HasilMooraModel();
        $this->mooraService      = new MooraService();
        $this->mooraQuery       = new MooraResultQueryService();
        helper(['form', 'url']);
    }

    /**
     * V4: daftar usulan yang sudah diverifikasi dan menjadi antrian proses MOORA Gudang.
     */
    public function index()
    {
        $usulanList = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul, COUNT(detail_usulan.id) AS jumlah_item')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->join('detail_usulan', 'detail_usulan.id_usulan = usulan_pengadaan.id', 'left')
            ->whereIn('usulan_pengadaan.status', ['diajukan', 'diverifikasi', 'moora_selesai'])
            ->groupBy('usulan_pengadaan.id')
            ->orderBy('usulan_pengadaan.updated_at', 'DESC')
            ->findAll();

        foreach ($usulanList as &$usulan) {
            $hasilAktif = $this->mooraQuery->activeLatestByUsulan((int) $usulan['id']);
            $usulan['terakhir_dihitung'] = $hasilAktif[0]['tanggal_hitung'] ?? null;
            $usulan['versi_terakhir']    = $hasilAktif[0]['versi_hitung'] ?? null;
            $usulan['mode_hitung']       = $hasilAktif[0]['mode_hitung'] ?? null;
            $usulan['detailBarang'] = $this->detailUsulanModel
                ->select('detail_usulan.*, alternatif.nama_alternatif, alternatif.satuan')
                ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
                ->where('id_usulan', (int) $usulan['id'])
                ->findAll();
        }
        unset($usulan);

        return view('Gudang/penilaian/index', [
            'title'      => 'Engine MOORA Gudang',
            'usulanList' => $usulanList,
        ]);
    }

    public function detail($id)
    {
        $id = (int) $id;
        try {
            $preview = $this->mooraService->previewPenilaianOtomatis($id);
        } catch (Throwable $e) {
            return redirect()->to(site_url('gudang/penilaian'))->with('error', $e->getMessage());
        }

        $existing = $this->mooraQuery->latestByUsulan($id);

        return view('Gudang/penilaian/detail', $preview + [
            'title'       => 'Proses MOORA Gudang',
            'hasilAktif'  => $existing,
        ]);
    }

    /**
     * V4: Gudang tidak lagi input manual nilai satu per satu.
     * Sistem membuat nilai C1-C5 otomatis, menyimpan penilaian, menghitung MOORA, dan update status.
     */
    public function submit($id)
    {
        $id = (int) $id;
        try {
            $result = $this->mooraService->prosesUsulan($id, [
                'allow_any_status' => true,
                'auto_generate'    => true,
                'cost_mode'        => 'standard_moora',
            ]);

            return redirect()
                ->to(site_url('gudang/hasil-moora'))
                ->with('success', 'MOORA berhasil diproses oleh Gudang untuk ' . $result['nomor_usulan'] . ' (' . $result['mode_label'] . ').');
        } catch (Throwable $e) {
            log_message('error', 'Gagal proses MOORA Gudang V4: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
