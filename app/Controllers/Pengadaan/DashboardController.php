<?php

namespace App\Controllers\Pengadaan;

use App\Controllers\BaseController;
use App\Services\MooraResultQueryService;

class DashboardController extends BaseController
{
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->mooraQuery = new MooraResultQueryService();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title'              => 'Dashboard Pengadaan',
            'total_disposisi'    => $db->table('usulan_pengadaan')->where('status', 'disposisi_pengadaan')->countAllResults(),
            'total_diproses'     => $db->table('pengadaan_pembelian')->whereIn('status_pengadaan', ['menunggu', 'diproses', 'po_terbit', 'barang_datang'])->countAllResults(),
            'total_serah_gudang' => $db->table('pengadaan_serah_barang')->where('status_serah', 'menunggu_gudang')->countAllResults(),
            'total_selesai'      => $db->table('pengadaan_pembelian')->where('status_pengadaan', 'selesai')->countAllResults(),
            'usulan'             => $db->table('usulan_pengadaan up')
                ->select('up.*, users.nama_lengkap AS nama_pengusul, COALESCE(SUM(du.total_estimasi),0) AS total_anggaran')
                ->join('users', 'users.id = up.id_user_pengusul', 'left')
                ->join('detail_usulan du', 'du.id_usulan = up.id', 'left')
                ->whereIn('up.status', ['disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan'])
                ->groupBy('up.id')
                ->orderBy('up.updated_at', 'DESC')
                ->limit(8)
                ->get()->getResultArray(),
            'rankingMoora'       => $this->mooraQuery->getForRole('pengadaan', null, 20),
        ];

        return view('Pengadaan/dashboard', $data);
    }
}
