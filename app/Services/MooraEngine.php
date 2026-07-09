<?php

namespace App\Services;

use Throwable;

/**
 * Compatibility wrapper untuk route/controller lama.
 * Semua proses final tetap diarahkan ke MooraService agar hasil_moora lengkap:
 * nilai_yi, versi_hitung, mode_hitung, audit, dan workflow.
 */
class MooraEngine
{
    protected MooraService $service;

    public function __construct()
    {
        $this->service = new MooraService();
    }

    /**
     * @param int|string $idUsulan
     * @param string|null $modeLegacy parameter lama diabaikan supaya mode selalu mengikuti jenis_usulan.
     * @return array<string,mixed>
     */
    public function process($idUsulan, ?string $modeLegacy = null): array
    {
        $idUsulan = (int) $idUsulan;
        if ($idUsulan < 1) {
            return [
                'status' => false,
                'success' => false,
                'message' => 'ID usulan tidak valid.',
            ];
        }

        try {
            $result = $this->service->prosesUsulan($idUsulan, [
                'allow_any_status' => true,
                'auto_generate' => true,
                'cost_mode' => 'standard_moora',
                'engine_log_role' => session()->get('role') ?: 'gudang_auto_fix_wrapper',
            ]);

            return array_merge($result, [
                'status' => true,
                'message' => 'MOORA berhasil diproses oleh Auto Fix Full System.',
            ]);
        } catch (Throwable $e) {
            log_message('error', 'MOORA AUTO FIX WRAPPER FAILED: ' . $e->getMessage());
            return [
                'status' => false,
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
