<?php

namespace App\Controllers\Pengadaan;

use App\Controllers\BaseController;
use App\Models\DetailUsulanModel;
use App\Models\NotificationModel;
use App\Models\PengadaanPembelianModel;
use App\Models\PengadaanSerahBarangModel;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;

class SerahBarangController extends BaseController
{
    protected PengadaanPembelianModel $pengadaanModel;
    protected PengadaanSerahBarangModel $serahModel;
    protected DetailUsulanModel $detailModel;
    protected UsulanPengadaanModel $usulanModel;
    protected RiwayatValidasiModel $riwayatModel;
    protected NotificationModel $notificationModel;
    protected WorkflowUsulanService $workflowService;

    public function __construct()
    {
        $this->pengadaanModel    = new PengadaanPembelianModel();
        $this->serahModel        = new PengadaanSerahBarangModel();
        $this->detailModel       = new DetailUsulanModel();
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->riwayatModel      = new RiwayatValidasiModel();
        $this->notificationModel = new NotificationModel();
        $this->workflowService   = new WorkflowUsulanService();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $pengadaan = $db->table('pengadaan_pembelian pp')
            ->select('pp.*, up.nomor_usulan, up.unit_pengusul')
            ->join('usulan_pengadaan up', 'up.id = pp.id_usulan', 'left')
            ->whereIn('pp.status_pengadaan', ['diproses', 'po_terbit', 'barang_datang', 'diserahkan_gudang'])
            ->orderBy('pp.id', 'DESC')
            ->get()->getResultArray();

        $detail = $db->table('detail_usulan du')
            ->select("du.*, a.kode_alternatif, a.nama_alternatif, a.satuan, up.nomor_usulan, COALESCE(SUM(CASE WHEN psb.status_serah <> 'ditolak_gudang' THEN psb.jumlah_diserahkan ELSE 0 END),0) AS jumlah_sudah_diserahkan")
            ->join('alternatif a', 'a.id = du.id_alternatif', 'left')
            ->join('usulan_pengadaan up', 'up.id = du.id_usulan', 'left')
            ->join('pengadaan_serah_barang psb', 'psb.id_detail_usulan = du.id', 'left')
            ->whereIn('up.status', ['diproses_pengadaan', 'menunggu_penerimaan'])
            ->groupBy('du.id')
            ->having('(du.jumlah - jumlah_sudah_diserahkan) >', 0)
            ->orderBy('up.id', 'DESC')
            ->get()->getResultArray();

        foreach ($detail as &$row) {
            $row['sisa_serah'] = max(0, (int) ($row['jumlah'] ?? 0) - (int) ($row['jumlah_sudah_diserahkan'] ?? 0));
        }
        unset($row);

        $serah = $db->table('pengadaan_serah_barang psb')
            ->select('psb.*, pp.nomor_pengadaan, up.nomor_usulan, a.nama_alternatif, a.satuan')
            ->join('pengadaan_pembelian pp', 'pp.id = psb.id_pengadaan', 'left')
            ->join('usulan_pengadaan up', 'up.id = psb.id_usulan', 'left')
            ->join('alternatif a', 'a.id = psb.id_alternatif', 'left')
            ->orderBy('psb.created_at', 'DESC')
            ->get()->getResultArray();

        return view('Pengadaan/serah_barang/index', [
            'title'     => 'Serah Barang ke Gudang',
            'pengadaan' => $pengadaan,
            'detail'    => $detail,
            'serah'     => $serah,
        ]);
    }

    public function store()
    {
        $idPengadaan = (int) $this->request->getPost('id_pengadaan');
        $idDetail    = (int) $this->request->getPost('id_detail_usulan');
        $jumlah      = (int) $this->request->getPost('jumlah_diserahkan');

        $pengadaan = $this->pengadaanModel->find($idPengadaan);
        $detail    = $this->detailModel->find($idDetail);

        if (!$pengadaan || !$detail || (int) $detail['id_usulan'] !== (int) $pengadaan['id_usulan']) {
            return redirect()->back()->with('error', 'Data pengadaan/detail barang tidak valid.');
        }

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah serah barang harus lebih dari 0.');
        }

        if (!in_array(($pengadaan['status_pengadaan'] ?? ''), ['diproses', 'po_terbit', 'barang_datang', 'diserahkan_gudang'], true)) {
            return redirect()->back()->with('error', 'Pengadaan belum berada pada status yang dapat diserahkan ke Gudang.');
        }

        // PATCH 10: Dokumen pengadaan menjadi prasyarat serah barang ke Gudang.
        $jumlahDokumen = (int) \Config\Database::connect()->table('pengadaan_dokumen')
            ->where('id_pengadaan', $idPengadaan)
            ->countAllResults();
        if ($jumlahDokumen < 1) {
            return redirect()->back()->with('error', 'Upload minimal satu dokumen pengadaan terlebih dahulu sebelum serah barang ke Gudang.');
        }

        $sudahDiserahkan = (int) (\Config\Database::connect()->table('pengadaan_serah_barang')
            ->selectSum('jumlah_diserahkan', 'total')
            ->where('id_detail_usulan', $idDetail)
            ->where('status_serah !=', 'ditolak_gudang')
            ->get()->getRowArray()['total'] ?? 0);
        $sisa = max(0, (int) ($detail['jumlah'] ?? 0) - $sudahDiserahkan);
        if ($jumlah > $sisa) {
            return redirect()->back()->with('error', 'Jumlah serah melebihi sisa barang usulan. Sisa yang dapat diserahkan: ' . $sisa . '.');
        }

        $idUser = $this->currentUserId();
        $now = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        $db->transStart();

        $this->serahModel->insert([
            'id_pengadaan'       => $idPengadaan,
            'id_usulan'          => (int) $pengadaan['id_usulan'],
            'id_detail_usulan'   => $idDetail,
            'id_alternatif'      => (int) $detail['id_alternatif'],
            'jumlah_diserahkan'  => $jumlah,
            'tanggal_serah'      => $this->request->getPost('tanggal_serah') ?: date('Y-m-d'),
            'status_serah'       => 'menunggu_gudang',
            'catatan_pengadaan'  => trim((string) $this->request->getPost('catatan_pengadaan')) ?: null,
            'created_by'         => $idUser,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $this->pengadaanModel->update($idPengadaan, [
            'status_pengadaan' => 'diserahkan_gudang',
            'updated_at'       => $now,
        ]);

        $catatanSerah = trim((string) $this->request->getPost('catatan_pengadaan')) ?: 'Barang diserahkan ke Gudang untuk penerimaan.';

        $this->workflowService->transition(
            (int) $pengadaan['id_usulan'],
            WorkflowUsulanService::STATUS_MENUNGGU_PENERIMAAN,
            $idUser,
            'pengadaan',
            'serah_barang',
            $catatanSerah,
            'Pengadaan',
            [
                'status_validasi'  => 'menunggu_penerimaan',
                'catatan_pengadaan' => $catatanSerah,
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan serah barang.');
        }

        $this->createNotification(null, 'gudang', 'Barang Menunggu Penerimaan', 'Bagian Pengadaan menyerahkan barang untuk diterima Gudang.', 'gudang/penerimaan', 'pengadaan', (int) $pengadaan['id_usulan']);
        return redirect()->to(site_url('pengadaan/serah-barang'))->with('success', 'Serah barang berhasil dicatat. Gudang dapat melakukan penerimaan.');
    }
}
