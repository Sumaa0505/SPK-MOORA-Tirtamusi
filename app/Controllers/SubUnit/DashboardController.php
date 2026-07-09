<?php

namespace App\Controllers\SubUnit;

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
        helper(['url']);
    }

    public function index()
    {
        $idUser = (int) (session()->get('id_user') ?? session()->get('id'));

        $usulan = $this->usulanModel
            ->where('id_user_pengusul', $idUser)
            ->orderBy('id', 'DESC')
            ->findAll();

        $totalDraft = count(array_filter($usulan, static fn($u) => ($u['status'] ?? '') === 'draft'));
        $totalDiajukan = count(array_filter($usulan, static fn($u) => in_array(($u['status'] ?? ''), ['diajukan', 'diverifikasi', 'moora_selesai'], true)));
        $totalSelesai = count(array_filter($usulan, static fn($u) => ($u['status'] ?? '') === 'selesai'));

        return view('SubUnit/dashboard', [
            'title'          => 'Dashboard Sub Unit',
            'totalUsulan'    => count($usulan),
            'totalDraft'     => $totalDraft,
            'totalDiajukan'  => $totalDiajukan,
            'totalSelesai'   => $totalSelesai,
            'usulanTerbaru'  => array_slice($usulan, 0, 5),
            'rankingMoora'   => $this->mooraQuery->getForRole('sub_unit', $idUser, 10),
        ]);
    }
}
