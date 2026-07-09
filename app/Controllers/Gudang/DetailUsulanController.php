<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\DetailUsulanModel;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraService;
use App\Services\MooraResultQueryService;
use Throwable;

class DetailUsulanController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailUsulanModel;
    protected MooraService $mooraService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailUsulanModel = new DetailUsulanModel();
        $this->mooraService      = new MooraService();
        $this->mooraQuery        = new MooraResultQueryService();
    }

    public function prosesMoora($idUsulan)
    {
        $idUsulan = (int) $idUsulan;
        try {
            $result = $this->mooraService->prosesUsulan($idUsulan, [
                'allow_any_status' => true,
                'auto_generate'    => true,
                'cost_mode'        => 'standard_moora',
            ]);

            return redirect()
                ->to(site_url('gudang/hasil-moora'))
                ->with('success', 'MOORA berhasil diproses oleh Gudang untuk ' . $result['nomor_usulan'] . ' (' . $result['mode_label'] . ').');
        } catch (Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function detail($idUsulan)
    {
        $usulan = $this->usulanModel->find((int) $idUsulan);

        if (!$usulan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        $details = $this->detailUsulanModel
            ->select('detail_usulan.*, alternatif.nama_alternatif, alternatif.satuan')
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
            ->where('id_usulan', (int) $idUsulan)
            ->findAll();

        $hasilMoora = $this->mooraQuery->activeLatestByUsulan((int) $idUsulan);
        $hasilByDetail = [];
        foreach ($hasilMoora as $row) {
            $hasilByDetail[(int) ($row['id_detail_usulan'] ?? 0)] = $row;
        }

        foreach ($details as &$detail) {
            $detail['hasil_moora'] = $hasilByDetail[(int) $detail['id']] ?? null;
        }
        unset($detail);

        return view('Gudang/usulan_masuk/detail_usulan', [
            'usulan'     => $usulan,
            'details'    => $details,
            'hasilMoora' => $hasilMoora,
        ]);
    }
}
