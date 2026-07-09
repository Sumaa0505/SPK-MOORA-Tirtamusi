<?php

namespace App\Controllers\SubUnit;

use App\Controllers\BaseController;
use App\Models\UsulanPengadaanModel;
use App\Models\DetailUsulanModel;
use App\Models\AlternatifModel;
use App\Models\NotificationModel;
use App\Models\RiwayatValidasiModel;
use App\Services\WorkflowUsulanService;
use App\Services\MooraResultQueryService;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UsulanController extends BaseController
{
    protected $usulanModel;
    protected $detailUsulanModel;
    protected $alternatifModel;
    protected WorkflowUsulanService $workflowService;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailUsulanModel = new DetailUsulanModel();
        $this->alternatifModel   = new AlternatifModel();
        $this->workflowService   = new WorkflowUsulanService();
        $this->mooraQuery        = new MooraResultQueryService();

        helper(['url', 'form', 'filesystem']);
    }

    public function index()
{
    $idUser = (int) session()->get('id_user');

    // Ambil semua usulan milik user Sub Unit
    $usulanList = $this->usulanModel
        ->where('id_user_pengusul', $idUser)
        ->orderBy('id', 'DESC')
        ->findAll();

    // Loop tiap usulan untuk menambahkan informasi banding & tampilan
    foreach ($usulanList as &$row) {
        // Tandai apakah usulan sedang di-banding oleh Gudang
        $row['is_banding_gudang'] = $this->isBandingGudang($row);

        // Tentukan label/status yang akan ditampilkan di view
        $row['status_tampilan'] = $this->getStatusTampilan($row);

        // Ambil catatan banding, jika ada
        $row['catatan_banding'] = $row['catatan_banding_gudang'] 
            ?? $row['catatan_verifikasi'] 
            ?? $row['catatan_validasi'] 
            ?? null;
    }
    unset($row);

    return view('SubUnit/usulan/index', [
        'title'      => 'Usulan Saya',
        'usulanList' => $usulanList,
    ]);
}
    public function create()
    {
        return view('SubUnit/usulan/form', [
            'title'          => 'Buat Usulan Pengadaan',
            'alternatifList' => $this->alternatifModel->getAktif(),
            'mode'           => 'create',
            'usulan'         => null,
            'detailList'     => [],
        ]);
    }

    public function edit($id)
    {
        $idUser = (int) session()->get('id_user');
        $id     = (int) $id;

        $usulan = $this->usulanModel
            ->where('id', $id)
            ->where('id_user_pengusul', $idUser)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        if (!$this->bolehDiperbarui($usulan)) {
            return redirect()
                ->to(site_url('sub-unit/usulan/detail/' . $id))
                ->with('error', 'Usulan ini tidak bisa diperbarui karena sudah masuk proses validasi atau keputusan.');
        }

        return view('SubUnit/usulan/form', [
            'title'          => 'Perbarui Usulan Pengadaan',
            'alternatifList' => $this->alternatifModel->getAktif(),
            'mode'           => 'edit',
            'usulan'         => $usulan,
            'detailList'     => $this->detailUsulanModel->getDetailByUsulan($id),
        ]);
    }

    public function store()
    {
        $jenisUsulan = $this->normalisasiJenisUsulan($this->request->getPost('jenis_usulan'));

        if ($jenisUsulan === null) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jenis usulan wajib dipilih.');
        }

        $unitPengusul = $this->cleanString($this->request->getPost('unit_pengusul'));

        if ($unitPengusul === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unit pengusul wajib diisi.');
        }

        $rkaItems = [];
        $fileRkaPath = null;
        $fileRkaExcelPath = null;
        $fileRkaDokumenPath = null;

        if ($jenisUsulan === 'RKA') {
            $validasiRka = $this->validasiDanBacaRka();

            if (!$validasiRka['success']) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $validasiRka['message']);
            }

            $rkaItems = $validasiRka['items'];
            $fileRkaPath = $validasiRka['file_rka_path'] ?? null;
            $fileRkaExcelPath = $validasiRka['file_rka_path'] ?? null;
            $fileRkaDokumenPath = $this->simpanDokumenRkaPendukung();
        }

        if ($jenisUsulan === 'Pesan Cepat' && !$this->hasPesanCepatItem()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Minimal pilih satu barang untuk Pesan Cepat.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $now = date('Y-m-d H:i:s');

            $payloadUsulan = $this->filterUsulanPayload([
                'nomor_usulan'          => $this->generateNomorUsulan(),
                'tanggal_usulan'        => date('Y-m-d'),
                'unit_pengusul'         => $unitPengusul,
                'id_user_pengusul'      => (int) session()->get('id_user'),
                'status'                => 'draft',
                'status_validasi'       => 'menunggu',
                'jenis_usulan'          => $jenisUsulan,
                'catatan_pengusul'      => $this->cleanString($this->request->getPost('catatan_pengusul')),
                'file_rka_path'         => $fileRkaPath,
                'file_rka_excel_path'   => $fileRkaExcelPath,
                'file_rka_dokumen_path' => $fileRkaDokumenPath,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);

            $idUsulan = $this->usulanModel->insert($payloadUsulan, true);

            if (!$idUsulan) {
                throw new \RuntimeException('Gagal menyimpan header usulan.');
            }

            $this->simpanDetailUsulan((int) $idUsulan, $jenisUsulan, $rkaItems);
            $this->pastikanDetailTidakKosong((int) $idUsulan);

            $db->transCommit();

            return redirect()
                ->to(site_url('sub-unit/usulan/detail/' . $idUsulan))
                ->with('success', 'Usulan berhasil dibuat. RKA terbaca sebagai satu dokumen usulan berisi beberapa barang.');
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Gagal membuat usulan Sub Unit: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Usulan gagal disimpan. ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        $idUser = (int) session()->get('id_user');
        $id     = (int) $id;

        $usulan = $this->usulanModel
            ->where('id', $id)
            ->where('id_user_pengusul', $idUser)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        if (!$this->bolehDiperbarui($usulan)) {
            return redirect()
                ->to(site_url('sub-unit/usulan/detail/' . $id))
                ->with('error', 'Usulan ini tidak bisa diperbarui karena sudah masuk proses validasi atau keputusan.');
        }

        $jenisUsulan = $this->normalisasiJenisUsulan($this->request->getPost('jenis_usulan'))
            ?? ($usulan['jenis_usulan'] ?? 'RKA');

        $unitPengusul = $this->cleanString($this->request->getPost('unit_pengusul'));

        if ($unitPengusul === '') {
            $unitPengusul = (string) ($usulan['unit_pengusul'] ?? '');
        }

        $rkaItems            = [];
        $fileRkaPath         = $usulan['file_rka_path'] ?? null;
        $fileRkaExcelPath    = $usulan['file_rka_excel_path'] ?? ($usulan['file_rka_path'] ?? null);
        $fileRkaDokumenPath  = $usulan['file_rka_dokumen_path'] ?? null;
        $replaceDetails      = false;

        if ($jenisUsulan === 'RKA') {
            $fileRka = $this->request->getFile('file_rka');

            if ($fileRka && $fileRka->getError() !== UPLOAD_ERR_NO_FILE) {
                $validasiRka = $this->validasiDanBacaRka();

                if (!$validasiRka['success']) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $validasiRka['message']);
                }

                $rkaItems        = $validasiRka['items'];
                $fileRkaPath     = $validasiRka['file_rka_path'] ?? $fileRkaPath;
                $fileRkaExcelPath = $validasiRka['file_rka_path'] ?? $fileRkaExcelPath;
                $replaceDetails  = true;
            }
        }

        if ($jenisUsulan === 'RKA') {
            $dokumenPendukung = $this->simpanDokumenRkaPendukung(false);
            if ($dokumenPendukung !== null) {
                $fileRkaDokumenPath = $dokumenPendukung;
            }
        }

        if ($jenisUsulan === 'Pesan Cepat') {
            if (!$this->hasPesanCepatItem()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Minimal pilih satu barang untuk Pesan Cepat.');
            }

            $replaceDetails = true;
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $now = date('Y-m-d H:i:s');

            $catatanPengusul = $this->cleanString($this->request->getPost('catatan_pengusul'));

            $this->workflowService->transition(
                $id,
                WorkflowUsulanService::STATUS_DRAFT,
                $idUser,
                'sub_unit',
                'revisi',
                $catatanPengusul ?: 'Usulan diperbarui oleh Sub Unit dan dikembalikan ke draft.',
                'Sub Unit',
                [
                    'status_validasi'  => 'menunggu',
                    'unit_pengusul'    => $unitPengusul,
                    'jenis_usulan'     => $jenisUsulan,
                    'catatan_pengusul' => $catatanPengusul,
                    'file_rka_path'         => $fileRkaPath,
                    'file_rka_excel_path'   => $fileRkaExcelPath,
                    'file_rka_dokumen_path' => $fileRkaDokumenPath,
                ]
            );

            if ($replaceDetails) {
                $this->detailUsulanModel
                    ->where('id_usulan', $id)
                    ->delete();

                $this->simpanDetailUsulan($id, $jenisUsulan, $rkaItems);
            }

            $this->pastikanDetailTidakKosong($id);

            $db->transCommit();

            return redirect()
                ->to(site_url('sub-unit/usulan/detail/' . $id))
                ->with('success', 'Usulan berhasil diperbarui. Silakan ajukan kembali ke Seksi Gudang.');
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Gagal memperbarui usulan Sub Unit: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Usulan gagal diperbarui. ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $idUser = (int) session()->get('id_user');
        $id     = (int) $id;

        $usulan = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->where('usulan_pengadaan.id', $id)
            ->where('usulan_pengadaan.id_user_pengusul', $idUser)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Usulan tidak ditemukan');
        }

        $usulan['is_banding_gudang'] = $this->isBandingGudang($usulan);
        $usulan['status_tampilan']   = $this->getStatusTampilan($usulan);
        $usulan['catatan_banding']   = $usulan['catatan_verifikasi']
            ?? $usulan['catatan_validasi']
            ?? null;

        $db = \Config\Database::connect();
        $hasilMoora = $this->mooraQuery->latestByUsulan($id);
        $riwayat = $db->table('riwayat_validasi rv')
            ->select('rv.*, users.nama_lengkap AS nama_user')
            ->join('users', 'users.id = rv.id_user', 'left')
            ->where('rv.id_usulan', $id)
            ->orderBy('rv.tanggal_aksi', 'ASC')
            ->get()
            ->getResultArray();
        $distribusi = $db->table('distribusi_barang db')
            ->select('db.*, alternatif.kode_alternatif, alternatif.nama_alternatif, alternatif.satuan')
            ->join('alternatif', 'alternatif.id = db.id_alternatif', 'left')
            ->where('db.id_usulan', $id)
            ->orderBy('db.id', 'ASC')
            ->get()
            ->getResultArray();

        return view('SubUnit/usulan/detail', [
            'title'      => 'Detail Usulan',
            'usulan'     => $usulan,
            'detailList' => $this->detailUsulanModel->getDetailByUsulan($id),
            'hasilMoora' => $hasilMoora,
            'riwayat'    => $riwayat,
            'distribusi' => $distribusi,
        ]);
    }

    public function ajukan($id)
    {
        $idUser = (int) session()->get('id_user');
        $id     = (int) $id;

        $usulan = $this->usulanModel
            ->where('id', $id)
            ->where('id_user_pengusul', $idUser)
            ->first();

        if (!$usulan) {
            return redirect()->back()->with('error', 'Usulan tidak ditemukan.');
        }

        if ($this->isBandingGudang($usulan)) {
            return redirect()
                ->to(site_url('sub-unit/usulan/detail/' . $id))
                ->with('error', 'Gudang mengajukan banding. Perbarui usulan terlebih dahulu sebelum diajukan kembali.');
        }

        if (($usulan['status'] ?? '') !== 'draft') {
            return redirect()->back()->with('error', 'Hanya usulan berstatus draft yang bisa diajukan.');
        }

        $jumlahDetail = $this->detailUsulanModel
            ->where('id_usulan', $id)
            ->countAllResults();

        if ($jumlahDetail < 1) {
            return redirect()->back()->with('error', 'Usulan belum memiliki detail barang.');
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $now = date('Y-m-d H:i:s');

            $this->workflowService->transition(
                $id,
                WorkflowUsulanService::STATUS_DIAJUKAN,
                $idUser,
                'sub_unit',
                'ajukan',
                'Usulan diajukan Sub Unit ke Seksi Gudang.',
                'Sub Unit',
                [
                    'status_validasi' => 'menunggu',
                ]
            );

            (new NotificationModel())->createForRole(
                'gudang',
                'Usulan Baru Masuk',
                'Usulan ' . ($usulan['nomor_usulan'] ?? $id) . ' menunggu verifikasi Seksi Gudang.',
                'gudang/usulan-masuk/detail/' . $id,
                'info',
                $id,
                $idUser
            );

            $db->transCommit();

            return redirect()
                ->to(site_url('sub-unit/usulan'))
                ->with('success', 'Usulan berhasil diajukan ke Seksi Gudang.');
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Gagal mengajukan usulan Sub Unit: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Usulan gagal diajukan.');
        }
    }

    private function simpanDetailUsulan(int $idUsulan, string $jenisUsulan, array $rkaItems = []): void
    {
        if ($jenisUsulan === 'RKA') {
            $this->simpanDetailRka($idUsulan, $rkaItems);
            return;
        }

        $this->simpanDetailPesanCepat($idUsulan);
    }

    private function simpanDetailRka(int $idUsulan, array $items): void
    {
        foreach ($items as $item) {
            $idAlt = $this->getOrCreateAlternatifFromRka($item);

            $this->detailUsulanModel->insert([
                'id_usulan'             => $idUsulan,
                'id_alternatif'         => $idAlt,
                'jumlah'                => (int) $item['jumlah'],
                'estimasi_harga_satuan' => (float) $item['estimasi_harga_satuan'],
                'alasan_kebutuhan'      => $item['alasan_kebutuhan'] ?: 'Berdasarkan dokumen RKA',
                'status'                => 'draft',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function simpanDetailPesanCepat(int $idUsulan): void
    {
        $idAlternatif = (array) $this->request->getPost('id_alternatif');
        $jumlah       = (array) $this->request->getPost('jumlah');
        $alasan       = (array) $this->request->getPost('alasan_kebutuhan');

        foreach ($idAlternatif as $i => $idAlt) {
            $idAlt = (int) $idAlt;

            if ($idAlt < 1) {
                continue;
            }

            $alt = $this->alternatifModel->find($idAlt);

            if (!$alt) {
                continue;
            }

            $qty = max(1, (int) ($jumlah[$i] ?? 1));

            $this->detailUsulanModel->insert([
                'id_usulan'             => $idUsulan,
                'id_alternatif'         => $idAlt,
                'jumlah'                => $qty,
                'estimasi_harga_satuan' => (float) ($alt['estimasi_harga'] ?? 0),
                'alasan_kebutuhan'      => $this->cleanString($alasan[$i] ?? '') ?: 'Pesan cepat kebutuhan operasional',
                'status'                => 'draft',
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function validasiDanBacaRka(): array
    {
        $fileRka = $this->request->getFile('file_rka');

        if (!$fileRka || $fileRka->getError() === UPLOAD_ERR_NO_FILE) {
            return [
                'success' => false,
                'message' => 'File RKA Excel wajib diupload.',
                'items'   => [],
            ];
        }

        if (!$fileRka->isValid()) {
            return [
                'success' => false,
                'message' => 'File RKA tidak valid atau gagal diupload.',
                'items'   => [],
            ];
        }

        $ext = strtolower($fileRka->getClientExtension());

        if (!in_array($ext, ['xlsx', 'xls'], true)) {
            return [
                'success' => false,
                'message' => 'File RKA harus berformat Excel .xlsx atau .xls agar daftar barang dapat dibaca sistem.',
                'items'   => [],
            ];
        }

        try {
            $items = $this->readRkaExcel($fileRka->getTempName());
        } catch (\Throwable $e) {
            log_message('error', 'Gagal membaca file RKA Sub Unit: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'File RKA gagal dibaca. Pastikan format kolom Excel sudah sesuai.',
                'items'   => [],
            ];
        }

        if (empty($items)) {
            return [
                'success' => false,
                'message' => 'File RKA tidak memiliki baris barang yang bisa dibaca.',
                'items'   => [],
            ];
        }

        $storedPath = $this->simpanFileRka($fileRka);

        return [
            'success' => true,
            'message'       => 'OK',
            'items'         => $items,
            'file_rka_path' => $storedPath,
        ];
    }

    private function simpanFileRka($fileRka): ?string
    {
        if (!$fileRka || !$fileRka->isValid()) {
            return null;
        }

        $dir = WRITEPATH . 'uploads/rka';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $ext = strtolower($fileRka->getClientExtension() ?: $fileRka->guessExtension() ?: 'xlsx');
        $safeName = 'rka_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        $fileRka->move($dir, $safeName);

        return 'writable/uploads/rka/' . $safeName;
    }


    private function simpanDokumenRkaPendukung(bool $required = false): ?string
    {
        $file = $this->request->getFile('file_rka_dokumen');

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                throw new \RuntimeException('Dokumen resmi RKA wajib diupload.');
            }
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('Dokumen resmi RKA tidak valid atau gagal diupload.');
        }

        $ext = strtolower($file->getClientExtension() ?: $file->guessExtension() ?: 'pdf');
        if (!in_array($ext, ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], true)) {
            throw new \RuntimeException('Dokumen resmi RKA harus PDF/Word/gambar. Excel tetap diupload pada field Excel RKA.');
        }

        $dir = WRITEPATH . 'uploads/rka';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $safeName = 'rka_dokumen_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $file->move($dir, $safeName);

        return 'writable/uploads/rka/' . $safeName;
    }

    /**
     * Payload difilter supaya patch tetap non-destruktif bila SQL kolom baru belum dijalankan.
     */
    private function filterUsulanPayload(array $payload): array
    {
        static $allowed = null;
        if ($allowed === null) {
            $fields = \Config\Database::connect()->getFieldNames('usulan_pengadaan');
            $allowed = array_flip($fields);
        }

        return array_intersect_key($payload, $allowed);
    }

    private function readRkaExcel(string $path): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        $spreadsheet = $reader->load($path);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        if (count($rows) < 2) {
            return [];
        }

        [$headerIndex, $map] = $this->findHeaderRka($rows);

        if ($headerIndex === null) {
            $headerIndex = 0;
            $map         = $this->buildHeaderMap($rows[0] ?? []);
        }

        $items = [];

        foreach ($rows as $i => $row) {
            if ($i <= $headerIndex) {
                continue;
            }

            if ($this->rowKosong($row)) {
                continue;
            }

            $nama = $this->getCellByAliases($row, $map, [
                'namabarang',
                'namabarangjasa',
                'nama',
                'barang',
                'item',
                'uraian',
                'uraianbarang',
                'uraianpekerjaan',
                'kebutuhan',
            ], 1);

            $nama = $this->cleanString($nama);

            if ($nama === '' || $this->isBarisNonBarang($nama)) {
                continue;
            }

            $kode = $this->getCellByAliases($row, $map, [
                'kode',
                'kodebarang',
                'kodealternatif',
                'kodeitem',
            ], null);

            $kategori = $this->getCellByAliases($row, $map, [
                'kategori',
                'kategoribarang',
                'kelompok',
                'kelompokbarang',
            ], null);

            $jenisBarang = $this->getCellByAliases($row, $map, [
                'jenisbarang',
                'jenis',
            ], null);

            $spesifikasi = $this->getCellByAliases($row, $map, [
                'spesifikasi',
                'spek',
                'spesifikasiteknis',
                'uraianpekerjaan',
                'keteranganbarang',
            ], 2);

            $jumlah = $this->getCellByAliases($row, $map, [
                'jumlah',
                'qty',
                'volume',
                'kuantitas',
                'banyak',
            ], 3);

            $satuan = $this->getCellByAliases($row, $map, [
                'satuan',
                'unit',
            ], 4);

            $estimasi = $this->getCellByAliases($row, $map, [
                'estimasisatuan',
                'estimasihargasatuan',
                'hargasatuan',
                'harga',
                'biayasatuan',
                'estimasi',
                'pagu',
                'anggaran',
            ], 5);

            $alasan = $this->getCellByAliases($row, $map, [
                'alasankebutuhan',
                'alasan',
                'keterangan',
                'catatan',
                'justifikasi',
            ], 6);

            $qty = (int) $this->normalizeNumber($jumlah);

            if ($qty < 1) {
                continue;
            }

            $jenisFinal = strtolower($this->cleanString($jenisBarang));

            if (!in_array($jenisFinal, ['alat', 'material', 'aset'], true)) {
                $jenisFinal = 'alat';
            }

            $items[] = [
                'kode_alternatif'       => substr($this->cleanString($kode), 0, 20),
                'nama_alternatif'       => substr($nama, 0, 150),
                'kategori_barang'       => substr($this->cleanString($kategori) ?: 'RKA Sub Unit', 0, 100),
                'jenis_barang'          => $jenisFinal,
                'spesifikasi'           => $this->cleanString($spesifikasi),
                'jumlah'                => $qty,
                'satuan'                => substr($this->cleanString($satuan) ?: 'unit', 0, 30),
                'estimasi_harga_satuan' => (float) $this->normalizeNumber($estimasi),
                'alasan_kebutuhan'      => $this->cleanString($alasan),
            ];
        }

        return $this->gabungkanItemRkaDuplikat($items);
    }

    private function findHeaderRka(array $rows): array
    {
        $max = min(12, count($rows));

        for ($i = 0; $i < $max; $i++) {
            $map    = $this->buildHeaderMap($rows[$i] ?? []);
            $keys   = array_keys($map);
            $joined = implode('|', $keys);

            $hasNama = str_contains($joined, 'namabarang')
                || str_contains($joined, 'uraian')
                || str_contains($joined, 'kebutuhan');

            $hasJumlah = str_contains($joined, 'jumlah')
                || str_contains($joined, 'volume')
                || str_contains($joined, 'qty')
                || str_contains($joined, 'kuantitas');

            if ($hasNama && $hasJumlah) {
                return [$i, $map];
            }
        }

        return [null, []];
    }

    private function getOrCreateAlternatifFromRka(array $item): int
    {
        $kode = trim((string) ($item['kode_alternatif'] ?? ''));
        $nama = trim((string) ($item['nama_alternatif'] ?? ''));

        if ($nama === '') {
            throw new \RuntimeException('Nama barang pada RKA tidak boleh kosong.');
        }

        $alt = null;

        if ($kode !== '') {
            $alt = $this->alternatifModel
                ->where('kode_alternatif', $kode)
                ->first();
        }

        if (!$alt) {
            $alt = $this->alternatifModel
                ->where('nama_alternatif', $nama)
                ->first();
        }

        if ($alt) {
            $this->sinkronkanAlternatifDariRka((int) $alt['id'], $alt, $item);
            return (int) $alt['id'];
        }

        if ($kode === '') {
            $kode = $this->generateKodeBarangInternal();
        }

        $idAlt = $this->alternatifModel->insert([
            'kode_alternatif' => $kode,
            'nama_alternatif' => $nama,
            'kategori_barang' => $item['kategori_barang'] ?: 'RKA Sub Unit',
            'jenis_barang'    => $item['jenis_barang'] ?: 'alat',
            'spesifikasi'     => $item['spesifikasi'] ?: null,
            'satuan'          => $item['satuan'] ?: 'unit',
            'stok'            => 0,
            'stok_minimum'    => 0,
            'kondisi_barang'  => 'baik',
            'estimasi_harga'  => (float) ($item['estimasi_harga_satuan'] ?? 0),
            'keterangan'      => 'Dibuat otomatis dari barang pada dokumen RKA Sub Unit',
            'is_active'       => 1,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ], true);

        if (!$idAlt) {
            throw new \RuntimeException('Gagal membuat data barang dari RKA: ' . $nama);
        }

        return (int) $idAlt;
    }

    private function sinkronkanAlternatifDariRka(int $idAlt, array $alt, array $item): void
    {
        $update = [];

        if (empty($alt['spesifikasi']) && !empty($item['spesifikasi'])) {
            $update['spesifikasi'] = $item['spesifikasi'];
        }

        if ((empty($alt['satuan']) || $alt['satuan'] === 'unit') && !empty($item['satuan'])) {
            $update['satuan'] = $item['satuan'];
        }

        if ((float) ($alt['estimasi_harga'] ?? 0) <= 0 && (float) ($item['estimasi_harga_satuan'] ?? 0) > 0) {
            $update['estimasi_harga'] = (float) $item['estimasi_harga_satuan'];
        }

        if (!empty($update)) {
            $update['updated_at'] = date('Y-m-d H:i:s');
            $this->alternatifModel->update($idAlt, $update);
        }
    }

    private function buildHeaderMap(array $header): array
    {
        $map = [];

        foreach ($header as $index => $value) {
            $key = $this->normalizeHeader((string) $value);

            if ($key !== '') {
                $map[$key] = $index;
            }
        }

        return $map;
    }

    private function getCellByAliases(array $row, array $map, array $aliases, ?int $fallbackIndex = null): string
    {
        foreach ($aliases as $alias) {
            $key = $this->normalizeHeader($alias);

            if (isset($map[$key])) {
                return (string) ($row[$map[$key]] ?? '');
            }
        }

        if ($fallbackIndex !== null && array_key_exists($fallbackIndex, $row)) {
            return (string) ($row[$fallbackIndex] ?? '');
        }

        return '';
    }

    private function normalizeHeader(string $value): string
    {
        $value = strtolower(trim($value));
        return preg_replace('/[^a-z0-9]+/', '', $value) ?? '';
    }

    private function cleanString($value): string
    {
        $value = trim((string) $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return $value ?? '';
    }

    private function normalizeNumber($value): float
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 0;
        }

        $value = str_replace(['Rp', 'rp', 'IDR', 'idr', ' '], '', $value);

        if (preg_match('/,\d{1,2}$/', $value)) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace(',', '', $value);
        }

        $value = preg_replace('/[^0-9.\-]/', '', $value);

        return is_numeric($value) ? (float) $value : 0;
    }

    private function rowKosong(array $row): bool
    {
        foreach ($row as $cell) {
            if ($this->cleanString($cell) !== '') {
                return false;
            }
        }

        return true;
    }

    private function isBarisNonBarang(string $nama): bool
    {
        $value = strtolower($nama);

        return str_contains($value, 'total')
            || str_contains($value, 'jumlah keseluruhan')
            || str_contains($value, 'rencana kebutuhan anggaran')
            || str_contains($value, 'nama barang')
            || str_contains($value, 'uraian barang');
    }

    private function gabungkanItemRkaDuplikat(array $items): array
    {
        $merged = [];

        foreach ($items as $item) {
            $key = strtolower(trim(
                ($item['nama_alternatif'] ?? '') . '|' .
                ($item['spesifikasi'] ?? '') . '|' .
                ($item['satuan'] ?? '') . '|' .
                ($item['estimasi_harga_satuan'] ?? 0)
            ));

            if (!isset($merged[$key])) {
                $merged[$key] = $item;
                continue;
            }

            $merged[$key]['jumlah'] += (int) $item['jumlah'];

            if (empty($merged[$key]['alasan_kebutuhan']) && !empty($item['alasan_kebutuhan'])) {
                $merged[$key]['alasan_kebutuhan'] = $item['alasan_kebutuhan'];
            }
        }

        return array_values($merged);
    }

    private function generateKodeBarangInternal(): string
    {
        $prefix = 'BRG' . date('ymd');
        $next   = 1;

        $last = $this->alternatifModel
            ->like('kode_alternatif', $prefix, 'after')
            ->orderBy('kode_alternatif', 'DESC')
            ->first();

        if ($last && !empty($last['kode_alternatif'])) {
            $next = ((int) substr($last['kode_alternatif'], -3)) + 1;
        }

        do {
            $kode   = $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
            $exists = $this->alternatifModel->where('kode_alternatif', $kode)->first();
            $next++;
        } while ($exists);

        return $kode;
    }

    private function generateNomorUsulan(): string
    {
        $prefix = 'UP-' . date('Ymd') . '-';

        $last = $this->usulanModel
            ->like('nomor_usulan', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $next = 1;

        if ($last && !empty($last['nomor_usulan'])) {
            $next = ((int) substr($last['nomor_usulan'], -3)) + 1;
        }

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    private function normalisasiJenisUsulan($jenis): ?string
    {
        $jenis = strtolower(trim((string) $jenis));

        return match ($jenis) {
            'rka' => 'RKA',
            'pesan cepat', 'pesan_cepat', 'pesancepat' => 'Pesan Cepat',
            default => null,
        };
    }

    private function hasPesanCepatItem(): bool
    {
        $idAlternatif = (array) $this->request->getPost('id_alternatif');

        $idAlternatif = array_values(array_filter(
            $idAlternatif,
            static fn ($id) => (int) $id > 0
        ));

        return !empty($idAlternatif);
    }

    private function pastikanDetailTidakKosong(int $idUsulan): void
    {
        $jumlahDetail = $this->detailUsulanModel
            ->where('id_usulan', $idUsulan)
            ->countAllResults();

        if ($jumlahDetail < 1) {
            throw new \RuntimeException('Detail usulan kosong.');
        }
    }

    private function isBandingGudang(array $usulan): bool
    {
        $status         = strtolower((string) ($usulan['status'] ?? ''));
        $statusValidasi = strtolower((string) ($usulan['status_validasi'] ?? ''));

        return $statusValidasi === 'banding_gudang'
            || $statusValidasi === 'banding gudang'
            || $status === 'banding_gudang';
    }

    private function getStatusTampilan(array $usulan): string
    {
        if ($this->isBandingGudang($usulan)) {
            return 'Gudang Mengajukan Banding';
        }

        return ucwords(str_replace('_', ' ', (string) ($usulan['status'] ?? 'draft')));
    }

    private function bolehDiperbarui(array $usulan): bool
    {
        $status = strtolower((string) ($usulan['status'] ?? ''));

        return in_array($status, ['draft', 'dikembalikan', 'banding_gudang'], true)
            || $this->isBandingGudang($usulan);
    }
}