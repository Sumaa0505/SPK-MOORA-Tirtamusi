<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;
use App\Models\LogAktivitasModel;
use App\Models\SettingModel;

class SettingController extends BaseController
{
    protected SettingModel $settingModel;
    protected KriteriaModel $kriteriaModel;
    protected LogAktivitasModel $logModel;

    public function __construct()
    {
        helper(['form', 'url']);

        $this->settingModel  = new SettingModel();
        $this->kriteriaModel = new KriteriaModel();
        $this->logModel      = new LogAktivitasModel();
    }

    public function index()
    {
        $kriteria = $this->kriteriaModel
            ->orderBy('kode_kriteria', 'ASC')
            ->findAll();

        $totalBobot = 0;
        foreach ($kriteria as $row) {
            if ((int) ($row['is_active'] ?? 1) === 1) {
                $totalBobot += (float) ($row['bobot'] ?? 0);
            }
        }

        return view('Administrator/setting/index', [
            'title'      => 'Setting Sistem',
            'settings'   => $this->settingModel->getMap(),
            'kriteria'   => $kriteria,
            'totalBobot' => $totalBobot,
            'tableReady' => \Config\Database::connect()->tableExists('setting_sistem'),
        ]);
    }

    public function updateSistem()
    {
        if (!\Config\Database::connect()->tableExists('setting_sistem')) {
            return redirect()->back()->with('error', 'Tabel setting_sistem belum ada. Jalankan SQL migrasi yang saya berikan terlebih dahulu.');
        }

        $rules = [
            'nama_perusahaan'     => 'required|min_length[3]',
            'nama_aplikasi'       => 'required|min_length[3]',
            'moora_mode'          => 'required|in_list[per_usulan,per_periode]',
            'moora_status_dataset'=> 'required',
            'moora_cost_mode'     => 'required|in_list[standard_moora,inverse_before_weight]',
            'moora_auto_recalculate' => 'required|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Setting sistem belum valid. Periksa kembali isian Anda.');
        }

        $payload = [
            'nama_perusahaan'         => trim((string) $this->request->getPost('nama_perusahaan')),
            'nama_aplikasi'           => trim((string) $this->request->getPost('nama_aplikasi')),
            'moora_mode'              => trim((string) $this->request->getPost('moora_mode')),
            'moora_status_dataset'    => trim((string) $this->request->getPost('moora_status_dataset')),
            'moora_cost_mode'         => trim((string) $this->request->getPost('moora_cost_mode')),
            'moora_auto_recalculate'  => trim((string) $this->request->getPost('moora_auto_recalculate')),
        ];

        foreach ($payload as $key => $value) {
            $this->settingModel->setValue($key, $value, [
                'setting_group' => str_starts_with($key, 'moora_') ? 'moora' : 'umum',
                'setting_type'  => 'text',
            ]);
        }

        $this->logModel->simpanLog('Update Setting Sistem', 'Administrator memperbarui konfigurasi umum dan MOORA.', 'Administrator');

        return redirect()->to(site_url('administrator/setting'))->with('success', 'Setting sistem berhasil diperbarui.');
    }

    public function updateBobot()
    {
        $bobot = $this->request->getPost('bobot') ?? [];
        $jenis = $this->request->getPost('jenis') ?? [];
        $aktif = $this->request->getPost('is_active') ?? [];

        if (empty($bobot) || !is_array($bobot)) {
            return redirect()->back()->with('error', 'Data bobot tidak ditemukan.');
        }

        $kriteria = $this->kriteriaModel->findAll();
        $totalBobotAktif = 0;

        foreach ($kriteria as $row) {
            $id = (int) $row['id'];
            $isActive = isset($aktif[$id]) ? 1 : 0;
            $nilaiBobot = isset($bobot[$id]) ? (float) str_replace(',', '.', (string) $bobot[$id]) : 0;

            if ($nilaiBobot < 0 || $nilaiBobot > 1) {
                return redirect()->back()->withInput()->with('error', 'Bobot ' . ($row['kode_kriteria'] ?? '') . ' harus berada pada rentang 0 sampai 1.');
            }

            if ($isActive === 1) {
                $totalBobotAktif += $nilaiBobot;
            }
        }

        if (abs($totalBobotAktif - 1.0) > 0.00001) {
            return redirect()->back()->withInput()->with('error', 'Total bobot kriteria aktif harus tepat 1.00. Total saat ini: ' . number_format($totalBobotAktif, 4));
        }

        foreach ($kriteria as $row) {
            $id = (int) $row['id'];
            $this->kriteriaModel->update($id, [
                'bobot'     => (float) str_replace(',', '.', (string) ($bobot[$id] ?? 0)),
                'jenis'     => in_array(($jenis[$id] ?? 'benefit'), ['benefit', 'cost'], true) ? $jenis[$id] : 'benefit',
                'is_active' => isset($aktif[$id]) ? 1 : 0,
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }

        $this->logModel->simpanLog('Update Bobot MOORA', 'Administrator memperbarui bobot dan jenis kriteria. Total bobot aktif: ' . number_format($totalBobotAktif, 4), 'Administrator');

        return redirect()->to(site_url('administrator/setting'))->with('success', 'Bobot dan jenis kriteria MOORA berhasil diperbarui.');
    }

    public function resetDefault()
    {
        $defaults = [
            'nama_perusahaan'        => 'Perumda Tirta Musi Palembang',
            'nama_aplikasi'          => 'SPK MOORA Pengadaan Barang',
            'moora_mode'             => 'per_usulan',
            'moora_status_dataset'   => 'diverifikasi',
            'moora_cost_mode'        => 'standard_moora',
            'moora_auto_recalculate' => '1',
        ];

        if (!\Config\Database::connect()->tableExists('setting_sistem')) {
            return redirect()->back()->with('error', 'Tabel setting_sistem belum ada. Jalankan SQL migrasi terlebih dahulu.');
        }

        foreach ($defaults as $key => $value) {
            $this->settingModel->setValue($key, $value, [
                'setting_group' => str_starts_with($key, 'moora_') ? 'moora' : 'umum',
                'setting_type'  => 'text',
            ]);
        }

        $this->logModel->simpanLog('Reset Setting Sistem', 'Administrator mengembalikan konfigurasi sistem ke default.', 'Administrator');

        return redirect()->to(site_url('administrator/setting'))->with('success', 'Setting sistem berhasil dikembalikan ke default.');
    }
}
