<?php

namespace App\Controllers\Gudang;

use App\Controllers\BaseController;
use App\Models\AlternatifModel;
use App\Models\LogAktivitasModel;

class StokController extends BaseController
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
        $keyword = $this->request->getGet('keyword');
        $status  = $this->request->getGet('status');

        $builder = $this->alternatifModel;

        if (!empty($keyword)) {
            $builder = $builder
                ->groupStart()
                ->like('kode_alternatif', $keyword)
                ->orLike('nama_alternatif', $keyword)
                ->orLike('kategori_barang', $keyword)
                ->groupEnd();
        }

        $barang = $builder
            ->orderBy('nama_alternatif', 'ASC')
            ->findAll();

        if (!empty($status)) {
            $barang = array_filter($barang, function ($row) use ($status) {
                $stok = (int) ($row['stok'] ?? 0);
                $min  = (int) ($row['stok_minimum'] ?? 0);

                if ($status === 'habis') {
                    return $stok <= 0;
                }

                if ($status === 'minimum') {
                    return $stok > 0 && $min > 0 && $stok <= $min;
                }

                if ($status === 'aman') {
                    return $stok > $min;
                }

                return true;
            });
        }

        return view('Gudang/stok/index', [
            'title'   => 'Data Stok Gudang',
            'barang'  => $barang,
            'keyword' => $keyword,
            'status'  => $status,
        ]);
    }

    public function detail($id)
    {
        $barang = $this->alternatifModel->find($id);

        if (!$barang) {
            return redirect()->to(site_url('gudang/stok'))
                ->with('error', 'Data barang tidak ditemukan.');
        }

        return view('Gudang/stok/detail', [
            'title'  => 'Detail Stok Barang',
            'barang' => $barang,
        ]);
    }

    public function updateDetail($id)
    {
        $barang = $this->alternatifModel->find($id);

        if (!$barang) {
            return redirect()->to(site_url('gudang/stok'))
                ->with('error', 'Data barang tidak ditemukan.');
        }

        $stok = (int) $this->request->getPost('stok');
        $stokMinimum = (int) $this->request->getPost('stok_minimum');

        if ($stok < 0 || $stokMinimum < 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Stok dan stok minimum tidak boleh bernilai negatif.');
        }

        $stokLama = (int) ($barang['stok'] ?? 0);
        $minimumLama = (int) ($barang['stok_minimum'] ?? 0);

        $this->alternatifModel->update($id, [
            'stok'         => $stok,
            'stok_minimum' => $stokMinimum,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->logModel->simpanLog(
            'Update Stok Barang',
            'Mengubah stok barang ' . ($barang['nama_alternatif'] ?? '-') .
            '. Stok awal: ' . $stokLama .
            ', stok baru: ' . $stok .
            '. Minimum awal: ' . $minimumLama .
            ', minimum baru: ' . $stokMinimum . '.',
            'gudang'
        );

        return redirect()->to(site_url('gudang/stok/detail/' . $id))
            ->with('success', 'Data stok barang berhasil diperbarui.');
    }

    public function opname()
    {
        $barang = $this->alternatifModel
            ->orderBy('nama_alternatif', 'ASC')
            ->findAll();

        return view('Gudang/stok/opname', [
            'title'  => 'Stock Opname',
            'barang' => $barang,
        ]);
    }

    public function updateMinimum($id)
    {
        $barang = $this->alternatifModel->find($id);

        if (!$barang) {
            return redirect()->back()
                ->with('error', 'Data barang tidak ditemukan.');
        }

        $stok = (int) $this->request->getPost('stok');
        $stokMinimum = (int) $this->request->getPost('stok_minimum');

        $stokLama = (int) ($barang['stok'] ?? 0);
        $minimumLama = (int) ($barang['stok_minimum'] ?? 0);

        $this->alternatifModel->update($id, [
            'stok'         => $stok,
            'stok_minimum' => $stokMinimum,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->logModel->simpanLog(
            'Stock Opname',
            'Melakukan stock opname barang ' . ($barang['nama_alternatif'] ?? '-') .
            '. Stok sistem awal: ' . $stokLama .
            ', stok fisik baru: ' . $stok .
            '. Minimum awal: ' . $minimumLama .
            ', minimum baru: ' . $stokMinimum . '.',
            'gudang'
        );

        return redirect()->back()
            ->with('success', 'Data stok berhasil diperbarui.');
    }
}