<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraResultQueryService;

class DashboardController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel = new UsulanPengadaanModel();
        $this->mooraQuery  = new MooraResultQueryService();
    }

    public function index()
    {
        $usulanMasukCount = $this->usulanModel
            ->whereIn('status', ['diajukan', 'banding_gudang'])
            ->countAllResults();

        $usulanTerbaru = $this->usulanModel
            ->orderBy('tanggal_usulan', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->findAll();

        foreach ($usulanTerbaru as &$row) {
            $row['status_tampilan'] = $row['status'] ?? '-';
        }
        unset($row);

        $rankingMoora = $this->mooraQuery->getForRole('gudang', null, 5);

        return view('Gudang/dashboard', [
            'title'            => 'Dashboard Gudang',
            'usulanMasukCount' => (int) $usulanMasukCount,
            'usulanTerbaru'    => $usulanTerbaru ?? [],
            'rankingMoora'     => $rankingMoora ?? [],
        ]);
    }
}
