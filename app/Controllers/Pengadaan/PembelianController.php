<?php

namespace App\Controllers\Pengadaan;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\PengadaanPembelianModel;
use App\Models\PengadaanDokumenModel;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;

class PembelianController extends BaseController
{
    protected UsulanPengadaanModel $usulanModel;
    protected PengadaanPembelianModel $pengadaanModel;
    protected PengadaanDokumenModel $dokumenModel;
    protected RiwayatValidasiModel $riwayatModel;
    protected NotificationModel $notificationModel;
    protected WorkflowUsulanService $workflowService;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->pengadaanModel    = new PengadaanPembelianModel();
        $this->dokumenModel      = new PengadaanDokumenModel();
        $this->riwayatModel      = new RiwayatValidasiModel();
        $this->notificationModel = new NotificationModel();
        $this->workflowService   = new WorkflowUsulanService();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $usulan = $db->table('usulan_pengadaan up')
            ->select('up.*, users.nama_lengkap AS nama_pengusul, COALESCE(SUM(du.total_estimasi),0) AS total_anggaran')
            ->join('users', 'users.id = up.id_user_pengusul', 'left')
            ->join('detail_usulan du', 'du.id_usulan = up.id', 'left')
            ->where('up.status', 'disposisi_pengadaan')
            ->groupBy('up.id')
            ->orderBy('up.tanggal_disposisi', 'DESC')
            ->get()->getResultArray();

        $pembelian = $db->table('pengadaan_pembelian pp')
            ->select("pp.*, up.nomor_usulan, up.unit_pengusul, up.status AS status_usulan, COUNT(pd.id) AS jumlah_dokumen, SUM(CASE WHEN pd.jenis_dokumen = 'po' THEN 1 ELSE 0 END) AS dok_po, SUM(CASE WHEN pd.jenis_dokumen = 'invoice' THEN 1 ELSE 0 END) AS dok_invoice, SUM(CASE WHEN pd.jenis_dokumen = 'bast' THEN 1 ELSE 0 END) AS dok_bast")
            ->join('usulan_pengadaan up', 'up.id = pp.id_usulan', 'left')
            ->join('pengadaan_dokumen pd', 'pd.id_pengadaan = pp.id', 'left')
            ->groupBy('pp.id')
            ->orderBy('pp.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('Pengadaan/pembelian/index', [
            'title'     => 'Proses Pembelian',
            'usulan'    => $usulan,
            'pembelian' => $pembelian,
        ]);
    }

    public function store()
    {
        $idUsulan = (int) $this->request->getPost('id_usulan');
        $usulan = $this->usulanModel->find($idUsulan);

        if (!$usulan || ($usulan['status'] ?? '') !== 'disposisi_pengadaan') {
            return redirect()->back()->with('error', 'Usulan tidak valid atau belum didisposisikan ke Pengadaan.');
        }

        $existing = $this->pengadaanModel->where('id_usulan', $idUsulan)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Usulan ini sudah memiliki data pembelian.');
        }

        $db = \Config\Database::connect();
        $total = (float) ($db->table('detail_usulan')->selectSum('total_estimasi', 'total')->where('id_usulan', $idUsulan)->get()->getRowArray()['total'] ?? 0);
        $now = date('Y-m-d H:i:s');
        $idUser = $this->currentUserId();

        $db->transStart();

        $this->pengadaanModel->insert([
            'id_usulan'          => $idUsulan,
            'nomor_pengadaan'    => $this->generateNomorPengadaan(),
            'nomor_po'           => trim((string) $this->request->getPost('nomor_po')) ?: null,
            'vendor'             => trim((string) $this->request->getPost('vendor')) ?: null,
            'tanggal_pengadaan'  => $this->request->getPost('tanggal_pengadaan') ?: date('Y-m-d'),
            'tanggal_po'         => $this->request->getPost('tanggal_po') ?: null,
            'total_pengadaan'    => $total,
            'status_pengadaan'   => 'diproses',
            'catatan'            => trim((string) $this->request->getPost('catatan')) ?: null,
            'created_by'         => $idUser,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $catatanPengadaan = trim((string) $this->request->getPost('catatan')) ?: 'Diproses Bagian Pengadaan.';

        $this->workflowService->transition(
            $idUsulan,
            WorkflowUsulanService::STATUS_DIPROSES_PENGADAAN,
            $idUser,
            'pengadaan',
            'pembelian',
            $catatanPengadaan,
            'Pengadaan',
            [
                'status_validasi' => 'diproses_pengadaan',
                'catatan_pengadaan' => $catatanPengadaan,
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan proses pembelian.');
        }

        $this->createNotification(null, 'gudang', 'Pengadaan Sedang Diproses', 'Usulan ' . ($usulan['nomor_usulan'] ?? $idUsulan) . ' sedang diproses pembelian oleh Pengadaan.', 'gudang/penerimaan', 'pengadaan', $idUsulan);
        return redirect()->to(site_url('pengadaan/pembelian'))->with('success', 'Proses pembelian berhasil dibuat.');
    }

    public function updateStatus(int $id)
    {
        $row = $this->pengadaanModel->find($id);
        if (!$row) {
            return redirect()->back()->with('error', 'Data pengadaan tidak ditemukan.');
        }

        $status = (string) $this->request->getPost('status_pengadaan');
        $allowed = ['menunggu', 'diproses', 'po_terbit', 'barang_datang', 'diserahkan_gudang', 'selesai', 'dibatalkan'];
        if (!in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status pengadaan tidak valid.');
        }

        if ($status === 'selesai') {
            return redirect()->back()->with('error', 'Status selesai tidak boleh dipilih manual oleh Pengadaan. Usulan selesai otomatis setelah Gudang menerima barang dan Sub Unit mengonfirmasi distribusi.');
        }

        if (in_array($status, ['po_terbit', 'barang_datang', 'diserahkan_gudang'], true) && !$this->hasDokumenPengadaan((int) $row['id'], ['po', 'invoice', 'bast', 'surat_jalan'])) {
            return redirect()->back()->with('error', 'Upload minimal satu dokumen pengadaan (PO/invoice/BAST/surat jalan) sebelum menaikkan status.');
        }

        $now = date('Y-m-d H:i:s');
        $idUser = $this->currentUserId() ?: 1;
        $catatan = trim((string) $this->request->getPost('catatan')) ?: ($row['catatan'] ?? null);

        if (in_array($status, ['po_terbit', 'barang_datang', 'diserahkan_gudang', 'selesai'], true)) {
            if (empty($row['nomor_po']) || empty($row['vendor'])) {
                return redirect()->back()->with('error', 'Nomor PO dan vendor wajib diisi sebelum status dapat dinaikkan ke ' . ucwords(str_replace('_', ' ', $status)) . '.');
            }
        }

        $this->pengadaanModel->update($id, [
            'status_pengadaan' => $status,
            'catatan'          => $catatan,
            'updated_at'       => $now,
        ]);

        $this->riwayatModel->insert([
            'id_usulan'    => (int) $row['id_usulan'],
            'id_user'      => $idUser,
            'role_user'    => 'pengadaan',
            'aksi'         => 'pengadaan',
            'catatan'      => 'Status pengadaan diperbarui menjadi ' . $status . '. ' . ($catatan ?: ''),
            'tanggal_aksi' => $now,
        ]);

        $this->logAktivitas($idUser, 'Update Status Pengadaan', 'Pengadaan', 'Status pengadaan ID ' . $id . ' diperbarui menjadi ' . $status . '.');

        return redirect()->back()->with('success', 'Status pengadaan berhasil diperbarui.');
    }

    private function hasDokumenPengadaan(int $idPengadaan, array $jenis = []): bool
    {
        $builder = $this->dokumenModel->where('id_pengadaan', $idPengadaan);
        if (!empty($jenis)) {
            $builder->whereIn('jenis_dokumen', $jenis);
        }

        return $builder->countAllResults() > 0;
    }

    private function generateNomorPengadaan(): string
    {
        $prefix = 'PGD-' . date('Ymd') . '-';
        $last = $this->pengadaanModel->like('nomor_pengadaan', $prefix, 'after')->orderBy('id', 'DESC')->first();
        $next = $last ? ((int) substr((string) $last['nomor_pengadaan'], -3)) + 1 : 1;
        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
