<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\UsulanPengadaanModel;
use App\Models\DetailUsulanModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class MonitoringController extends BaseController
{
    protected $usulanModel;
    protected $detailUsulanModel;

    public function __construct()
    {
        $this->usulanModel       = new UsulanPengadaanModel();
        $this->detailUsulanModel = new DetailUsulanModel();

        helper(['url', 'form']);
    }

    public function index()
    {
        $usulan = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->orderBy('usulan_pengadaan.id', 'DESC')
            ->findAll();

        $totalUsulan    = count($usulan);
        $totalMenunggu  = 0;
        $totalDiproses  = 0;
        $totalDisetujui = 0;
        $totalDitolak   = 0;

        foreach ($usulan as &$row) {
            $statusProses   = $this->formatStatus($row['status'] ?? null);
            $statusValidasi = $this->formatStatus($row['status_validasi'] ?? null);

            $row['status_asli']           = $row['status'] ?? '-';
            $row['status_validasi_asli']  = $row['status_validasi'] ?? '-';
            $row['status']                = $statusProses;
            $row['status_validasi']       = $statusValidasi;
            $row['badge_status']          = $this->badgeStatus($statusProses);
            $row['badge_status_validasi'] = $this->badgeStatus($statusValidasi);
            $row['progress']              = $this->formatProgress($statusProses, $statusValidasi);
            $row['badge_progress']        = $this->badgeProgress($row['progress']);

            if ($statusValidasi === 'Disetujui') {
                $totalDisetujui++;
            } elseif ($statusValidasi === 'Ditolak') {
                $totalDitolak++;
            } elseif ($statusProses === 'Diproses') {
                $totalDiproses++;
            } else {
                $totalMenunggu++;
            }
        }

        unset($row);

        return view('Administrator/monitoring/index', [
            'title'          => 'Monitoring Usulan',
            'usulan'         => $usulan,
            'totalUsulan'    => $totalUsulan,
            'totalMenunggu'  => $totalMenunggu,
            'totalDiproses'  => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak'   => $totalDitolak,
        ]);
    }

    public function detail($id)
    {
        $usulan = $this->usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->where('usulan_pengadaan.id', $id)
            ->first();

        if (!$usulan) {
            throw PageNotFoundException::forPageNotFound('Data usulan tidak ditemukan.');
        }

        $usulan['status_asli']          = $usulan['status'] ?? '-';
        $usulan['status_validasi_asli'] = $usulan['status_validasi'] ?? '-';

        $usulan['status']          = $this->formatStatus($usulan['status'] ?? null);
        $usulan['status_validasi'] = $this->formatStatus($usulan['status_validasi'] ?? null);

        $usulan['badge_status']          = $this->badgeStatus($usulan['status']);
        $usulan['badge_status_validasi'] = $this->badgeStatus($usulan['status_validasi']);

        $usulan['progress']       = $this->formatProgress($usulan['status'], $usulan['status_validasi']);
        $usulan['badge_progress'] = $this->badgeProgress($usulan['progress']);

        $detail = $this->detailUsulanModel
            ->select('
                detail_usulan.*,
                alternatif.kode_alternatif,
                alternatif.nama_alternatif,
                alternatif.kategori_barang,
                alternatif.spesifikasi,
                alternatif.satuan,
                alternatif.estimasi_harga
            ')
            ->join('alternatif', 'alternatif.id = detail_usulan.id_alternatif', 'left')
            ->where('detail_usulan.id_usulan', $id)
            ->findAll();

        return view('Administrator/monitoring/detail', [
            'title'  => 'Detail Usulan',
            'usulan' => $usulan,
            'detail' => $detail,
        ]);
    }

    private function formatStatus($status)
    {
        $status = strtolower(trim((string) $status));

        if ($status === '' || $status === '-' || $status === 'null') {
            return 'Menunggu';
        }

        if (in_array($status, ['diajukan', 'menunggu', 'pending', 'baru'])) {
            return 'Menunggu';
        }

        if (in_array($status, ['verifikasi', 'diverifikasi', 'moora_selesai', 'menunggu_direktur_bidang', 'menunggu_direktur_utama', 'menunggu_direktur_umum', 'disposisi_pengadaan', 'diproses_pengadaan', 'menunggu_penerimaan'])) {
            return 'Diproses';
        }

        if (in_array($status, ['disetujui', 'setuju', 'approved'])) {
            return 'Disetujui';
        }

        if (in_array($status, ['ditolak', 'tolak', 'rejected'])) {
            return 'Ditolak';
        }

        return ucwords(str_replace('_', ' ', $status));
    }

    private function badgeStatus($status)
    {
        if ($status === 'Disetujui') {
            return 'success';
        }

        if ($status === 'Ditolak') {
            return 'danger';
        }

        if ($status === 'Diproses') {
            return 'primary';
        }

        if ($status === 'Menunggu') {
            return 'warning text-dark';
        }

        return 'secondary';
    }

    private function formatProgress($statusProses, $statusValidasi)
    {
        if ($statusValidasi === 'Disetujui') {
            return 'Selesai';
        }

        if ($statusValidasi === 'Ditolak') {
            return 'Ditolak';
        }

        if ($statusProses === 'Diproses') {
            return 'Proses MOORA';
        }

        return 'Menunggu';
    }

    private function badgeProgress($progress)
    {
        if ($progress === 'Selesai') {
            return 'success';
        }

        if ($progress === 'Ditolak') {
            return 'danger';
        }

        if ($progress === 'Proses MOORA') {
            return 'info text-dark';
        }

        return 'warning text-dark';
    }
}