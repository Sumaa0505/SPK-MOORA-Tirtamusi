<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\DetailUsulanModel;
use App\Models\NotificationModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;
use CodeIgniter\Exceptions\PageNotFoundException;

class UsulanMasukController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected DetailUsulanModel $detailUsulanModel;
    protected NotificationModel $notificationModel;
    protected WorkflowUsulanService $workflowService;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailUsulanModel = new DetailUsulanModel();
        $this->notificationModel = new NotificationModel();
        $this->workflowService   = new WorkflowUsulanService();
    }

    public function index()
    {
        $usulanMasuk = $this->usulanModel
            ->whereIn('status', ['diajukan', 'banding_gudang'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('Gudang/usulan_masuk/index', [
            'title'       => 'Usulan Masuk Gudang',
            'usulanMasuk' => $usulanMasuk,
        ]);
    }

    public function detail($id)
    {
        $usulan = $this->usulanModel->find((int) $id);
        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        $detailBarang = $this->detailUsulanModel
            ->select('detail_usulan.*, alternatif.kode_alternatif, alternatif.nama_alternatif, alternatif.kategori_barang, alternatif.jenis_barang, alternatif.satuan, alternatif.estimasi_harga, alternatif.stok, alternatif.stok_minimum, alternatif.movement_type, alternatif.kondisi_barang')
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif')
            ->where('id_usulan', (int) $id)
            ->findAll();

        return view('Gudang/usulan_masuk/detail', [
            'title'        => 'Detail Usulan',
            'usulan'       => $usulan,
            'detailBarang' => $detailBarang,
        ]);
    }

    public function verifikasi($idUsulan)
    {
        $idUsulan = (int) $idUsulan;
        $usulan = $this->usulanModel->find($idUsulan);
        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        if (!in_array($usulan['status'] ?? '', ['diajukan', 'banding_gudang'], true)) {
            return redirect()->back()->with('error', 'Usulan hanya dapat diverifikasi dari status diajukan/banding Gudang.');
        }

        $catatan = trim((string) $this->request->getPost('catatan_verifikasi')) ?: 'Usulan diverifikasi Gudang dan siap diproses MOORA oleh Gudang.';
        $idUser  = $this->currentUserId();
        $now     = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $this->workflowService->transition(
            $idUsulan,
            WorkflowUsulanService::STATUS_DIVERIFIKASI,
            $idUser,
            'gudang',
            'verifikasi',
            $catatan,
            'Gudang',
            [
                'status_validasi'    => 'diverifikasi',
                'catatan_verifikasi' => $catatan,
                'validated_by'       => $idUser,
                'validated_at'       => $now,
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memverifikasi usulan.');
        }

        $this->createNotification(
            null,
            'gudang',
            'Usulan Siap Diproses MOORA',
            'Usulan ' . ($usulan['nomor_usulan'] ?? $idUsulan) . ' sudah diverifikasi dan masuk antrian engine MOORA Gudang.',
            'gudang/penilaian/detail/' . $idUsulan,
            'moora',
            $idUsulan
        );

        return redirect()
            ->to(site_url('gudang/penilaian/detail/' . $idUsulan))
            ->with('success', 'Usulan berhasil diverifikasi. Lanjutkan proses MOORA otomatis oleh Gudang.');
    }

    public function banding($id)
    {
        $id = (int) $id;
        $usulan = $this->usulanModel->find($id);
        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        if (!in_array($usulan['status'] ?? '', ['diajukan'], true)) {
            return redirect()->back()->with('error', 'Banding Gudang hanya dapat diajukan untuk usulan yang masih menunggu verifikasi.');
        }

        $keteranganBanding = trim((string) $this->request->getPost('keterangan_banding'));
        if ($keteranganBanding === '') {
            return redirect()->back()->with('error', 'Alasan banding harus diisi.');
        }

        $userGudangId = $this->currentUserId();
        $now = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $this->workflowService->transition(
            $id,
            WorkflowUsulanService::STATUS_BANDING_GUDANG,
            $userGudangId,
            'gudang',
            'banding',
            $keteranganBanding,
            'Gudang',
            [
                'status_validasi'        => 'banding_gudang',
                'catatan_banding_gudang' => $keteranganBanding,
                'banding_by'             => $userGudangId,
                'banding_at'             => $now,
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan banding Gudang.');
        }

        $this->createNotification((int) $usulan['id_user_pengusul'], null, 'Usulan Perlu Revisi Gudang', 'Gudang mengajukan banding/revisi untuk usulan ' . ($usulan['nomor_usulan'] ?? $id) . '.', 'sub-unit/usulan/detail/' . $id, 'warning', $id);

        return redirect()
            ->to(site_url('gudang/usulan-masuk/detail/' . $id))
            ->with('success', 'Usulan berhasil diajukan banding oleh Gudang.');
    }
}
