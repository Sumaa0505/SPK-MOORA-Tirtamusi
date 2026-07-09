<?php

namespace App\Controllers\ManajerUmum;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use App\Models\UsulanPengadaanModel;
use App\Models\UserModel;
use App\Services\MooraResultQueryService;

class DashboardController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected UserModel $userModel;
    protected AlternatifModel $alternatifModel;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel     = new UsulanPengadaanModel();
        $this->userModel       = new UserModel();
        $this->alternatifModel = new AlternatifModel();
        $this->mooraQuery      = new MooraResultQueryService();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $usulanReview = $this->usulanModel
            ->whereIn('status', ['moora_selesai'])
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        $dataUsulanPerTanggal = $this->usulanModel
            ->select('DATE(tanggal_usulan) AS tanggal, COUNT(*) AS total', false)
            ->groupBy('DATE(tanggal_usulan)')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $dataKategoriBarang = $db->table('alternatif')
            ->select('kategori_barang AS kategori, kategori_barang, COUNT(*) AS total', false)
            ->groupBy('kategori_barang')
            ->get()
            ->getResultArray();

        $dataMooraPerUsulan = $this->mooraQuery->getChartMooraPerUsulan('manajer_umum', null, 20);

        return view('manajer_umum/dashboard', [
            'title'                  => 'Dashboard Manajer Umum',
            'total_usulan'           => $this->usulanModel->countAllResults(),
            'total_users'            => $this->userModel->countAllResults(),
            'total_barang'           => $this->alternatifModel->countAllResults(),
            'total_moora'            => count($this->mooraQuery->getForRole('manajer_umum', null, 500)),
            'usulan'                 => $usulanReview,

            // Nama variabel yang dipakai view saat ini.
            'data_usulan_per_tanggal'=> $dataUsulanPerTanggal,
            'data_kategori_barang'   => $dataKategoriBarang,
            'data_moora_per_usulan'  => $dataMooraPerUsulan,

            // Alias kompatibilitas controller lama.
            'chart_usulan'           => $dataUsulanPerTanggal,
            'chart_kategori'         => $dataKategoriBarang,
            'chart_moora'            => $dataMooraPerUsulan,
        ]);
    }
}
