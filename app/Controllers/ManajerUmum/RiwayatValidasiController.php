<?php
namespace App\Controllers\ManajerUmum;

use App\Controllers\BaseController;
use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;

class RiwayatValidasiController extends BaseController
{
    protected $riwayatValidasiModel;
    protected $usulanModel;

    public function __construct()
    {
        $this->riwayatValidasiModel = new RiwayatValidasiModel();
        $this->usulanModel = new UsulanPengadaanModel();
    }

    /**
     * Menampilkan halaman Riwayat Validasi untuk Manajer Umum
     */
    public function index()
    {
        // Ambil semua riwayat validasi, termasuk role kosong atau manajer_umum
        $riwayat = $this->riwayatValidasiModel
            ->select('riwayat_validasi.*, usulan_pengadaan.nomor_usulan, usulan_pengadaan.unit_pengusul')
            ->join('usulan_pengadaan', 'usulan_pengadaan.id = riwayat_validasi.id_usulan', 'left')
            ->groupStart()
                ->where('role_user', 'manajer_umum')
                ->orWhere('role_user', '')
            ->groupEnd()
            ->orderBy('tanggal_aksi', 'DESC')
            ->findAll();

        // Pastikan data selalu array
        if (!$riwayat) {
            $riwayat = [];
        }

        return view('manajer_umum/riwayat_validasi', [
            'title' => 'Riwayat Validasi - Manajer Umum',
            'riwayat' => $riwayat
        ]);
    }
}