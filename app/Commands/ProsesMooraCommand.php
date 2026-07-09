<?php

namespace App\Commands;

use App\Services\MooraService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

class ProsesMooraCommand extends BaseCommand
{
    protected $group       = 'MOORA';
    protected $name        = 'moora:proses';
    protected $description = 'V6: konsolidasi hasil MOORA historis agar RKA menjadi rka_aggregate dan Pesan Cepat tetap item_based.';
    protected $usage       = 'moora:proses [limit]';

    public function run(array $params = [])
    {
        $limit = isset($params[0]) ? (int) $params[0] : 100;
        $limit = max(1, min(500, $limit));

        try {
            $service = new MooraService();
            $summary = $service->konsolidasiHistoris($limit, [
                'engine_log_role' => 'cli_v6_maintenance',
            ]);

            CLI::write('Konsolidasi MOORA V6 selesai.', 'green');
            CLI::write('Berhasil: ' . (int) ($summary['processed'] ?? 0) . ' | Gagal: ' . (int) ($summary['failed'] ?? 0), 'yellow');

            foreach (($summary['items'] ?? []) as $item) {
                if (!empty($item['success'])) {
                    CLI::write('- OK  ' . ($item['nomor_usulan'] ?? '-') . ' | ' . ($item['jenis_usulan'] ?? '-') . ' | ' . ($item['mode_hitung'] ?? '-') . ' | versi ' . ($item['versi_hitung'] ?? '-'), 'green');
                } else {
                    CLI::write('- ERR ' . ($item['nomor_usulan'] ?? '-') . ' | ' . ($item['error'] ?? 'Gagal'), 'red');
                }
            }
        } catch (Throwable $e) {
            CLI::error('Konsolidasi MOORA V6 gagal: ' . $e->getMessage());
        }
    }
}
