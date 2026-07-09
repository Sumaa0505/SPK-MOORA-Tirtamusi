<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use App\Models\LogAktivitasModel;

class PengambilanController extends BaseController
{
    protected $alternatifModel;
    protected $logModel;

    public function __construct()
    {
        $this->alternatifModel = new AlternatifModel();
        $this->logModel = new LogAktivitasModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        $barang = $this->alternatifModel
            ->orderBy('nama_alternatif', 'ASC')
            ->findAll();

        return view('Gudang/pengambilan/index', [
            'title'  => 'Pengambilan Barang',
            'barang' => $barang,
        ]);
    }

    public function create()
    {
        return redirect()->to(site_url('gudang/pengambilan'));
    }

    public function store()
    {
        $idBarang = (int) $this->request->getPost('id_barang');
        $jumlah   = (int) $this->request->getPost('jumlah');
        $unit     = trim((string) $this->request->getPost('unit_pengambil'));
        $userLogin = session()->get('nama_lengkap') ?? 'Seksi Gudang';
        $catatan  = trim((string) $this->request->getPost('catatan'));

        if ($idBarang <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Barang wajib dipilih.');
        }

        if ($jumlah <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah pengambilan harus lebih dari 0.');
        }

        $barang = $this->alternatifModel->find($idBarang);

        if (!$barang) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data barang tidak ditemukan.');
        }

        $stokLama = (int) ($barang['stok'] ?? 0);

        if ($stokLama <= 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Barang tidak dapat diambil karena stok habis.');
        }

        if ($jumlah > $stokLama) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Jumlah pengambilan melebihi stok tersedia. Stok saat ini hanya ' . $stokLama . ' ' . ($barang['satuan'] ?? '') . '.');
        }

        $stokBaru = $stokLama - $jumlah;

        $this->alternatifModel->update($idBarang, [
            'stok'       => $stokBaru,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        
        $this->logModel->simpanLog(
        'Pengambilan Barang',
        'User: ' . $userLogin .
        '. Mengeluarkan barang: ' . ($barang['nama_alternatif'] ?? '-') .
        '. Jumlah diambil: ' . $jumlah . ' ' . ($barang['satuan'] ?? '') .
        '. Tujuan/Unit penerima: ' . ($unit ?: '-') .
        '. Stok awal: ' . $stokLama .
        '. Stok akhir: ' . $stokBaru .
        '. Catatan: ' . ($catatan ?: '-'),
        'Gudang'
        );

        return redirect()->to(site_url('gudang/pengambilan'))
            ->with('success', 'Pengambilan barang berhasil dicatat. Stok barang otomatis berkurang.');
    }
}

