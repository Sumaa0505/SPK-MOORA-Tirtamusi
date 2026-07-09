<?php

namespace App\Controllers\Pengadaan;

use App\Controllers\BaseController;
use App\Models\PengadaanDokumenModel;
use App\Models\PengadaanPembelianModel;
use App\Models\RiwayatValidasiModel;

class DokumenController extends BaseController
{
    protected PengadaanDokumenModel $dokumenModel;
    protected PengadaanPembelianModel $pengadaanModel;
    protected RiwayatValidasiModel $riwayatModel;

    public function __construct()
    {
        $this->dokumenModel  = new PengadaanDokumenModel();
        $this->pengadaanModel = new PengadaanPembelianModel();
        $this->riwayatModel  = new RiwayatValidasiModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $pengadaan = $db->table('pengadaan_pembelian pp')
            ->select('pp.*, up.nomor_usulan, up.unit_pengusul')
            ->join('usulan_pengadaan up', 'up.id = pp.id_usulan', 'left')
            ->orderBy('pp.id', 'DESC')
            ->get()->getResultArray();

        $dokumen = $db->table('pengadaan_dokumen pd')
            ->select('pd.*, pp.nomor_pengadaan, up.nomor_usulan, up.unit_pengusul')
            ->join('pengadaan_pembelian pp', 'pp.id = pd.id_pengadaan', 'left')
            ->join('usulan_pengadaan up', 'up.id = pd.id_usulan', 'left')
            ->orderBy('pd.uploaded_at', 'DESC')
            ->get()->getResultArray();

        return view('Pengadaan/dokumen/index', [
            'title'     => 'Dokumen Pengadaan',
            'pengadaan' => $pengadaan,
            'dokumen'   => $dokumen,
        ]);
    }

    public function upload()
    {
        $idPengadaan = (int) $this->request->getPost('id_pengadaan');
        $pengadaan = $this->pengadaanModel->find($idPengadaan);
        if (!$pengadaan) {
            return redirect()->back()->with('error', 'Data pengadaan tidak ditemukan.');
        }

        $file = $this->request->getFile('file_dokumen');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File dokumen wajib diupload.');
        }

        $allowedExt = ['pdf', 'jpg', 'jpeg', 'png', 'xlsx', 'xls', 'doc', 'docx'];
        if (!in_array(strtolower($file->getClientExtension()), $allowedExt, true)) {
            return redirect()->back()->with('error', 'Format file tidak didukung.');
        }

        $uploadPath = WRITEPATH . 'uploads/pengadaan';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $this->dokumenModel->insert([
            'id_pengadaan'  => $idPengadaan,
            'id_usulan'     => (int) $pengadaan['id_usulan'],
            'jenis_dokumen' => $this->request->getPost('jenis_dokumen') ?: 'lainnya',
            'nomor_dokumen' => trim((string) $this->request->getPost('nomor_dokumen')) ?: null,
            'nama_file'     => $file->getClientName(),
            'file_path'     => 'writable/uploads/pengadaan/' . $newName,
            'mime_type'     => $file->getClientMimeType(),
            'uploaded_by'   => $this->currentUserId(),
            'uploaded_at'   => date('Y-m-d H:i:s'),
            'catatan'       => trim((string) $this->request->getPost('catatan')) ?: null,
        ]);

        $this->riwayatModel->insert([
            'id_usulan'    => (int) $pengadaan['id_usulan'],
            'id_user'      => $this->currentUserId(),
            'role_user'    => 'pengadaan',
            'aksi'         => 'upload_dokumen',
            'catatan'      => 'Upload dokumen pengadaan: ' . $file->getClientName(),
            'tanggal_aksi' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('pengadaan/dokumen'))->with('success', 'Dokumen pengadaan berhasil diupload.');
    }


    public function file(string $filename)
    {
        $safeName = basename($filename);
        $path = WRITEPATH . 'uploads/pengadaan/' . $safeName;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen pengadaan tidak ditemukan.');
        }

        return $this->response->download($path, null)->setFileName($safeName);
    }
}
