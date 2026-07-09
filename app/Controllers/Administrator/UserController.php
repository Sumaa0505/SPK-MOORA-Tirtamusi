<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    private array $allowedRoles = [
        'administrator',
        'sub_unit',
        'gudang',
        'manajer_umum',
        'direktur',
        'pengadaan',
    ];

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /* ===================== INDEX ===================== */
    public function index()
    {
        $users = $this->userModel
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('Administrator/user/index', [
            'title' => 'Manajemen User',
            'users' => $users
        ]);
    }

    /* ===================== CREATE ===================== */
    public function create()
    {
        return view('Administrator/user/form', [
            'title' => 'Tambah User',
            'user'  => null,
            'action'=> site_url('administrator/user/store')
        ]);
    }

    /* ===================== STORE ===================== */
    public function store()
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'username'     => 'required|min_length[3]|is_unique[users.username]',
            'password'     => 'required|min_length[4]',
            'role'         => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        $role = (string) $this->request->getPost('role');
        if (!in_array($role, $this->allowedRoles, true)) {
            return redirect()->back()->withInput()->with('error', 'Role tidak valid.');
        }

        $this->userModel->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => $role,
            'is_active'    => 1
        ]);

        return redirect()->to('administrator/user')->with('success', 'User berhasil ditambahkan');
    }

    /* ===================== EDIT ===================== */
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        return view('Administrator/user/form', [
            'title' => 'Edit User',
            'user'  => $user,
            'action'=> site_url('administrator/user/update/' . $id)
        ]);
    }

    /* ===================== UPDATE ===================== */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $rules = [
            'nama_lengkap' => 'required|min_length[3]',
            'role'         => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal');
        }

        $role = (string) $this->request->getPost('role');
        if (!in_array($role, $this->allowedRoles, true)) {
            return redirect()->back()->withInput()->with('error', 'Role tidak valid.');
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'role'         => $role,
        ];

        // jika password diisi → update
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);

        return redirect()->to('administrator/user')->with('success', 'User berhasil diperbarui');
    }

    /* ===================== TOGGLE AKTIF ===================== */
    public function toggle($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $this->userModel->update($id, [
            'is_active' => $user['is_active'] ? 0 : 1
        ]);

        return redirect()->back()->with('success', 'Status user diperbarui');
    }
}