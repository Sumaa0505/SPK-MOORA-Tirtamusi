<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;

class MasterDataBarangController extends BaseController
{
    protected $alternatifModel;

    public function __construct()
    {
        $this->alternatifModel = new AlternatifModel();
        helper(['url', 'form']);
    }

    public function alat()
    {
        return $this->tampilData('alat', 'Master Data Alat Operasional');
    }

    public function material()
    {
        return $this->tampilData('material', 'Master Data Material');
    }

    public function aset()
    {
        return $this->tampilData('aset', 'Master Data Aset');
    }

    public function createAlat()
{
    return view('Administrator/master_data/form_alat', [
        'title' => 'Tambah Data Alat'
    ]);
}

public function storeAlat()
{
    $kode = trim($this->request->getPost('kode_alternatif'));

    $cekKode = $this->alternatifModel
        ->where('kode_alternatif', $kode)
        ->first();

    if ($cekKode) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Kode alat sudah digunakan.');
    }

    $this->alternatifModel->insert([
        'kode_alternatif' => $kode,
        'nama_alternatif' => $this->request->getPost('nama_alternatif'),
        'jenis_barang'    => 'alat',
        'kategori_barang' => $this->request->getPost('kategori_barang'),
        'spesifikasi'     => $this->request->getPost('spesifikasi'),
        'satuan'          => $this->request->getPost('satuan'),
        'estimasi_harga'  => $this->request->getPost('estimasi_harga') ?? 0,
        'keterangan'      => $this->request->getPost('keterangan'),
        'is_active'       => 1,
        'created_at'      => date('Y-m-d H:i:s'),
        'updated_at'      => date('Y-m-d H:i:s'),
    ]);

    return redirect()->to(site_url('administrator/master-data/alat'))
        ->with('success', 'Data alat berhasil ditambahkan.');
}

public function createMaterial()
{
    return view('Administrator/master_data/form_material', [
        'title' => 'Tambah Data Material'
    ]);
}

public function storeMaterial()
{
    $kode = trim($this->request->getPost('kode_alternatif'));

    $cekKode = $this->alternatifModel
        ->where('kode_alternatif', $kode)
        ->first();

    if ($cekKode) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Kode material sudah digunakan.');
    }

    $this->alternatifModel->insert([
        'kode_alternatif' => $kode,
        'nama_alternatif' => $this->request->getPost('nama_alternatif'),
        'jenis_barang'    => 'material',
        'kategori_barang' => $this->request->getPost('kategori_barang'),
        'spesifikasi'     => $this->request->getPost('spesifikasi'),
        'satuan'          => $this->request->getPost('satuan'),
        'estimasi_harga'  => $this->request->getPost('estimasi_harga') ?? 0,
        'keterangan'      => $this->request->getPost('keterangan'),
        'is_active'       => 1,
        'created_at'      => date('Y-m-d H:i:s'),
        'updated_at'      => date('Y-m-d H:i:s'),
    ]);

    return redirect()->to(site_url('administrator/master-data/material'))
        ->with('success', 'Data material berhasil ditambahkan.');
}

    private function tampilData($jenis, $title)
    {
        $dataBarang = $this->alternatifModel
            ->where('jenis_barang', $jenis)
            ->orderBy('kode_alternatif', 'ASC')
            ->findAll();

        return view('Administrator/master_data/index', [
            'title'      => $title,
            'jenis'      => $jenis,
            'dataBarang' => $dataBarang,
        ]);
    }
}