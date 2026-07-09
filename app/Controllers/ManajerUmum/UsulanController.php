<?php

namespace App\Controllers\ManajerUmum;

use App\Controllers\BaseController;
use App\Models\ApprovalDirekturModel;
use App\Models\DetailUsulanModel;
use App\Models\NotificationModel;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;
use App\Services\MooraResultQueryService;
use CodeIgniter\Exceptions\PageNotFoundException;

class UsulanController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailUsulanModel;
    protected RiwayatValidasiModel $riwayatValidasiModel;
    protected ApprovalDirekturModel $approvalDirekturModel;
    protected NotificationModel $notificationModel;
    protected WorkflowUsulanService $workflowService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel           = new UsulanPengadaanModel();
        $this->detailUsulanModel     = new DetailUsulanModel();
        $this->riwayatValidasiModel  = new RiwayatValidasiModel();
        $this->approvalDirekturModel = new ApprovalDirekturModel();
        $this->notificationModel     = new NotificationModel();
        $this->workflowService       = new WorkflowUsulanService();
        $this->mooraQuery            = new MooraResultQueryService();
    }

    public function index()
    {
        $data['usulan'] = $this->usulanModel
            ->withPengusul()
            ->whereIn('usulan_pengadaan.status', ['moora_selesai', 'dikembalikan', 'menunggu_direktur_bidang', 'menunggu_direktur_utama', 'menunggu_direktur_umum', 'disposisi_pengadaan'])
            ->orderBy('usulan_pengadaan.updated_at', 'DESC')
            ->findAll();

        $data['title'] = 'Review Usulan - Manajer Umum';

        return view('manajer_umum/usulan_index', $data);
    }

    public function detail(int $id)
    {
        $usulan = $this->usulanModel
            ->withPengusul()
            ->where('usulan_pengadaan.id', $id)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        $detail = $this->detailUsulanModel
            ->select('detail_usulan.*, alternatif.kode_alternatif, alternatif.nama_alternatif, alternatif.kategori_barang, alternatif.stok as stok_barang, alternatif.kondisi_barang, alternatif.satuan, alternatif.spesifikasi')
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
            ->where('detail_usulan.id_usulan', $id)
            ->findAll() ?? [];

        $hasilMoora = $this->mooraQuery->latestByUsulan((int) $id);

        return view('manajer_umum/usulan_detail', [
            'title'      => 'Detail Review Usulan',
            'usulan'     => $usulan,
            'detail'      => $detail,
            'hasilMoora' => $hasilMoora,
        ]);
    }

    public function rekomendasi(int $id)
    {
        $usulan = $this->usulanModel->find($id);
        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        if (!in_array($usulan['status'] ?? '', ['moora_selesai'], true)) {
            return redirect()->back()->with('error', 'Usulan hanya bisa direkomendasikan setelah MOORA selesai.');
        }

        $hasilAktif = $this->mooraQuery->activeLatestByUsulan($id);
        if (empty($hasilAktif)) {
            return redirect()->back()->with('error', 'Hasil MOORA belum tersedia.');
        }

        $idUser  = $this->currentUserId();
        $catatan = trim((string) $this->request->getPost('catatan_manajer')) ?: 'Direkomendasikan ke Direktur berdasarkan hasil MOORA.';
        $now     = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $this->workflowService->transition(
            $id,
            WorkflowUsulanService::STATUS_MENUNGGU_DIREKTUR_BIDANG,
            $idUser,
            'manajer_umum',
            'rekomendasi',
            $catatan,
            'Manajer Umum',
            [
                'status_validasi' => 'direkomendasikan',
                'approval_stage'  => 'direktur_bidang',
                'catatan_manajer' => $catatan,
            ]
        );

        $this->approvalDirekturModel->ensureStage($id, 'direktur_bidang');


        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan rekomendasi.');
        }

        $this->createNotification(null, 'direktur', 'Usulan Siap Approval Direktur Bidang', 'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' sudah direkomendasikan Manajer Umum.', 'direktur/validasi/detail/' . $id, 'approval', $id);
        return redirect()->to(site_url('manajer-umum/usulan/detail/' . $id))->with('success', 'Usulan berhasil direkomendasikan ke Direktur Bidang.');
    }

    public function kembalikan(int $id)
    {
        $usulan = $this->usulanModel->find($id);
        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        $catatan = trim((string) $this->request->getPost('catatan_manajer'));
        if ($catatan === '') {
            return redirect()->back()->with('error', 'Catatan pengembalian wajib diisi.');
        }

        $idUser = $this->currentUserId();
        $now    = date('Y-m-d H:i:s');

        $this->workflowService->transition(
            $id,
            WorkflowUsulanService::STATUS_DIKEMBALIKAN,
            $idUser,
            'manajer_umum',
            'kembalikan',
            $catatan,
            'Manajer Umum',
            [
                'status_validasi' => 'dikembalikan',
                'catatan_manajer' => $catatan,
            ]
        );

        $this->createNotification((int) $usulan['id_user_pengusul'], null, 'Usulan Dikembalikan Manajer Umum', 'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' dikembalikan untuk revisi.', 'sub-unit/usulan/detail/' . $id, 'warning', $id);
        return redirect()->back()->with('success', 'Usulan berhasil dikembalikan untuk revisi.');
    }
}
