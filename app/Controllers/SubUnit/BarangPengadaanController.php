<?php

namespace App\Controllers\SubUnit;

use App\Controllers\BaseController;
use App\Models\DistribusiBarangModel;
use App\Models\PengadaanPembelianModel;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;

class BarangPengadaanController extends BaseController
{
    protected DistribusiBarangModel $distribusiModel;
    protected UsulanPengadaanModel $usulanModel;
    protected PengadaanPembelianModel $pengadaanModel;
    protected RiwayatValidasiModel $riwayatModel;
    protected WorkflowUsulanService $workflowService;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiBarangModel();
        $this->usulanModel     = new UsulanPengadaanModel();
        $this->pengadaanModel  = new PengadaanPembelianModel();
        $this->riwayatModel    = new RiwayatValidasiModel();
        $this->workflowService = new WorkflowUsulanService();
        helper(['url', 'form']);
    }

    public function index()
    {
        $idUser = (int) session()->get('id_user');
        $dataBarang = $this->distribusiModel->getByPengusul($idUser);

        return view('SubUnit/barang_pengadaan/index', [
            'title'      => 'Barang Pengadaan',
            'dataBarang' => $dataBarang,
        ]);
    }

    public function konfirmasiTerima($id)
    {
        $idUser = (int) session()->get('id_user');
        $id     = (int) $id;
        $now    = date('Y-m-d H:i:s');

        $barang = $this->distribusiModel
            ->where('id', $id)
            ->where('id_user_pengusul', $idUser)
            ->first();

        if (!$barang) {
            return redirect()->to(site_url('sub-unit/barang-pengadaan'))
                ->with('error', 'Data barang pengadaan tidak ditemukan.');
        }

        if (($barang['status_distribusi'] ?? '') === 'selesai') {
            return redirect()->to(site_url('sub-unit/barang-pengadaan'))
                ->with('error', 'Barang ini sudah pernah dikonfirmasi diterima.');
        }

        $idUsulan = (int) ($barang['id_usulan'] ?? 0);
        $usulan = $this->usulanModel->find($idUsulan);
        if (!$usulan) {
            return redirect()->to(site_url('sub-unit/barang-pengadaan'))
                ->with('error', 'Usulan asal barang tidak ditemukan.');
        }

        $catatanPengusul = trim((string) $this->request->getPost('catatan_pengusul')) ?: 'Barang telah diterima oleh Sub Unit.';

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $this->distribusiModel->update($id, [
                'status_distribusi'        => 'selesai',
                'tanggal_realisasi'        => date('Y-m-d'),
                'diterima_oleh_pengusul_at'=> $now,
                'catatan_pengusul'         => $catatanPengusul,
                'updated_at'               => $now,
            ]);

            $this->riwayatModel->insert([
                'id_usulan'    => $idUsulan,
                'id_user'      => $idUser,
                'role_user'    => 'sub_unit',
                'aksi'         => 'selesai',
                'catatan'      => 'Sub Unit mengonfirmasi barang diterima. ' . $catatanPengusul,
                'tanggal_aksi' => $now,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);

            if ($this->workflowService->canCloseAfterSubUnitConfirmation($idUsulan)) {
                $this->workflowService->transition(
                    $idUsulan,
                    WorkflowUsulanService::STATUS_SELESAI,
                    $idUser,
                    'sub_unit',
                    'selesai',
                    'Seluruh barang pengadaan telah dikonfirmasi diterima oleh Sub Unit.',
                    'Sub Unit',
                    [
                        'status_validasi'    => 'selesai',
                        'catatan_penerimaan' => 'Seluruh distribusi selesai dikonfirmasi Sub Unit.',
                    ]
                );

                $this->pengadaanModel
                    ->where('id_usulan', $idUsulan)
                    ->set([
                        'status_pengadaan' => 'selesai',
                        'updated_at'       => $now,
                    ])
                    ->update();

                $this->createNotification(null, 'gudang', 'Usulan Selesai Diterima Sub Unit', 'Seluruh barang untuk usulan ' . ($usulan['nomor_usulan'] ?? $idUsulan) . ' sudah dikonfirmasi diterima Sub Unit.', 'gudang/penerimaan', 'success', $idUsulan);
            } else {
                $this->createNotification(null, 'gudang', 'Sebagian Barang Sudah Diterima Sub Unit', 'Sub Unit mengonfirmasi sebagian barang untuk usulan ' . ($usulan['nomor_usulan'] ?? $idUsulan) . '. Masih ada distribusi yang belum selesai.', 'gudang/penerimaan', 'info', $idUsulan);
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Gagal konfirmasi terima barang pengadaan Sub Unit: ' . $e->getMessage());

            return redirect()->to(site_url('sub-unit/barang-pengadaan'))
                ->with('error', 'Konfirmasi gagal diproses. ' . $e->getMessage());
        }

        return redirect()->to(site_url('sub-unit/barang-pengadaan'))
            ->with('success', 'Barang berhasil dikonfirmasi diterima. Jika semua barang usulan sudah diterima, status usulan otomatis menjadi selesai.');
    }
}
