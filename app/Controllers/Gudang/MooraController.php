<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Services\MooraEngine;
use App\Services\MooraResultQueryService;
use App\Models\UsulanPengadaanModel;

class MooraController extends BaseController
{
    public function index()
    {
        $usulanModel = new UsulanPengadaanModel();
        $mooraQuery = new MooraResultQueryService();

        $usulanList = $usulanModel
            ->select('usulan_pengadaan.*, users.nama_lengkap AS nama_pengusul, COUNT(detail_usulan.id) AS jumlah_item')
            ->join('users', 'users.id = usulan_pengadaan.id_user_pengusul', 'left')
            ->join('detail_usulan', 'detail_usulan.id_usulan = usulan_pengadaan.id', 'left')
            ->whereIn('usulan_pengadaan.status', ['diajukan', 'diverifikasi', 'moora_selesai'])
            ->groupBy('usulan_pengadaan.id')
            ->orderBy('usulan_pengadaan.updated_at', 'DESC')
            ->findAll(200);

        foreach ($usulanList as &$row) {
            $latest = $mooraQuery->activeLatestByUsulan((int) $row['id']);
            $row['terakhir_dihitung'] = $latest[0]['tanggal_hitung'] ?? null;
            $row['mode_hitung'] = $latest[0]['mode_hitung'] ?? null;
        }
        unset($row);

        return view('Gudang/penilaian/index', [
            'title' => 'Auto Fix Engine MOORA Gudang',
            'usulanList' => $usulanList,
        ]);
    }

    public function proses($id)
    {
        $engine = new MooraEngine();
        $result = $engine->process((int) $id);

        if (!empty($result['status'])) {
            return redirect()
                ->to(site_url('gudang/hasil-moora/detail/' . (int) $id))
                ->with('success', $result['message'] ?? 'MOORA berhasil diproses.');
        }

        return redirect()
            ->back()
            ->with('error', $result['message'] ?? 'MOORA gagal diproses.');
    }

    public function process($id)
    {
        $engine = new MooraEngine();
        return $this->response->setJSON($engine->process((int) $id));
    }
}
