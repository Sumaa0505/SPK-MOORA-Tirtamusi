<?php

namespace App\Services;

use App\Models\RiwayatValidasiModel;
use App\Models\UsulanPengadaanModel;
use RuntimeException;

/**
 * PATCH 10 FINAL COMPLETION ENTERPRISE
 *
 * Service ini mengembalikan kontrak workflow yang dipakai banyak controller.
 * Patch 7 lama terlalu minimal dan dapat membuat konstanta/method transition hilang.
 */
class WorkflowUsulanService
{
    public const STATUS_DRAFT                     = 'draft';
    public const STATUS_DIAJUKAN                  = 'diajukan';
    public const STATUS_BANDING_GUDANG            = 'banding_gudang';
    public const STATUS_DIKEMBALIKAN              = 'dikembalikan';
    public const STATUS_DITOLAK                   = 'ditolak';
    public const STATUS_DIVERIFIKASI              = 'diverifikasi';
    public const STATUS_MOORA_SELESAI             = 'moora_selesai';
    public const STATUS_MENUNGGU_DIREKTUR_BIDANG  = 'menunggu_direktur_bidang';
    public const STATUS_MENUNGGU_DIREKTUR_UTAMA   = 'menunggu_direktur_utama';
    public const STATUS_MENUNGGU_DIREKTUR_UMUM    = 'menunggu_direktur_umum';
    public const STATUS_DISPOSISI_PENGADAAN       = 'disposisi_pengadaan';
    public const STATUS_DIPROSES_PENGADAAN        = 'diproses_pengadaan';
    public const STATUS_MENUNGGU_PENERIMAAN       = 'menunggu_penerimaan';
    public const STATUS_SELESAI                   = 'selesai';

    protected UsulanPengadaanModel $usulanModel;
    protected RiwayatValidasiModel $riwayatModel;

    /** @var string[] */
    protected array $allowedStatus = [
        self::STATUS_DRAFT,
        self::STATUS_DIAJUKAN,
        self::STATUS_BANDING_GUDANG,
        self::STATUS_DIKEMBALIKAN,
        self::STATUS_DITOLAK,
        self::STATUS_DIVERIFIKASI,
        self::STATUS_MOORA_SELESAI,
        self::STATUS_MENUNGGU_DIREKTUR_BIDANG,
        self::STATUS_MENUNGGU_DIREKTUR_UTAMA,
        self::STATUS_MENUNGGU_DIREKTUR_UMUM,
        self::STATUS_DISPOSISI_PENGADAAN,
        self::STATUS_DIPROSES_PENGADAAN,
        self::STATUS_MENUNGGU_PENERIMAAN,
        self::STATUS_SELESAI,
    ];

    /** @var string[] */
    protected array $allowedAksi = [
        'ajukan', 'verifikasi', 'banding', 'revisi', 'nilai_moora', 'proses_moora',
        'rekomendasi', 'kembalikan', 'setujui', 'tolak', 'approval_bidang',
        'approval_utama', 'approval_umum', 'disposisi', 'pengadaan', 'pembelian',
        'upload_dokumen', 'serah_barang', 'penerimaan', 'realisasi', 'selesai',
    ];

    /** @var string[] */
    protected array $allowedRole = [
        'administrator', 'admin', 'sub_unit', 'gudang', 'seksi_gudang',
        'manajer_umum', 'direktur', 'direksi', 'pengadaan',
    ];

    public function __construct()
    {
        $this->usulanModel  = new UsulanPengadaanModel();
        $this->riwayatModel = new RiwayatValidasiModel();
    }

    public function normalizeStatus(?string $status): string
    {
        $status = trim((string) $status);

        $map = [
            'verifikasi_gudang'          => self::STATUS_DIAJUKAN,
            'direvisi'                   => self::STATUS_DIKEMBALIKAN,
            'menunggu_moora'             => self::STATUS_DIVERIFIKASI,
            'moora_diproses'             => self::STATUS_DIVERIFIKASI,
            'direkomendasikan'           => self::STATUS_MENUNGGU_DIREKTUR_BIDANG,
            'disetujui_direktur_bidang'  => self::STATUS_MENUNGGU_DIREKTUR_UTAMA,
            'disetujui_direktur_utama'   => self::STATUS_MENUNGGU_DIREKTUR_UMUM,
            'disetujui'                  => self::STATUS_DISPOSISI_PENGADAAN,
            'selesai_pengadaan'          => self::STATUS_MENUNGGU_PENERIMAAN,
            'direalisasi'                => self::STATUS_SELESAI,
        ];

        $status = $map[$status] ?? $status;

        if (in_array($status, $this->allowedStatus, true)) {
            return $status;
        }

        return self::STATUS_DIAJUKAN;
    }

    public function enforceFinalStatus($status): string
    {
        return $this->normalizeStatus((string) $status);
    }

    public function canSetSelesai($usulan): bool
    {
        if (!$usulan || !is_array($usulan)) {
            return false;
        }

        $status = $this->normalizeStatus($usulan['status'] ?? '');
        if (!in_array($status, [self::STATUS_MENUNGGU_PENERIMAAN, self::STATUS_SELESAI], true)) {
            return false;
        }

        $idUsulan = (int) ($usulan['id'] ?? 0);
        if ($idUsulan < 1) {
            return false;
        }

        return $this->canCloseAfterSubUnitConfirmation($idUsulan);
    }

    /**
     * Patch 8: status selesai hanya sah jika seluruh baris distribusi sudah dikonfirmasi Sub Unit.
     * Jika belum ada distribusi, usulan belum boleh ditutup karena barang belum masuk closing loop.
     */
    public function canCloseAfterSubUnitConfirmation(int $idUsulan): bool
    {
        $db = \Config\Database::connect();

        $totalDistribusi = (int) $db->table('distribusi_barang')
            ->where('id_usulan', $idUsulan)
            ->countAllResults();

        if ($totalDistribusi < 1) {
            return false;
        }

        $belumSelesai = (int) $db->table('distribusi_barang')
            ->where('id_usulan', $idUsulan)
            ->where('status_distribusi !=', 'selesai')
            ->countAllResults();

        return $belumSelesai === 0;
    }

    public function hasPendingDistribusi(int $idUsulan): bool
    {
        $db = \Config\Database::connect();

        return (int) $db->table('distribusi_barang')
            ->where('id_usulan', $idUsulan)
            ->where('status_distribusi !=', 'selesai')
            ->countAllResults() > 0;
    }

    /**
     * Update status utama + riwayat validasi dalam satu kontrak konsisten.
     * Transaksi tetap dikendalikan caller bila caller sudah membuka transStart/transBegin.
     *
     * @param array<string,mixed> $extraPayload
     */
    public function transition(
        int $idUsulan,
        string $targetStatus,
        ?int $idUser,
        string $roleUser,
        string $aksi,
        ?string $catatan = null,
        ?string $modul = null,
        array $extraPayload = []
    ): bool {
        $idUsulan = (int) $idUsulan;
        if ($idUsulan < 1) {
            throw new RuntimeException('ID usulan tidak valid.');
        }

        $targetStatus = $this->normalizeStatus($targetStatus);
        $roleUser = $this->normalizeRole($roleUser);
        $aksi = $this->normalizeAksi($aksi, $targetStatus);
        $now = date('Y-m-d H:i:s');

        $payload = $this->filterUsulanPayload(array_merge($extraPayload, [
            'status'     => $targetStatus,
            'updated_at' => $now,
        ]));

        if (!$this->usulanModel->update($idUsulan, $payload)) {
            throw new RuntimeException('Gagal memperbarui status usulan.');
        }

        $this->syncDetailStatus($idUsulan, $targetStatus, $now);

        $this->riwayatModel->insert([
            'id_usulan'    => $idUsulan,
            'id_user'      => $idUser ?: 1,
            'role_user'    => $roleUser,
            'aksi'         => $aksi,
            'catatan'      => $catatan,
            'tanggal_aksi' => $now,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        $this->logAktivitas($idUser ?: 1, $this->judulAktivitas($aksi), $modul ?: 'Workflow', 'Status usulan ID ' . $idUsulan . ' diubah menjadi ' . $targetStatus . '. ' . ($catatan ?: ''));

        return true;
    }

    protected function normalizeRole(string $role): string
    {
        $role = strtolower(trim($role));

        $map = [
            'seksi gudang' => 'gudang',
            'direktur_bidang' => 'direktur',
            'direktur_utama' => 'direktur',
            'direktur_umum' => 'direktur',
        ];

        $role = $map[$role] ?? $role;
        return in_array($role, $this->allowedRole, true) ? $role : 'administrator';
    }

    protected function normalizeAksi(string $aksi, string $targetStatus): string
    {
        $aksi = strtolower(trim($aksi));
        if (in_array($aksi, $this->allowedAksi, true)) {
            return $aksi;
        }

        return match ($targetStatus) {
            self::STATUS_DIAJUKAN => 'ajukan',
            self::STATUS_DIVERIFIKASI => 'verifikasi',
            self::STATUS_MOORA_SELESAI => 'proses_moora',
            self::STATUS_MENUNGGU_DIREKTUR_BIDANG => 'rekomendasi',
            self::STATUS_DISPOSISI_PENGADAAN => 'disposisi',
            self::STATUS_DIPROSES_PENGADAAN => 'pembelian',
            self::STATUS_MENUNGGU_PENERIMAAN => 'serah_barang',
            self::STATUS_SELESAI => 'selesai',
            self::STATUS_DITOLAK => 'tolak',
            default => 'revisi',
        };
    }

    protected function syncDetailStatus(int $idUsulan, string $status, string $now): void
    {
        $detailStatus = match ($status) {
            self::STATUS_DRAFT => 'draft',
            self::STATUS_DIAJUKAN, self::STATUS_BANDING_GUDANG => 'diajukan',
            self::STATUS_DIKEMBALIKAN => 'dikembalikan',
            self::STATUS_DITOLAK => 'ditolak',
            self::STATUS_DIPROSES_PENGADAAN => 'diproses_pengadaan',
            self::STATUS_MENUNGGU_PENERIMAAN => 'menunggu_penerimaan',
            self::STATUS_SELESAI => 'selesai',
            default => 'diverifikasi',
        };

        try {
            \Config\Database::connect()
                ->table('detail_usulan')
                ->where('id_usulan', $idUsulan)
                ->set([
                    'status'     => $detailStatus,
                    'updated_at' => $now,
                ])
                ->update();
        } catch (\Throwable $e) {
            log_message('error', 'Gagal sinkron status detail usulan: ' . $e->getMessage());
        }
    }


    protected function filterUsulanPayload(array $payload): array
    {
        static $allowed = null;
        if ($allowed === null) {
            $allowed = array_flip(\Config\Database::connect()->getFieldNames('usulan_pengadaan'));
        }

        return array_intersect_key($payload, $allowed);
    }

    protected function logAktivitas(int $idUser, string $aktivitas, string $modul, string $keterangan): void
    {
        try {
            \Config\Database::connect()->table('log_aktivitas')->insert([
                'id_user'    => $idUser,
                'aktivitas'  => $aktivitas,
                'modul'      => $modul,
                'keterangan' => $keterangan,
                'ip_address' => is_cli() ? '127.0.0.1' : (service('request')->getIPAddress() ?? '127.0.0.1'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mencatat log workflow: ' . $e->getMessage());
        }
    }

    protected function judulAktivitas(string $aksi): string
    {
        return ucwords(str_replace('_', ' ', $aksi));
    }
}
