<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use App\Models\PerbaikanAlatModel;
use App\Models\LogAktivitasModel;

class PerbaikanAlatController extends BaseController
{
    protected PerbaikanAlatModel $perbaikanModel;
    protected AlternatifModel $alternatifModel;
    protected LogAktivitasModel $logModel;

    public function __construct()
    {
        $this->perbaikanModel = new PerbaikanAlatModel();
        $this->alternatifModel = new AlternatifModel();
        $this->logModel = new LogAktivitasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Perbaikan Alat Operasional',
            'perbaikan' => $this->perbaikanModel->getPerbaikanLengkap(),
        ];

        return view('Administrator/perbaikan_alat/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Perbaikan Alat',
            'action' => site_url('administrator/master-data/perbaikan-alat/store'),
            'mode' => 'create',
            'perbaikan' => [],
            'alat' => $this->alternatifModel
                ->where('jenis_barang', 'alat')
                ->where('is_active', 1)
                ->orderBy('nama_alternatif', 'ASC')
                ->findAll(),
        ];

        return view('Administrator/perbaikan_alat/form', $data);
    }

    public function store()
    {
        $rules = [
            'id_alternatif' => 'required|numeric',
            'unit_pemakai' => 'required',
            'tanggal_perbaikan' => 'required|valid_date',
            'kerusakan' => 'required',
            'status_perbaikan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Data perbaikan belum lengkap. Silakan periksa kembali form.');
        }

        $idAlternatif = (int) $this->request->getPost('id_alternatif');

        $this->perbaikanModel->insert([
            'id_alternatif' => $idAlternatif,
            'unit_pemakai' => $this->request->getPost('unit_pemakai'),
            'lokasi_unit' => $this->request->getPost('lokasi_unit'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'tanggal_perbaikan' => $this->request->getPost('tanggal_perbaikan'),
            'tanggal_target' => $this->request->getPost('tanggal_target'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'kerusakan' => $this->request->getPost('kerusakan'),
            'tindakan_perbaikan' => $this->request->getPost('tindakan_perbaikan'),
            'biaya_perbaikan' => $this->request->getPost('biaya_perbaikan') ?: 0,
            'prioritas' => $this->request->getPost('prioritas') ?: 'sedang',
            'status_perbaikan' => $this->request->getPost('status_perbaikan'),
            'catatan' => $this->request->getPost('catatan'),
        ]);

        $this->alternatifModel->update($idAlternatif, [
            'kondisi_barang' => 'diperbaiki',
        ]);

        $alat = $this->alternatifModel->find($idAlternatif);

        $this->logModel->simpanLog(
            'Menambahkan data perbaikan alat',
            'Alat ' . ($alat['nama_alternatif'] ?? '-') . ' diperbaiki oleh unit ' . $this->request->getPost('unit_pemakai'),
            'Perbaikan Alat'
        );

        return redirect()
            ->to(site_url('administrator/master-data/perbaikan-alat'))
            ->with('success', 'Data perbaikan alat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $perbaikan = $this->perbaikanModel->find($id);

        if (!$perbaikan) {
            return redirect()
                ->to(site_url('administrator/master-data/perbaikan-alat'))
                ->with('error', 'Data perbaikan alat tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Perbaikan Alat',
            'action' => site_url('administrator/master-data/perbaikan-alat/update/' . $id),
            'mode' => 'edit',
            'perbaikan' => $perbaikan,
            'alat' => $this->alternatifModel
                ->where('jenis_barang', 'alat')
                ->where('is_active', 1)
                ->orderBy('nama_alternatif', 'ASC')
                ->findAll(),
        ];

        return view('Administrator/perbaikan_alat/form', $data);
    }

    public function update($id)
    {
        $perbaikan = $this->perbaikanModel->find($id);

        if (!$perbaikan) {
            return redirect()
                ->to(site_url('administrator/master-data/perbaikan-alat'))
                ->with('error', 'Data perbaikan alat tidak ditemukan.');
        }

        $rules = [
            'id_alternatif' => 'required|numeric',
            'unit_pemakai' => 'required',
            'tanggal_perbaikan' => 'required|valid_date',
            'kerusakan' => 'required',
            'status_perbaikan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Data perbaikan belum lengkap. Silakan periksa kembali form.');
        }

        $idAlternatif = (int) $this->request->getPost('id_alternatif');
        $status = $this->request->getPost('status_perbaikan');

        $this->perbaikanModel->update($id, [
            'id_alternatif' => $idAlternatif,
            'unit_pemakai' => $this->request->getPost('unit_pemakai'),
            'lokasi_unit' => $this->request->getPost('lokasi_unit'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
            'tanggal_perbaikan' => $this->request->getPost('tanggal_perbaikan'),
            'tanggal_target' => $this->request->getPost('tanggal_target'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'kerusakan' => $this->request->getPost('kerusakan'),
            'tindakan_perbaikan' => $this->request->getPost('tindakan_perbaikan'),
            'biaya_perbaikan' => $this->request->getPost('biaya_perbaikan') ?: 0,
            'prioritas' => $this->request->getPost('prioritas') ?: 'sedang',
            'status_perbaikan' => $status,
            'catatan' => $this->request->getPost('catatan'),
        ]);

        if ($status === 'selesai') {
            $this->alternatifModel->update($idAlternatif, [
                'kondisi_barang' => 'baik',
            ]);
        } else {
            $this->alternatifModel->update($idAlternatif, [
                'kondisi_barang' => 'diperbaiki',
            ]);
        }

        $this->logModel->simpanLog(
            'Memperbarui data perbaikan alat',
            'Data perbaikan alat pada unit ' . $this->request->getPost('unit_pemakai') . ' diperbarui.',
            'Perbaikan Alat'
        );

        return redirect()
            ->to(site_url('administrator/master-data/perbaikan-alat'))
            ->with('success', 'Data perbaikan alat berhasil diperbarui.');
    }

    public function selesai($id)
    {
        $perbaikan = $this->perbaikanModel->find($id);

        if (!$perbaikan) {
            return redirect()
                ->to(site_url('administrator/master-data/perbaikan-alat'))
                ->with('error', 'Data perbaikan alat tidak ditemukan.');
        }

        $this->perbaikanModel->update($id, [
            'status_perbaikan' => 'selesai',
            'tanggal_selesai' => date('Y-m-d'),
        ]);

        $this->alternatifModel->update($perbaikan['id_alternatif'], [
            'kondisi_barang' => 'baik',
        ]);

        $this->logModel->simpanLog(
            'Menyelesaikan perbaikan alat',
            'Perbaikan alat pada unit ' . ($perbaikan['unit_pemakai'] ?? '-') . ' telah diselesaikan.',
            'Perbaikan Alat'
        );

        return redirect()
            ->to(site_url('administrator/master-data/perbaikan-alat'))
            ->with('success', 'Status perbaikan alat berhasil diselesaikan.');
    }

    public function delete($id)
    {
        $perbaikan = $this->perbaikanModel->find($id);

        if (!$perbaikan) {
            return redirect()
                ->to(site_url('administrator/master-data/perbaikan-alat'))
                ->with('error', 'Data perbaikan alat tidak ditemukan.');
        }

        $this->perbaikanModel->delete($id);

        $this->logModel->simpanLog(
            'Menghapus data perbaikan alat',
            'Data perbaikan alat pada unit ' . ($perbaikan['unit_pemakai'] ?? '-') . ' dihapus.',
            'Perbaikan Alat'
        );

        return redirect()
            ->to(site_url('administrator/master-data/perbaikan-alat'))
            ->with('success', 'Data perbaikan alat berhasil dihapus.');
    }
}