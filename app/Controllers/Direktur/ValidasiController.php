<?php

namespace App\Controllers\Direktur;

use App\Controllers\BaseController;
use App\Models\ApprovalDirekturModel;
use App\Models\DetailUsulanModel;
use App\Models\DokumenDisposisiModel;
use App\Models\HasilMooraModel;
use App\Models\LogAktivitasModel;
use App\Models\NotificationModel;
use App\Models\QrDisposisiModel;
use App\Models\RiwayatValidasiModel;
use App\Models\SettingModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;
use App\Services\MooraResultQueryService;
use App\Services\DisposisiDocumentService;

class ValidasiController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailModel;
    protected HasilMooraModel $hasilModel;
    protected LogAktivitasModel $logModel;
    protected RiwayatValidasiModel $riwayatModel;
    protected ApprovalDirekturModel $approvalModel;
    protected DokumenDisposisiModel $dokumenModel;
    protected QrDisposisiModel $qrModel;
    protected NotificationModel $notificationModel;
    protected SettingModel $settingModel;
    protected WorkflowUsulanService $workflowService;
    protected MooraResultQueryService $mooraQuery;
    protected DisposisiDocumentService $disposisiDocumentService;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailModel       = new DetailUsulanModel();
        $this->hasilModel        = new HasilMooraModel();
        $this->logModel          = new LogAktivitasModel();
        $this->riwayatModel      = new RiwayatValidasiModel();
        $this->approvalModel     = new ApprovalDirekturModel();
        $this->dokumenModel      = new DokumenDisposisiModel();
        $this->qrModel           = new QrDisposisiModel();
        $this->notificationModel = new NotificationModel();
        $this->settingModel      = new SettingModel();
        $this->workflowService   = new WorkflowUsulanService();
        $this->mooraQuery        = new MooraResultQueryService();
        $this->disposisiDocumentService = new DisposisiDocumentService();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $usulanList = $db->table('usulan_pengadaan up')
            ->select('
                up.*,
                users.nama_lengkap,
                COUNT(du.id) AS total_item,
                COALESCE(SUM(du.total_estimasi), 0) AS total_anggaran
            ')
            ->join('users', 'users.id = up.id_user_pengusul', 'left')
            ->join('detail_usulan du', 'du.id_usulan = up.id', 'left')
            ->whereIn('up.status', [
                'menunggu_direktur_bidang',
                'menunggu_direktur_utama',
                'menunggu_direktur_umum',
                'disposisi_pengadaan',
                'ditolak',
            ])
            ->groupBy('up.id')
            ->orderBy('up.updated_at', 'DESC')
            ->get()
            ->getResultArray();

        $stageCounts = [
            'direktur_bidang' => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_bidang')->countAllResults(),
            'direktur_utama'  => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_utama')->countAllResults(),
            'direktur_umum'   => (int) $db->table('usulan_pengadaan')->where('status', 'menunggu_direktur_umum')->countAllResults(),
        ];

        return view('Direktur/validasi/index', [
            'title'       => 'Validasi Direktur Multi-Level',
            'usulanList'  => $usulanList,
            'stageCounts' => $stageCounts,
        ]);
    }

    public function detail($id)
    {
        $db = \Config\Database::connect();

        $usulan = $db->table('usulan_pengadaan up')
            ->select('up.*, users.nama_lengkap AS nama_pengusul, validator.nama_lengkap AS nama_validator')
            ->join('users', 'users.id = up.id_user_pengusul', 'left')
            ->join('users validator', 'validator.id = up.validated_by', 'left')
            ->where('up.id', (int) $id)
            ->get()
            ->getRowArray();

        if (!$usulan) {
            return redirect()->to(site_url('direktur/validasi'))->with('error', 'Data usulan tidak ditemukan.');
        }

        $detail = $this->detailModel->getDetailByUsulan((int) $id);

        $hasilMoora = $this->mooraQuery->latestByUsulan((int) $id);

        $approval = $this->approvalModel
            ->where('id_usulan', (int) $id)
            ->orderBy('urutan', 'ASC')
            ->findAll();

        $dokumen = $this->dokumenModel
            ->where('id_usulan', (int) $id)
            ->orderBy('id', 'DESC')
            ->first();

        $qr = null;
        if ($dokumen) {
            $qr = $this->qrModel->where('id_dokumen', (int) $dokumen['id'])->first();
        }

        return view('Direktur/validasi/detail', [
            'title'          => 'Detail Validasi Direktur',
            'usulan'         => $usulan,
            'detail'          => $detail,
            'hasilMoora'     => $hasilMoora,
            'approval'       => $approval,
            'dokumen'        => $dokumen,
            'qr'             => $qr,
            'stageLabel'     => $this->stageLabel($this->resolveStage($usulan)),
            'approvalStages' => $this->buildApprovalStages($usulan, $approval),
        ]);
    }

    public function setujui($id)
    {
        $id = (int) $id;
        $usulan = $this->usulanModel->find($id);

        if (!$usulan) {
            return redirect()->back()->with('error', 'Data usulan tidak ditemukan.');
        }

        $hasil = $this->mooraQuery->activeLatestByUsulan($id);
        if (empty($hasil)) {
            return redirect()->back()->with('error', 'Hasil MOORA belum tersedia. Usulan belum dapat disetujui.');
        }

        $stage   = $this->resolveStage($usulan);
        $catatan = trim((string) $this->request->getPost('catatan_validasi')) ?: $this->defaultCatatanStage($stage);
        $idUser  = $this->currentUserId();
        $now     = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $approval = $this->approvalModel->ensureStage($id, $stage);
        $this->approvalModel->update((int) $approval['id'], [
            'aksi'        => $stage === 'direktur_umum' ? 'disposisi' : 'setujui',
            'catatan'     => $catatan,
            'approved_by' => $idUser,
            'approved_at' => $now,
            'updated_at'  => $now,
        ]);

        $next = $this->nextStage($stage);
        $payload = [
            'catatan_validasi' => $catatan,
            'catatan_direksi'  => $catatan,
            'validated_by'     => $idUser,
            'validated_at'     => $now,
        ];

        if ($next) {
            $targetStatus = 'menunggu_' . $next;
            $payload['status_validasi'] = 'menunggu_' . $next;
            $payload['approval_stage']  = $next;
            $this->approvalModel->ensureStage($id, $next);
        } else {
            $nomorDisposisi = $usulan['nomor_disposisi'] ?: $this->generateNomorDisposisi();
            $targetStatus = WorkflowUsulanService::STATUS_DISPOSISI_PENGADAAN;
            $payload['status_validasi']   = 'disetujui';
            $payload['approval_stage']    = 'selesai';
            $payload['nomor_disposisi']   = $nomorDisposisi;
            $payload['tanggal_disposisi'] = $now;

            $this->buatDokumenDisposisi($id, $nomorDisposisi, $idUser, $now);
        }

        $this->workflowService->transition(
            $id,
            $targetStatus,
            $idUser,
            'direktur',
            $this->aksiRiwayatStage($stage),
            $catatan,
            'Direktur',
            $payload
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan approval Direktur.');
        }

        if ($next) {
            $this->createNotification(null, 'direktur', 'Approval ' . $this->stageLabel($next) . ' Menunggu', 'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' masuk tahap ' . $this->stageLabel($next) . '.', 'direktur/validasi/detail/' . $id, 'approval', $id);
            $message = 'Approval ' . $this->stageLabel($stage) . ' berhasil. Lanjut ke ' . $this->stageLabel($next) . '.';
        } else {
            $this->createNotification(null, 'pengadaan', 'Disposisi Pengadaan Baru', 'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' sudah mendapat disposisi Direktur Umum dan siap diproses Pengadaan.', 'pengadaan/pembelian', 'pengadaan', $id);
            $message = 'Approval Direktur Umum berhasil. Usulan masuk ke Bagian Pengadaan.';
        }

        return redirect()->to(site_url('direktur/validasi/detail/' . $id))->with('success', $message);
    }

    public function tolak($id)
    {
        $id = (int) $id;
        $usulan = $this->usulanModel->find($id);

        if (!$usulan) {
            return redirect()->back()->with('error', 'Data usulan tidak ditemukan.');
        }

        $catatan = trim((string) $this->request->getPost('catatan_validasi'));
        if ($catatan === '') {
            return redirect()->back()->with('error', 'Catatan penolakan wajib diisi.');
        }

        $stage  = $this->resolveStage($usulan);
        $idUser = $this->currentUserId();
        $now    = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $approval = $this->approvalModel->ensureStage($id, $stage);
        $this->approvalModel->update((int) $approval['id'], [
            'aksi'        => 'tolak',
            'catatan'     => $catatan,
            'approved_by' => $idUser,
            'approved_at' => $now,
            'updated_at'  => $now,
        ]);

        $this->workflowService->transition(
            $id,
            WorkflowUsulanService::STATUS_DITOLAK,
            $idUser,
            'direktur',
            'tolak',
            $catatan,
            'Direktur',
            [
                'status_validasi'  => 'ditolak',
                'catatan_validasi' => $catatan,
                'catatan_direksi'  => $catatan,
                'validated_by'     => $idUser,
                'validated_at'     => $now,
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menolak usulan.');
        }

        $this->createNotification((int) $usulan['id_user_pengusul'], null, 'Usulan Ditolak Direktur', 'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' ditolak pada tahap ' . $this->stageLabel($stage) . '.', 'sub-unit/usulan/detail/' . $id, 'danger', $id);
        return redirect()->to(site_url('direktur/validasi/detail/' . $id))->with('success', 'Usulan berhasil ditolak oleh Direktur.');
    }

    private function resolveStage(array $usulan): string
    {
        $stage = (string) ($usulan['approval_stage'] ?? '');
        if (in_array($stage, ['direktur_bidang', 'direktur_utama', 'direktur_umum'], true)) {
            return $stage;
        }

        return match ($usulan['status'] ?? '') {
            'menunggu_direktur_utama' => 'direktur_utama',
            'menunggu_direktur_umum'   => 'direktur_umum',
            default                                                => 'direktur_bidang',
        };
    }

    private function nextStage(string $stage): ?string
    {
        return match ($stage) {
            'direktur_bidang' => 'direktur_utama',
            'direktur_utama'  => 'direktur_umum',
            default           => null,
        };
    }

    private function stageLabel(string $stage): string
    {
        return match ($stage) {
            'direktur_bidang' => 'Direktur Bidang',
            'direktur_utama'  => 'Direktur Utama',
            'direktur_umum'   => 'Direktur Umum',
            default           => 'Direktur',
        };
    }

    private function aksiRiwayatStage(string $stage): string
    {
        return match ($stage) {
            'direktur_bidang' => 'approval_bidang',
            'direktur_utama'  => 'approval_utama',
            'direktur_umum'   => 'disposisi',
            default           => 'setujui',
        };
    }

    private function defaultCatatanStage(string $stage): string
    {
        return match ($stage) {
            'direktur_bidang' => 'Disetujui secara teknis operasional oleh Direktur Bidang.',
            'direktur_utama'  => 'Disetujui sebagai prioritas strategis perusahaan oleh Direktur Utama.',
            'direktur_umum'   => 'Disposisi ke Bagian Pengadaan oleh Direktur Umum.',
            default           => 'Disetujui.',
        };
    }

    private function buildApprovalStages(array $usulan, array $approval): array
    {
        $activeStage = $this->resolveStage($usulan);
        $isFinal = in_array($usulan['status'] ?? '', [
            'disposisi_pengadaan',
            'diproses_pengadaan',
            'selesai_pengadaan',
            'menunggu_penerimaan',
            'direalisasi',
            'selesai',
            'ditolak',
        ], true) || in_array($usulan['status_validasi'] ?? '', ['disetujui', 'ditolak'], true);

        $approvalByStage = [];
        foreach ($approval as $row) {
            $approvalByStage[$row['tahap_approval'] ?? ''] = $row;
        }

        $descriptions = [
            'direktur_bidang' => 'Review teknis operasional dan kesesuaian kebutuhan unit.',
            'direktur_utama'  => 'Review prioritas strategis dan kelayakan keputusan perusahaan.',
            'direktur_umum'   => 'Disposisi final ke Bagian Pengadaan setelah dua tahap sebelumnya selesai.',
        ];

        $stages = [];
        foreach (['direktur_bidang', 'direktur_utama', 'direktur_umum'] as $key) {
            $row = $approvalByStage[$key] ?? [];
            $aksi = strtolower((string) ($row['aksi'] ?? 'menunggu'));
            $state = 'waiting';

            if (in_array($aksi, ['setujui', 'disposisi'], true)) {
                $state = 'done';
            } elseif ($aksi === 'tolak') {
                $state = 'rejected';
            } elseif (!$isFinal && $key === $activeStage) {
                $state = 'active';
            }

            $stages[] = [
                'key'         => $key,
                'label'       => $this->stageLabel($key),
                'description' => $descriptions[$key],
                'aksi'        => $aksi,
                'state'       => $state,
                'approved_at' => $row['approved_at'] ?? null,
                'catatan'     => $row['catatan'] ?? null,
            ];
        }

        return $stages;
    }

    private function generateNomorDisposisi(): string
    {
        $seq = $this->dokumenModel
            ->like('nomor_dokumen', 'DISP/' . date('Y/m'), 'after')
            ->countAllResults() + 1;

        return 'DISP/' . date('Y/m') . '/' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    private function buatDokumenDisposisi(int $idUsulan, string $nomorDisposisi, ?int $idUser, string $now): void
    {
        $this->disposisiDocumentService->finalize($idUsulan, $nomorDisposisi, $idUser, $now);
    }
}
