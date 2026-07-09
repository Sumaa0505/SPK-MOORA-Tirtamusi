<?php

namespace App\Controllers\Direktur;

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

        $totalUsulan = $db->table('usulan_pengadaan')->countAllResults();

        $menungguValidasi = $db->table('usulan_pengadaan')
            ->whereIn('status', MooraResultQueryService::DIREKTUR_APPROVAL_STATUSES)
            ->countAllResults();

        $disetujui = $db->table('usulan_pengadaan')
            ->whereIn('status', ['disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan', 'selesai'])
            ->countAllResults();

        $ditolak = $db->table('usulan_pengadaan')
            ->where('status', 'ditolak')
            ->countAllResults();

        $totalAnggaran = $db->table('detail_usulan')
            ->selectSum('total_estimasi', 'total')
            ->get()
            ->getRowArray();

        $anggaranDisetujui = $db->table('detail_usulan du')
            ->selectSum('du.total_estimasi', 'total')
            ->join('usulan_pengadaan up', 'up.id = du.id_usulan', 'left')
            ->whereIn('up.status', ['disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan', 'selesai'])
            ->get()
            ->getRowArray();

        $topPrioritas = $this->mooraQuery->getForRole('direktur', null, 5);

        $stageCounts = [
            'direktur_bidang' => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_bidang')->countAllResults(),
            'direktur_utama'  => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_utama')->countAllResults(),
            'direktur_umum'   => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_umum')->countAllResults(),
        ];

        return view('Direktur/dashboard', [
            'title'             => 'Dashboard Direktur',
            'totalUsulan'       => $totalUsulan,
            'menungguValidasi'  => $menungguValidasi,
            'disetujui'         => $disetujui,
            'ditolak'           => $ditolak,
            'totalAnggaran'     => $totalAnggaran['total'] ?? 0,
            'anggaranDisetujui' => $anggaranDisetujui['total'] ?? 0,
            'topPrioritas'      => $topPrioritas,
            'stageCounts'       => $stageCounts,
        ]);
    }
}
