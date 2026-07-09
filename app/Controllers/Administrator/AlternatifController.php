<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AlternatifController extends BaseController
{
    protected $alternatifModel;

    public function __construct()
    {
        $this->alternatifModel = new AlternatifModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        $alternatif = $this->alternatifModel
            ->whereIn('jenis_barang', ['alat', 'material'])
            ->orderBy('jenis_barang', 'ASC')
            ->orderBy('kode_alternatif', 'ASC')
            ->findAll();

        return view('Administrator/alternatif/index', [
            'title'      => 'Data Alternatif',
            'alternatif' => $alternatif,
        ]);
    }

    public function edit($id)
    {
        $alternatif = $this->alternatifModel->find($id);

        if (!$alternatif) {
            throw PageNotFoundException::forPageNotFound('Data alternatif tidak ditemukan.');
        }

        return view('Administrator/alternatif/form', [
            'title'      => 'Edit Alternatif',
            'alternatif' => $alternatif,
            'action'     => site_url('administrator/alternatif/update/' . $id),
        ]);
    }

    public function update($id)
    {
        $alternatif = $this->alternatifModel->find($id);

        if (!$alternatif) {
            throw PageNotFoundException::forPageNotFound('Data alternatif tidak ditemukan.');
        }

        $kode = trim($this->request->getPost('kode_alternatif'));

        $cekKode = $this->alternatifModel
            ->where('kode_alternatif', $kode)
            ->where('id !=', $id)
            ->first();

        if ($cekKode) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode alternatif ' . $kode . ' sudah digunakan.');
        }

        $this->alternatifModel->update($id, [
            'kode_alternatif' => $kode,
            'nama_alternatif' => $this->request->getPost('nama_alternatif'),
            'kategori_barang' => $this->request->getPost('kategori_barang'),
            'spesifikasi'     => $this->request->getPost('spesifikasi'),
            'satuan'          => $this->request->getPost('satuan'),
            'estimasi_harga'  => $this->request->getPost('estimasi_harga') ?? 0,
            'keterangan'      => $this->request->getPost('keterangan'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('administrator/alternatif'))
            ->with('success', 'Data alternatif berhasil diperbarui.');
    }

    public function delete($id)
    {
        $alternatif = $this->alternatifModel->find($id);

        if (!$alternatif) {
            throw PageNotFoundException::forPageNotFound('Data alternatif tidak ditemukan.');
        }

        $this->alternatifModel->delete($id);

        return redirect()->to(site_url('administrator/alternatif'))
            ->with('success', 'Data alternatif berhasil dihapus.');
    }
}