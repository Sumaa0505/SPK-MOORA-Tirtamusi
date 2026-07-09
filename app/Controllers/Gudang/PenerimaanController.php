<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use App\Models\DistribusiBarangModel;
use App\Models\LogAktivitasModel;
use App\Models\PenerimaanBarangModel;
use App\Models\PengadaanPembelianModel;
use App\Models\PengadaanSerahBarangModel;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use App\Services\WorkflowUsulanService;

class PenerimaanController extends BaseController
{
    protected AlternatifModel $alternatifModel;
    protected LogAktivitasModel $logModel;
    protected PengadaanSerahBarangModel $serahModel;
    protected PenerimaanBarangModel $penerimaanModel;
    protected UsulanPengadaanModel $usulanModel;
    protected PengadaanPembelianModel $pengadaanModel;
    protected RiwayatValidasiModel $riwayatModel;
    protected DistribusiBarangModel $distribusiModel;
    protected WorkflowUsulanService $workflowService;

    public function __construct()
    {
        $this->alternatifModel  = new AlternatifModel();
        $this->logModel         = new LogAktivitasModel();
        $this->serahModel       = new PengadaanSerahBarangModel();
        $this->penerimaanModel  = new PenerimaanBarangModel();
        $this->usulanModel      = new UsulanPengadaanModel();
        $this->pengadaanModel   = new PengadaanPembelianModel();
        $this->riwayatModel     = new RiwayatValidasiModel();
        $this->distribusiModel  = new DistribusiBarangModel();
        $this->workflowService   = new WorkflowUsulanService();
        helper(['url', 'form']);
    }

    public function index()
    {
        $barang = $this->alternatifModel
            ->orderBy('nama_alternatif', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();
        $serahPengadaan = $db->table('pengadaan_serah_barang psb')
            ->select('psb.*, pp.nomor_pengadaan, up.nomor_usulan, up.unit_pengusul, up.id_user_pengusul, a.kode_alternatif, a.nama_alternatif, a.satuan')
            ->join('pengadaan_pembelian pp', 'pp.id = psb.id_pengadaan', 'left')
            ->join('usulan_pengadaan up', 'up.id = psb.id_usulan', 'left')
            ->join('alternatif a', 'a.id = psb.id_alternatif', 'left')
            ->where('psb.status_serah', 'menunggu_gudang')
            ->orderBy('psb.created_at', 'DESC')
            ->get()->getResultArray();

        return view('Gudang/penerimaan/index', [
            'title'          => 'Penerimaan Barang',
            'barang'         => $barang,
            'serahPengadaan' => $serahPengadaan,
        ]);
    }

    public function create()
    {
        return redirect()->to(site_url('gudang/penerimaan'));
    }

    public function store()
    {
        $idBarang = (int) $this->request->getPost('id_barang');
        $jumlah   = (int) $this->request->getPost('jumlah');
        $sumberBarang = trim((string) $this->request->getPost('sumber_barang'));
        $userLogin = session()->get('nama_lengkap') ?? 'Seksi Gudang';
        $catatan  = trim((string) $this->request->getPost('catatan'));

        if ($idBarang <= 0 || $jumlah <= 0) {
            return redirect()->back()->withInput()->with('error', 'Barang dan jumlah penerimaan wajib diisi dengan benar.');
        }

        $barang = $this->alternatifModel->find($idBarang);
        if (!$barang) {
            return redirect()->back()->withInput()->with('error', 'Data barang tidak ditemukan.');
        }

        $stokLama = (int) ($barang['stok'] ?? 0);
        $stokBaru = $stokLama + $jumlah;
        $now = date('Y-m-d H:i:s');

        $this->alternatifModel->update($idBarang, [
            'stok'              => $stokBaru,
            'last_stock_update' => $now,
            'updated_at'        => $now,
        ]);

        $this->penerimaanModel->insert([
            'id_alternatif'      => $idBarang,
            'id_user_gudang'     => $this->currentUserId(),
            'jumlah'             => $jumlah,
            'status_penerimaan'  => 'diterima',
            'tanggal'            => date('Y-m-d'),
            'sumber'             => $sumberBarang ?: 'Umum',
            'keterangan'         => $catatan,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $this->logModel->simpanLog(
            'Penerimaan Barang',
            'User: ' . $userLogin . '. Menerima barang: ' . ($barang['nama_alternatif'] ?? '-') . '. Jumlah diterima: ' . $jumlah . ' ' . ($barang['satuan'] ?? '') . '. Asal/Sumber barang: ' . ($sumberBarang ?: '-') . '. Stok awal: ' . $stokLama . '. Stok akhir: ' . $stokBaru . '. Catatan: ' . ($catatan ?: '-'),
            'Gudang'
        );

        return redirect()->to(site_url('gudang/penerimaan'))->with('success', 'Penerimaan barang berhasil dicatat. Stok barang otomatis bertambah.');
    }

    public function terimaSerah(int $idSerah)
    {
        $serah = $this->serahModel->find($idSerah);
        if (!$serah || ($serah['status_serah'] ?? '') !== 'menunggu_gudang') {
            return redirect()->back()->with('error', 'Data serah barang tidak ditemukan atau sudah diproses.');
        }

        $barang = $this->alternatifModel->find((int) $serah['id_alternatif']);
        $usulan = $this->usulanModel->find((int) $serah['id_usulan']);
        if (!$barang || !$usulan) {
            return redirect()->back()->with('error', 'Data barang/usulan tidak lengkap.');
        }

        $jumlahDiterima = (int) $this->request->getPost('jumlah_diterima');
        if ($jumlahDiterima <= 0) {
            $jumlahDiterima = (int) $serah['jumlah_diserahkan'];
        }

        $jumlahDiserahkan = (int) $serah['jumlah_diserahkan'];
        if ($jumlahDiterima > $jumlahDiserahkan) {
            return redirect()->back()->with('error', 'Jumlah diterima tidak boleh melebihi jumlah yang diserahkan.');
        }

        $sisaParsial = max(0, $jumlahDiserahkan - $jumlahDiterima);
        $statusPenerimaan = $sisaParsial > 0 ? 'parsial' : 'diterima';
        $catatan = trim((string) $this->request->getPost('catatan_gudang')) ?: 'Barang diterima Gudang dari Bagian Pengadaan.';
        $idUser = $this->currentUserId();
        $now = date('Y-m-d H:i:s');
        $stokLama = (int) ($barang['stok'] ?? 0);
        $stokBaru = $stokLama + $jumlahDiterima;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->alternatifModel->update((int) $serah['id_alternatif'], [
            'stok'              => $stokBaru,
            'last_stock_update' => $now,
            'updated_at'        => $now,
        ]);

        $this->serahModel->update($idSerah, [
            'status_serah'   => 'diterima_gudang',
            'catatan_gudang' => $catatan . ($sisaParsial > 0 ? ' (parsial, sisa ' . $sisaParsial . ' dibuat sebagai serah barang baru)' : ''),
            'received_by'    => $idUser,
            'received_at'    => $now,
            'updated_at'     => $now,
        ]);

        if ($sisaParsial > 0) {
            $this->serahModel->insert([
                'id_pengadaan'       => !empty($serah['id_pengadaan']) ? (int) $serah['id_pengadaan'] : null,
                'id_usulan'          => (int) $serah['id_usulan'],
                'id_detail_usulan'   => !empty($serah['id_detail_usulan']) ? (int) $serah['id_detail_usulan'] : null,
                'id_alternatif'      => (int) $serah['id_alternatif'],
                'jumlah_diserahkan'  => $sisaParsial,
                'tanggal_serah'      => $serah['tanggal_serah'] ?: date('Y-m-d'),
                'status_serah'       => 'menunggu_gudang',
                'catatan_pengadaan'  => 'Sisa penerimaan parsial dari serah barang #' . $idSerah,
                'created_by'         => !empty($serah['created_by']) ? (int) $serah['created_by'] : null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        $this->penerimaanModel->insert([
            'id_usulan'          => (int) $serah['id_usulan'],
            'id_detail_usulan'   => (int) $serah['id_detail_usulan'],
            'id_pengadaan_serah' => $idSerah,
            'id_alternatif'      => (int) $serah['id_alternatif'],
            'id_user_gudang'     => $idUser,
            'jumlah'             => $jumlahDiterima,
            'status_penerimaan'  => $statusPenerimaan,
            'tanggal'            => date('Y-m-d'),
            'sumber'             => 'Pengadaan',
            'keterangan'         => $catatan,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $this->distribusiModel->insert([
            'id_usulan'             => (int) $serah['id_usulan'],
            'id_detail_usulan'      => (int) $serah['id_detail_usulan'],
            'id_pengadaan_serah'    => $idSerah,
            'id_alternatif'         => (int) $serah['id_alternatif'],
            'id_user_pengusul'      => (int) $usulan['id_user_pengusul'],
            'jenis_distribusi'      => 'diambil',
            'status_distribusi'     => 'menunggu_pengambilan',
            'jumlah'                => $jumlahDiterima,
            'tanggal_jadwal'        => date('Y-m-d'),
            'catatan_gudang'        => 'Barang pengadaan sudah diterima Gudang. Menunggu pengambilan/konfirmasi Sub Unit.',
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $sisaMenunggu = $this->serahModel
            ->where('id_usulan', (int) $serah['id_usulan'])
            ->where('status_serah', 'menunggu_gudang')
            ->countAllResults();

        if ($sisaMenunggu < 1) {
            // PATCH 8 FINAL DEMO WORKFLOW LOCK:
            // Gudang menerima barang dari Pengadaan bukan berarti usulan selesai.
            // Usulan tetap menunggu penerimaan/konfirmasi Sub Unit sampai semua distribusi dikonfirmasi.
            $this->workflowService->transition(
                (int) $serah['id_usulan'],
                WorkflowUsulanService::STATUS_MENUNGGU_PENERIMAAN,
                $idUser,
                'gudang',
                'penerimaan',
                $catatan . ' Barang sudah diterima Gudang dan menunggu konfirmasi penerimaan oleh Sub Unit.',
                'Gudang',
                [
                    'status_validasi'    => 'menunggu_konfirmasi_subunit',
                    'catatan_penerimaan' => $catatan,
                ]
            );

            if (!empty($serah['id_pengadaan'])) {
                $this->pengadaanModel->update((int) $serah['id_pengadaan'], [
                    'status_pengadaan' => 'diserahkan_gudang',
                    'updated_at'       => $now,
                ]);
            }
        } else {
            $this->riwayatModel->insert([
                'id_usulan'    => (int) $serah['id_usulan'],
                'id_user'      => $idUser,
                'role_user'    => 'gudang',
                'aksi'         => 'penerimaan',
                'catatan'      => $catatan,
                'tanggal_aksi' => $now,
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses penerimaan barang dari Pengadaan.');
        }

        $this->createNotification((int) $usulan['id_user_pengusul'], null, 'Barang Pengadaan Sudah Diterima Gudang', 'Barang untuk usulan ' . ($usulan['nomor_usulan'] ?? $serah['id_usulan']) . ' sudah diterima Gudang.', 'sub-unit/barang-pengadaan', 'success', (int) $serah['id_usulan']);
        return redirect()->to(site_url('gudang/penerimaan'))->with('success', 'Barang pengadaan berhasil diterima. Stok bertambah, distribusi dibuat, dan Sub Unit harus mengonfirmasi penerimaan agar usulan selesai.');
    }
}
