<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\AdminUserApprovalLogModel;
use App\Models\LogAktivitasModel;
use App\Models\UserModel;
use App\Models\UserRegistrationHistoryModel;
use App\Models\UserRegistrationModel;
use Config\Database;
use CodeIgniter\Database\Exceptions\DatabaseException;

class RegistrasiController extends BaseController
{
    protected $db;
    protected UserModel $userModel;
    protected UserRegistrationModel $registrationModel;
    protected AdminUserApprovalLogModel $approvalLogModel;
    protected UserRegistrationHistoryModel $historyModel;
    protected LogAktivitasModel $logAktivitasModel;

    protected array $allowedRoles = [
        'administrator',
        'gudang',
        'sub_unit',
        'manajer_umum',
        'direktur',
        'pengadaan',
    ];

    protected array $roleLabels = [
        'administrator' => 'Administrator',
        'gudang'        => 'Seksi Gudang',
        'sub_unit'      => 'Sub Unit',
        'manajer_umum'  => 'Manajer Umum',
        'direktur'      => 'Direktur',
        'pengadaan'     => 'Bagian Pengadaan',
    ];

    public function __construct()
    {
        helper(['form', 'url', 'text']);

        $this->db                 = Database::connect();
        $this->userModel          = new UserModel();
        $this->registrationModel  = new UserRegistrationModel();
        $this->approvalLogModel   = new AdminUserApprovalLogModel();
        $this->historyModel       = new UserRegistrationHistoryModel();
        $this->logAktivitasModel  = new LogAktivitasModel();
    }

    public function index()
    {
        $status  = strtolower(trim((string) $this->request->getGet('status')));
        $keyword = trim((string) $this->request->getGet('q'));
        $role    = strtolower(trim((string) $this->request->getGet('role')));

        if ($status === '') {
            // Default all agar user yang sudah disetujui/ditolak tetap terlihat,
            // bukan seolah-olah hilang ketika filter Pending kosong.
            $status = 'all';
        }

        $allowedStatus = ['pending', 'approved', 'rejected', 'all'];
        if (! in_array($status, $allowedStatus, true)) {
            $status = 'pending';
        }

        if ($role !== '' && ! in_array($role, $this->allowedRoles, true)) {
            $role = '';
        }

        $query = $this->registrationModel;

        if ($status !== 'all') {
            $query = $query->where('status', $status);
        }

        if ($role !== '') {
            $query = $query->where('role', $role);
        }

        if ($keyword !== '') {
            $query = $query
                ->groupStart()
                    ->like('nama_lengkap', $keyword)
                    ->orLike('username', $keyword)
                    ->orLike('email', $keyword)
                ->groupEnd();
        }

        $registrations = $query
            ->orderBy('created_at', 'DESC')
            ->paginate(10, 'registrasi');

        $logs = $this->approvalLogModel
            ->select('admin_user_approval_log.*, user_registration.nama_lengkap, user_registration.username, user_registration.role, users.nama_lengkap AS admin_nama')
            ->join('user_registration', 'user_registration.id = admin_user_approval_log.registration_id', 'left')
            ->join('users', 'users.id = admin_user_approval_log.admin_id', 'left')
            ->orderBy('admin_user_approval_log.created_at', 'DESC')
            ->limit(10)
            ->findAll();

        return view('Administrator/registrasi/index', [
            'title'          => 'Approval Registrasi User',
            'registrations'  => $registrations,
            'pager'          => $this->registrationModel->pager,
            'status'         => $status,
            'keyword'        => $keyword,
            'role'           => $role,
            'roles'          => $this->roleLabels,
            'logs'           => $logs,
            'totalPending'   => $this->countRegistrationByStatus('pending'),
            'totalApproved'  => $this->countRegistrationByStatus('approved'),
            'totalRejected'  => $this->countRegistrationByStatus('rejected'),
            'totalUsers'     => $this->userModel->countAllResults(),
        ]);
    }

    public function approve($id)
    {
        $id = (int) $id;
        $registration = $this->registrationModel->find($id);

        if (! $registration) {
            return redirect()->to(site_url('administrator/registrasi'))
                ->with('error', 'Data registrasi tidak ditemukan.');
        }

        if (($registration['status'] ?? '') !== 'pending') {
            return redirect()->to(site_url('administrator/registrasi'))
                ->with('error', 'Registrasi ini sudah diproses sebelumnya.');
        }

        $role = $this->normalizeRole($this->request->getPost('role') ?: ($registration['role'] ?? 'sub_unit'));
        if (! in_array($role, $this->allowedRoles, true)) {
            return redirect()->back()->withInput()->with('error', 'Role yang dipilih tidak valid.');
        }

        $catatan = trim((string) $this->request->getPost('catatan'));
        $now     = date('Y-m-d H:i:s');
        $adminId = $this->getAdminId();

        $duplicateMessage = $this->checkDuplicateActiveUser($registration);
        if ($duplicateMessage !== null) {
            return redirect()->back()->withInput()->with('error', $duplicateMessage);
        }

        $this->db->transBegin();

        try {
            $this->userModel->insert([
                'nama_lengkap'   => $registration['nama_lengkap'],
                'username'       => $registration['username'],
                'email'          => $this->emptyToNull($registration['email'] ?? null),
                'password'       => $registration['password'],
                'role'           => $role,
                'is_active'      => 1,
                'registration_id'=> $id,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            $this->registrationModel->update($id, [
                'role'       => $role,
                'status'     => 'approved',
                'updated_at' => $now,
            ]);

            $note = $catatan !== '' ? $catatan : 'Akun disetujui dan diaktifkan oleh Administrator.';

            $this->approvalLogModel->insert([
                'registration_id' => $id,
                'admin_id'        => $adminId,
                'action'          => 'approved',
                'catatan'         => $note,
                'created_at'      => $now,
            ]);

            $this->historyModel->insert([
                'registration_id' => $id,
                'status'          => 'approved',
                'changed_by'      => $adminId,
                'note'            => $note,
                'created_at'      => $now,
            ]);

            $this->catatLog(
                'Approval Registrasi User',
                'Menyetujui akun ' . $registration['username'] . ' sebagai ' . ($this->roleLabels[$role] ?? $role) . '.'
            );

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Approval gagal diproses. Silakan coba lagi.');
            }

            $this->db->transCommit();

            return redirect()->to(site_url('administrator/registrasi'))
                ->with('success', 'Registrasi berhasil disetujui. Akun sudah masuk ke Manajemen User dan dapat login.');
        } catch (DatabaseException $e) {
            $this->db->transRollback();
            if ((int) $e->getCode() === 1062 || str_contains(strtolower($e->getMessage()), 'duplicate entry')) {
                return redirect()->back()->withInput()->with('error', 'Approval dibatalkan karena username/email sudah ada pada tabel user aktif. Gunakan username/email lain atau jalankan SQL repair jika data approved lama belum tersinkron.');
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi error saat approval. Silakan cek data registrasi dan coba lagi.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi error saat approval. Silakan cek data registrasi dan coba lagi.');
        }
    }

    public function reject($id)
    {
        $id = (int) $id;
        $registration = $this->registrationModel->find($id);

        if (! $registration) {
            return redirect()->to(site_url('administrator/registrasi'))
                ->with('error', 'Data registrasi tidak ditemukan.');
        }

        if (($registration['status'] ?? '') !== 'pending') {
            return redirect()->to(site_url('administrator/registrasi'))
                ->with('error', 'Registrasi ini sudah diproses sebelumnya.');
        }

        $catatan = trim((string) $this->request->getPost('catatan'));
        if ($catatan === '') {
            return redirect()->back()->withInput()->with('error', 'Catatan penolakan wajib diisi.');
        }

        $now     = date('Y-m-d H:i:s');
        $adminId = $this->getAdminId();

        $this->db->transBegin();

        try {
            $this->registrationModel->update($id, [
                'status'     => 'rejected',
                'updated_at' => $now,
            ]);

            $this->approvalLogModel->insert([
                'registration_id' => $id,
                'admin_id'        => $adminId,
                'action'          => 'rejected',
                'catatan'         => $catatan,
                'created_at'      => $now,
            ]);

            $this->historyModel->insert([
                'registration_id' => $id,
                'status'          => 'rejected',
                'changed_by'      => $adminId,
                'note'            => $catatan,
                'created_at'      => $now,
            ]);

            $this->catatLog(
                'Penolakan Registrasi User',
                'Menolak akun ' . $registration['username'] . '. Catatan: ' . $catatan
            );

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Penolakan gagal diproses. Silakan coba lagi.');
            }

            $this->db->transCommit();

            return redirect()->to(site_url('administrator/registrasi'))
                ->with('success', 'Registrasi berhasil ditolak dan tercatat pada riwayat approval.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi error saat penolakan: ' . $e->getMessage());
        }
    }

    protected function countRegistrationByStatus(string $status): int
    {
        return (int) $this->db->table('user_registration')
            ->where('status', $status)
            ->countAllResults();
    }

    protected function checkDuplicateActiveUser(array $registration): ?string
    {
        $username = trim((string) ($registration['username'] ?? ''));
        $email    = trim((string) ($registration['email'] ?? ''));

        if ($username !== '' && $this->userModel->where('username', $username)->first()) {
            return 'Username sudah ada pada tabel user aktif. Approval dibatalkan.';
        }

        if ($email !== '' && $this->userModel->where('email', $email)->first()) {
            return 'Email sudah ada pada tabel user aktif. Approval dibatalkan.';
        }

        return null;
    }

    protected function normalizeRole($role): string
    {
        $role = strtolower(trim((string) $role));
        $role = str_replace([' ', '-'], '_', $role);

        $map = [
            'admin'         => 'administrator',
            'administrator' => 'administrator',
            'seksi_gudang'  => 'gudang',
            'gudang'        => 'gudang',
            'subunit'       => 'sub_unit',
            'sub_unit'      => 'sub_unit',
            'manajer'       => 'manajer_umum',
            'manajer_umum'  => 'manajer_umum',
            'direksi'       => 'direktur',
            'direktur'      => 'direktur',
            'bagian_pengadaan' => 'pengadaan',
            'pengadaan'     => 'pengadaan',
        ];

        return $map[$role] ?? $role;
    }

    protected function emptyToNull($value)
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    protected function getAdminId(): ?int
    {
        $id = session()->get('id_user') ?? session()->get('id');
        return $id ? (int) $id : null;
    }

    protected function catatLog(string $aktivitas, string $keterangan): void
    {
        try {
            $this->logAktivitasModel->insert([
                'id_user'    => $this->getAdminId(),
                'aktivitas'  => $aktivitas,
                'modul'      => 'Administrator',
                'keterangan' => $keterangan,
                'ip_address' => $this->request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Error log tidak boleh membatalkan proses approval.
        }
    }
}
