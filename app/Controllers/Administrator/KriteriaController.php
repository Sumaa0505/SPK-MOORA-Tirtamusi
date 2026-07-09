<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class KriteriaController extends BaseController
{
    protected $kriteriaModel;

    public function __construct()
    {
        $this->kriteriaModel = new KriteriaModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        $kriteria = $this->kriteriaModel->orderBy('kode_kriteria', 'ASC')->findAll();

        $totalBobot = array_sum(array_map(fn($row) => (float) $row['bobot'], $kriteria));

        return view('Administrator/kriteria/index', [
            'title'      => 'Data Kriteria',
            'kriteria'   => $kriteria,
            'totalBobot' => $totalBobot,
        ]);
    }

    public function create()
    {
        return view('Administrator/kriteria/form', [
            'title'    => 'Tambah Kriteria',
            'kriteria' => null,
            'action'   => site_url('administrator/kriteria/store'),
        ]);
    }

    public function store()
    {
        $rules = [
            'kode_kriteria' => 'required',
            'nama_kriteria' => 'required',
            'jenis'         => 'required|in_list[benefit,cost]',
            'bobot'         => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data belum valid.');
        }

        $this->kriteriaModel->insert([
            'kode_kriteria' => $this->request->getPost('kode_kriteria'),
            'nama_kriteria' => $this->request->getPost('nama_kriteria'),
            'jenis'         => $this->request->getPost('jenis'),
            'bobot'         => $this->request->getPost('bobot'),
        ]);

        return redirect()->to(site_url('administrator/kriteria'))
            ->with('success', 'Data kriteria berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kriteria = $this->kriteriaModel->find($id);

        if (!$kriteria) {
            throw PageNotFoundException::forPageNotFound('Data kriteria tidak ditemukan.');
        }

        return view('Administrator/kriteria/form', [
            'title'    => 'Edit Kriteria',
            'kriteria' => $kriteria,
            'action'   => site_url('administrator/kriteria/update/' . $id),
        ]);
    }

    public function update($id)
    {
        $rules = [
            'kode_kriteria' => 'required',
            'nama_kriteria' => 'required',
            'jenis'         => 'required|in_list[benefit,cost]',
            'bobot'         => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data belum valid.');
        }

        $this->kriteriaModel->update($id, [
            'kode_kriteria' => $this->request->getPost('kode_kriteria'),
            'nama_kriteria' => $this->request->getPost('nama_kriteria'),
            'jenis'         => $this->request->getPost('jenis'),
            'bobot'         => $this->request->getPost('bobot'),
        ]);

        return redirect()->to(site_url('administrator/kriteria'))
            ->with('success', 'Data kriteria berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->kriteriaModel->delete($id);

        return redirect()->to(site_url('administrator/kriteria'))
            ->with('success', 'Data kriteria berhasil dihapus.');
    }
}