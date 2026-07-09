<?php

namespace App\Controllers\Dokumen;

use App\Controllers\BaseController;
use App\Models\QrDisposisiModel;

class VerifikasiController extends BaseController
{
    public function index(string $hash)
    {
        $qr = (new QrDisposisiModel())
            ->select('qr_disposisi.*, dokumen_disposisi.nomor_dokumen, dokumen_disposisi.judul_dokumen, dokumen_disposisi.status_dokumen, dokumen_disposisi.approved_at, usulan_pengadaan.nomor_usulan, usulan_pengadaan.unit_pengusul, usulan_pengadaan.status')
            ->join('dokumen_disposisi', 'dokumen_disposisi.id = qr_disposisi.id_dokumen', 'left')
            ->join('usulan_pengadaan', 'usulan_pengadaan.id = qr_disposisi.id_usulan', 'left')
            ->where('qr_disposisi.qr_hash', $hash)
            ->first();

        return view('Dokumen/verifikasi', [
            'title' => 'Verifikasi Dokumen Disposisi',
            'qr'    => $qr,
            'hash'  => $hash,
        ]);
    }
}
