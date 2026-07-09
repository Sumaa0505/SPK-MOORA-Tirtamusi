<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogAktivitasModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AuthController extends BaseController
{
    protected $userModel;
    protected $logAktivitasModel;
    protected $db;

    protected array $allowedRegisterRoles = [
        'administrator',
        'gudang',
        'sub_unit',
        'manajer_umum',
        'direktur',
        'pengadaan',
    ];

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->logAktivitasModel = new LogAktivitasModel();
        $this->db = \Config\Database::connect();
    }

    // =====================================================
    // LOGIN VIEW
    // =====================================================
    public function login()
    {
        if (session()->get('logged_in') === true) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('Auth/login', [
            'title' => 'Login'
        ]);
    }

    // =====================================================
    // LOGIN PROCESS
    // =====================================================
    public function prosesLogin()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = trim((string) $this->request->getPost('password'));

        if ($username === '' || $password === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi.');
        }

        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username atau password salah.');
        }

        if ((int)($user['is_active'] ?? 1) !== 1) {
            return redirect()->back()->with('error', 'Akun tidak aktif.');
        }

        // =================================================
        // PASSWORD VALIDATION (SAFE + BACKWARD COMPATIBLE)
        // =================================================
        $passwordValid = false;

        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $passwordValid = true;
        } elseif (!empty($user['password']) && $password === $user['password']) {
            // fallback password lama (plain text)
            $passwordValid = true;

            // auto upgrade ke hash
            $this->userModel->update($user['id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        if (!$passwordValid) {
            return redirect()->back()->with('error', 'Username atau password salah.');
        }

        // =================================================
        // ROLE NORMALIZATION
        // =================================================
        $role = $this->normalizeRole($user['role'] ?? '');

        $allowedRoles = [
            'administrator',
            'gudang',
            'sub_unit',
            'manajer_umum',
            'direktur',
            'pengadaan'
        ];

        if (!in_array($role, $allowedRoles, true)) {
            return redirect()->back()->with('error', 'Role tidak valid.');
        }

        // =================================================
        // SESSION
        // =================================================
        session()->regenerate(true);
        session()->set([
            'logged_in'    => true,
            'id_user'      => $user['id'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'] ?? $user['username'],
            'role'         => $role
        ]);

        $this->catatLog($user['id'], 'Login', 'User login ke sistem');

        return $this->redirectByRole($role);
    }

    // =====================================================
    // LOGOUT
    // =====================================================
    public function logout()
    {
        $idUser = session()->get('id_user');

        if ($idUser) {
            $this->catatLog($idUser, 'Logout', 'User logout dari sistem');
        }

        session()->destroy();

        return redirect()->to(site_url('login'))
            ->with('success', 'Berhasil logout.');
    }

    // =====================================================
    // REGISTER VIEW
    // =====================================================
    public function register()
    {
        return view('Auth/register');
    }

    // =====================================================
    // REGISTER STORE (PENDING APPROVAL SYSTEM)
    // Patch Recovery: anti duplicate username, anti DB exception #1062,
    // dan aman untuk registrasi ulang yang sebelumnya ditolak.
    // =====================================================
    public function storeRegister()
    {
        $nama     = trim((string) $this->request->getPost('nama_lengkap'));
        $username = trim((string) $this->request->getPost('username'));
        $email    = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('password');
        $role     = $this->normalizeRole($this->request->getPost('role') ?: 'sub_unit');

        $error = $this->validateRegisterInput($nama, $username, $email, $password, $role);
        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $usernameInUsers = $this->findUserByUsername($username);
        if ($usernameInUsers !== null) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan oleh akun aktif. Silakan gunakan username lain atau langsung login.');
        }

        if ($email !== '' && $this->findUserByEmail($email) !== null) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan oleh akun aktif. Silakan gunakan email lain.');
        }

        $existingRegistration = $this->findRegistrationByUsername($username);
        if ($existingRegistration !== null) {
            $status = strtolower((string) ($existingRegistration['status'] ?? 'pending'));

            if ($status === 'pending') {
                return redirect()->back()->withInput()->with('error', 'Username ini sudah masuk daftar registrasi dan masih menunggu approval Administrator. Cek menu Approval Registrasi User.');
            }

            if ($status === 'approved') {
                return redirect()->back()->withInput()->with('error', 'Username ini sudah pernah disetujui. Jalankan SQL repair pada patch ini bila akun belum muncul di tabel user aktif, lalu login menggunakan akun tersebut.');
            }

            // Registrasi yang pernah ditolak boleh diajukan ulang tanpa membuat row duplikat.
            return $this->resubmitRejectedRegistration((int) $existingRegistration['id'], $nama, $username, $email, $password, $role);
        }

        if ($email !== '' && $this->findRegistrationByEmail($email) !== null) {
            return redirect()->back()->withInput()->with('error', 'Email ini sudah tercatat pada daftar registrasi. Gunakan email lain atau hubungi Administrator.');
        }

        try {
            $this->db->table('user_registration')->insert([
                'nama_lengkap' => $nama,
                'username'     => $username,
                'email'        => $email !== '' ? $email : null,
                'password'     => password_hash($password, PASSWORD_DEFAULT),
                'role'         => $role,
                'status'       => 'pending',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        } catch (DatabaseException $e) {
            if ($this->isDuplicateKeyError($e)) {
                return redirect()->back()->withInput()->with('error', 'Username sudah tercatat pada daftar registrasi. Data tidak dibuat ulang agar tidak terjadi duplikasi.');
            }

            return redirect()->back()->withInput()->with('error', 'Registrasi gagal disimpan. Silakan coba lagi atau hubungi Administrator.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Registrasi gagal diproses. Silakan coba lagi atau hubungi Administrator.');
        }

        return redirect()->to(site_url('login'))
            ->with('success', 'Registrasi berhasil. Menunggu validasi Administrator.');
    }

    protected function validateRegisterInput(string $nama, string $username, string $email, string $password, string $role): ?string
    {
        if ($nama === '' || $username === '' || $password === '') {
            return 'Nama lengkap, username, dan password wajib diisi.';
        }

        if (mb_strlen($nama) < 3) {
            return 'Nama lengkap minimal 3 karakter.';
        }

        if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
            return 'Username hanya boleh berisi huruf, angka, titik, garis bawah, atau strip dengan panjang 3-50 karakter.';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Format email tidak valid.';
        }

        if (mb_strlen($password) < 6) {
            return 'Password minimal 6 karakter.';
        }

        if (!in_array($role, $this->allowedRegisterRoles, true)) {
            return 'Role registrasi tidak valid.';
        }

        return null;
    }

    protected function resubmitRejectedRegistration(int $id, string $nama, string $username, string $email, string $password, string $role)
    {
        try {
            $this->db->table('user_registration')
                ->where('id', $id)
                ->update([
                    'nama_lengkap' => $nama,
                    'username'     => $username,
                    'email'        => $email !== '' ? $email : null,
                    'password'     => password_hash($password, PASSWORD_DEFAULT),
                    'role'         => $role,
                    'status'       => 'pending',
                    'updated_at'   => date('Y-m-d H:i:s'),
                ]);

            return redirect()->to(site_url('login'))
                ->with('success', 'Registrasi ulang berhasil dikirim. Menunggu validasi Administrator.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', 'Registrasi ulang gagal diproses. Silakan hubungi Administrator.');
        }
    }

    protected function findUserByUsername(string $username): ?array
    {
        $row = $this->db->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    protected function findUserByEmail(string $email): ?array
    {
        $row = $this->db->table('users')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    protected function findRegistrationByUsername(string $username): ?array
    {
        $row = $this->db->table('user_registration')
            ->where('username', $username)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    protected function findRegistrationByEmail(string $email): ?array
    {
        $row = $this->db->table('user_registration')
            ->where('email', $email)
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        return $row ?: null;
    }

    protected function isDuplicateKeyError(\Throwable $e): bool
    {
        return (int) $e->getCode() === 1062 || str_contains(strtolower($e->getMessage()), 'duplicate entry');
    }

    // =====================================================
    // ROLE NORMALIZER
    // =====================================================
    protected function normalizeRole($role)
    {
        $role = strtolower(trim((string)$role));
        $role = str_replace([' ', '-'], '_', $role);

        $map = [
            'admin'            => 'administrator',
            'administrator'    => 'administrator',
            'gudang'           => 'gudang',
            'seksi_gudang'     => 'gudang',
            'subunit'          => 'sub_unit',
            'sub_unit'         => 'sub_unit',
            'manajer'          => 'manajer_umum',
            'manajer_umum'     => 'manajer_umum',
            'direksi'          => 'direktur',
            'direktur'         => 'direktur',
            'bagian_pengadaan' => 'pengadaan',
            'pengadaan'        => 'pengadaan'
        ];

        return $map[$role] ?? $role;
    }

    // =====================================================
    // REDIRECT BY ROLE
    // =====================================================
    protected function redirectByRole($role)
    {
        $role = $this->normalizeRole($role);

        return match ($role) {
            'administrator' => redirect()->to(site_url('administrator/dashboard')),
            'gudang'        => redirect()->to(site_url('gudang/dashboard')),
            'sub_unit'      => redirect()->to(site_url('sub-unit/dashboard')),
            'manajer_umum'  => redirect()->to(site_url('manajer-umum/dashboard')),
            'direktur'      => redirect()->to(site_url('direktur/dashboard')),
            'pengadaan'     => redirect()->to(site_url('pengadaan/dashboard')),
            default         => redirect()->to(site_url('login'))->with('error', 'Role tidak valid')
        };
    }

    // =====================================================
    // LOG ACTIVITY
    // =====================================================
    protected function catatLog($idUser, $aktivitas, $keterangan)
    {
        try {
            $this->logAktivitasModel->insert([
                'id_user'    => $idUser,
                'aktivitas'  => $aktivitas,
                'modul'      => 'auth',
                'keterangan' => $keterangan,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // ignore log error
        }
    }
}
