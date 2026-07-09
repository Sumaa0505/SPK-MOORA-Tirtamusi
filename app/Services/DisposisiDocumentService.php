<?php

namespace App\Services;

use App\Models\DokumenDisposisiModel;
use App\Models\QrDisposisiModel;
use App\Models\SettingModel;
use App\Models\UsulanPengadaanModel;
use App\Services\MooraResultQueryService;
use RuntimeException;

class DisposisiDocumentService
{
    protected DokumenDisposisiModel $dokumenModel;
    protected QrDisposisiModel $qrModel;
    protected SettingModel $settingModel;
    protected UsulanPengadaanModel $usulanModel;
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->dokumenModel = new DokumenDisposisiModel();
        $this->qrModel      = new QrDisposisiModel();
        $this->settingModel = new SettingModel();
        $this->usulanModel  = new UsulanPengadaanModel();
        $this->mooraQuery    = new MooraResultQueryService();
    }

    public function preparePreview(int $idUsulan, ?int $idUser = null): array
    {
        $usulan = $this->usulanModel->find($idUsulan);
        if (!$usulan) {
            throw new RuntimeException('Usulan tidak ditemukan.');
        }

        $dokumen = $this->dokumenModel->where('id_usulan', $idUsulan)->orderBy('id', 'DESC')->first();
        $now = date('Y-m-d H:i:s');

        if (!$dokumen) {
            $nomor = $usulan['nomor_disposisi'] ?: $this->generateNomorDraft();
            $hash  = $this->makeHash($idUsulan, $nomor, $now);
            $idDokumen = (int) $this->dokumenModel->insert([
                'id_usulan'      => $idUsulan,
                'nomor_dokumen'  => $nomor,
                'judul_dokumen'  => 'Dokumen Disposisi Pengadaan',
                'status_dokumen' => 'preview',
                'hash_dokumen'   => $hash,
                'created_by'     => $idUser,
                'created_at'     => $now,
                'updated_at'     => $now,
            ], true);
            $dokumen = $this->dokumenModel->find($idDokumen);
        } elseif (empty($dokumen['hash_dokumen'])) {
            $hash = $this->makeHash($idUsulan, (string) $dokumen['nomor_dokumen'], $now);
            $this->dokumenModel->update((int) $dokumen['id'], [
                'hash_dokumen' => $hash,
                'updated_at'   => $now,
            ]);
            $dokumen = $this->dokumenModel->find((int) $dokumen['id']);
        }

        $qr = $this->ensureQr($dokumen, $idUsulan);
        $this->saveQrVisual($qr);

        return $this->buildData($idUsulan);
    }

    public function finalize(int $idUsulan, string $nomorDisposisi, ?int $idUser = null, ?string $approvedAt = null): array
    {
        $approvedAt = $approvedAt ?: date('Y-m-d H:i:s');
        $hash = $this->makeHash($idUsulan, $nomorDisposisi, $approvedAt);
        $existing = $this->dokumenModel->where('id_usulan', $idUsulan)->first();

        $payload = [
            'nomor_dokumen'  => $nomorDisposisi,
            'judul_dokumen'  => 'Dokumen Disposisi Pengadaan',
            'status_dokumen' => 'tervalidasi',
            'hash_dokumen'   => $hash,
            'approved_by'    => $idUser,
            'approved_at'    => $approvedAt,
            'updated_at'     => $approvedAt,
        ];

        if ($existing) {
            $this->dokumenModel->update((int) $existing['id'], $payload);
            $idDokumen = (int) $existing['id'];
        } else {
            $payload['id_usulan']  = $idUsulan;
            $payload['created_by'] = $idUser;
            $payload['created_at'] = $approvedAt;
            $idDokumen = (int) $this->dokumenModel->insert($payload, true);
        }

        $dokumen = $this->dokumenModel->find($idDokumen);
        $qr = $this->ensureQr($dokumen, $idUsulan);
        $this->saveQrVisual($qr);

        $data = $this->buildData($idUsulan);
        $filePath = $this->savePrintableDocument($data);
        $this->dokumenModel->update($idDokumen, [
            'file_path' => $filePath,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->buildData($idUsulan);
    }

    public function buildData(int $idUsulan): array
    {
        $db = \Config\Database::connect();

        $usulan = $db->table('usulan_pengadaan up')
            ->select('up.*, u.nama_lengkap AS nama_pengusul, validator.nama_lengkap AS nama_validator')
            ->join('users u', 'u.id = up.id_user_pengusul', 'left')
            ->join('users validator', 'validator.id = up.validated_by', 'left')
            ->where('up.id', $idUsulan)
            ->get()->getRowArray();

        if (!$usulan) {
            throw new RuntimeException('Usulan tidak ditemukan.');
        }

        $detail = $db->table('detail_usulan du')
            ->select('du.*, a.kode_alternatif, a.nama_alternatif, a.kategori_barang, a.spesifikasi, a.satuan')
            ->join('alternatif a', 'a.id = du.id_alternatif', 'left')
            ->where('du.id_usulan', $idUsulan)
            ->orderBy('du.id', 'ASC')
            ->get()->getResultArray();

        // PATCH 10: dokumen disposisi membaca single source latest.
        // RKA harus tampil 1 baris agregat, bukan histori raw hasil_moora per barang.
        $hasilMoora = $this->mooraQuery->activeLatestByUsulan($idUsulan);

        $approval = $db->table('approval_direktur ad')
            ->select('ad.*, u.nama_lengkap AS nama_approver')
            ->join('users u', 'u.id = ad.approved_by', 'left')
            ->where('ad.id_usulan', $idUsulan)
            ->orderBy('ad.urutan', 'ASC')
            ->get()->getResultArray();

        $dokumen = $this->dokumenModel->where('id_usulan', $idUsulan)->orderBy('id', 'DESC')->first();
        $qr = $dokumen ? $this->qrModel->where('id_dokumen', (int) $dokumen['id'])->first() : null;

        return [
            'title'      => 'Preview Dokumen Disposisi',
            'usulan'     => $usulan,
            'detail'     => $detail,
            'hasilMoora' => $hasilMoora,
            'approval'   => $approval,
            'dokumen'    => $dokumen,
            'qr'         => $qr,
        ];
    }

    public function savePrintableDocument(array $data): string
    {
        $dokumen = $data['dokumen'] ?? null;
        if (!$dokumen) {
            throw new RuntimeException('Dokumen disposisi belum tersedia.');
        }

        $dir = WRITEPATH . 'uploads/disposisi';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $safeNumber = preg_replace('/[^A-Za-z0-9_\-]+/', '_', (string) ($dokumen['nomor_dokumen'] ?? ('DISP_' . time())));
        $fileName = $safeNumber . '.html';
        $html = view('Dokumen/disposisi_pdf', $data + ['printMode' => true]);
        file_put_contents($dir . DIRECTORY_SEPARATOR . $fileName, $html);

        return 'writable/uploads/disposisi/' . $fileName;
    }

    private function ensureQr(array $dokumen, int $idUsulan): array
    {
        $hash = (string) ($dokumen['hash_dokumen'] ?? '');
        if ($hash === '') {
            $hash = $this->makeHash($idUsulan, (string) $dokumen['nomor_dokumen'], date('Y-m-d H:i:s'));
        }

        $baseUrl = rtrim((string) $this->settingModel->getValue('qr_base_url', site_url('verifikasi-dokumen')), '/');
        $verificationUrl = $baseUrl . '/' . $hash;

        $qr = $this->qrModel->where('id_dokumen', (int) $dokumen['id'])->first();
        $payload = [
            'id_dokumen'       => (int) $dokumen['id'],
            'id_usulan'        => $idUsulan,
            'qr_hash'          => $hash,
            'verification_url' => $verificationUrl,
            'is_valid'         => 1,
        ];

        if ($qr) {
            $this->qrModel->update((int) $qr['id'], $payload);
            return $this->qrModel->find((int) $qr['id']);
        }

        $payload['created_at'] = date('Y-m-d H:i:s');
        $id = (int) $this->qrModel->insert($payload, true);
        return $this->qrModel->find($id);
    }

    private function saveQrVisual(array $qr): void
    {
        $dir = WRITEPATH . 'uploads/disposisi/qr';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $hash = (string) ($qr['qr_hash'] ?? hash('sha256', microtime(true)));
        $path = $dir . DIRECTORY_SEPARATOR . $hash . '.svg';
        file_put_contents($path, $this->makeQrVisualSvg((string) ($qr['verification_url'] ?? $hash), $hash));

        $this->qrModel->update((int) $qr['id'], [
            'qr_file_path' => 'writable/uploads/disposisi/qr/' . $hash . '.svg',
        ]);
    }

    /**
     * Visual QR internal untuk dokumen resmi. Hash dan URL verifikasi tetap tercetak
     * di bawahnya agar dokumen dapat diverifikasi walaupun pembaca tidak memakai scanner.
     */
    private function makeQrVisualSvg(string $payload, string $hash): string
    {
        $size = 29;
        $cell = 8;
        $margin = 2;
        $dim = ($size + $margin * 2) * $cell;
        $bits = hash('sha512', $payload . '|' . $hash, true);
        $idx = 0;
        $rects = [];

        $finder = static function (int $x, int $y) use (&$rects, $cell, $margin): void {
            for ($yy = 0; $yy < 7; $yy++) {
                for ($xx = 0; $xx < 7; $xx++) {
                    $outer = $xx === 0 || $yy === 0 || $xx === 6 || $yy === 6;
                    $inner = $xx >= 2 && $xx <= 4 && $yy >= 2 && $yy <= 4;
                    if ($outer || $inner) {
                        $rx = ($x + $xx + $margin) * $cell;
                        $ry = ($y + $yy + $margin) * $cell;
                        $rects[] = '<rect x="' . $rx . '" y="' . $ry . '" width="' . $cell . '" height="' . $cell . '"/>';
                    }
                }
            }
        };

        $finder(0, 0);
        $finder($size - 7, 0);
        $finder(0, $size - 7);

        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $inFinder = ($x < 8 && $y < 8) || ($x >= $size - 8 && $y < 8) || ($x < 8 && $y >= $size - 8);
                if ($inFinder) {
                    continue;
                }
                $byte = ord($bits[$idx % strlen($bits)]);
                $bit = (($byte >> ($idx % 8)) & 1) === 1;
                $idx++;
                if ($bit) {
                    $rx = ($x + $margin) * $cell;
                    $ry = ($y + $margin) * $cell;
                    $rects[] = '<rect x="' . $rx . '" y="' . $ry . '" width="' . $cell . '" height="' . $cell . '"/>';
                }
            }
        }

        return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $dim . '" height="' . $dim . '" viewBox="0 0 ' . $dim . ' ' . $dim . '"><rect width="100%" height="100%" fill="#fff"/><g fill="#111827">' . implode('', $rects) . '</g></svg>';
    }

    private function makeHash(int $idUsulan, string $nomor, string $time): string
    {
        return hash('sha256', $idUsulan . '|' . $nomor . '|' . $time . '|' . bin2hex(random_bytes(8)));
    }

    private function generateNomorDraft(): string
    {
        return 'PREVIEW/DISP/' . date('Y/m') . '/' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
