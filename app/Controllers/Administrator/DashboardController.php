<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;
use App\Models\AlternatifModel;
use App\Models\UsulanPengadaanModel;
use App\Models\HasilMooraModel;
use App\Services\MooraResultQueryService;
use App\Models\UserModel;
use App\Models\LogAktivitasModel;
use Config\Database;

class DashboardController extends BaseController
{
    protected $db;

    public function __construct()
    {
        // =====================================================
        // FIX DATABASE CONNECTION (INI WAJIB)
        // =====================================================
        $this->db = Database::connect();
    }

    public function index()
    {
        $kriteriaModel   = new KriteriaModel();
        $alternatifModel = new AlternatifModel();
        $usulanModel     = new UsulanPengadaanModel();
        $hasilModel      = new HasilMooraModel();
        $mooraQuery      = new MooraResultQueryService();
        $userModel       = new UserModel();
        $logModel        = new LogAktivitasModel();

        // =====================================================
        // USULAN STATUS
        // =====================================================
        $usulan = $usulanModel->findAll();

        $totalUsulan   = count($usulan);
        $diproses      = 0;
        $disetujui     = 0;
        $ditolak       = 0;

        foreach ($usulan as $row) {

            $status = strtolower($row['status_validasi'] ?? $row['status'] ?? '');

            if (str_contains($status, 'proses') || str_contains($status, 'menunggu')) {
                $diproses++;
            }

            if (str_contains($status, 'setuju')) {
                $disetujui++;
            }

            if (str_contains($status, 'tolak')) {
                $ditolak++;
            }
        }

        // =====================================================
        // RANKING MOORA AKTIF (PATCH 10 SINGLE SOURCE)
        // Dashboard tidak lagi membaca raw hasil_moora agar RKA tidak tampil pecah per barang.
        // Histori raw tetap dihitung pada jumlahHasil untuk kebutuhan audit Admin.
        // =====================================================
        $rankingTerbaru = $mooraQuery->dashboardRanking(5);

        // =====================================================
        // USER MANAGEMENT (ADMIN MODULE)
        // =====================================================
        $userAktif = $userModel->where('is_active', 1)->countAllResults();

        $userPending = $this->db->table('user_registration')
            ->where('status', 'pending')
            ->countAllResults();

        $userApproved = $this->db->table('user_registration')
            ->where('status', 'approved')
            ->countAllResults();

        $userRejected = $this->db->table('user_registration')
            ->where('status', 'rejected')
            ->countAllResults();

        // =====================================================
        // APPROVAL LOG
        // =====================================================
        $approvalLog = $this->db->table('admin_user_approval_log')
            ->countAllResults();

        // =====================================================
        // RETURN VIEW
        // =====================================================
        return view('Administrator/dashboard', [
            'title'            => 'Dashboard Administrator',

            // MASTER DATA
            'jumlahKriteria'   => $kriteriaModel->countAllResults(),
            'jumlahAlternatif' => $alternatifModel->countAllResults(),
            'jumlahUsulan'     => $totalUsulan,
            'jumlahHasil'      => $hasilModel->countAllResults(),
            'jumlahLog'        => $logModel->countAllResults(),

            // USULAN
            'usulanDiproses'   => $diproses,
            'usulanDisetujui'  => $disetujui,
            'usulanDitolak'    => $ditolak,

            // USER SYSTEM
            'jumlahUserAktif'  => $userAktif,
            'jumlahUserPending'=> $userPending,
            'jumlahUserApprove'=> $userApproved,
            'jumlahUserReject' => $userRejected,

            // APPROVAL LOG
            'jumlahApprovalLog'=> $approvalLog,

            // MOORA RANKING
            'rankingTerbaru'   => $rankingTerbaru,
        ]);
    }
}