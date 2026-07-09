<?php

namespace App\Commands;

use App\Services\MooraConsolidationService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

class MooraConsolidate extends BaseCommand
{
    protected $group       = 'MOORA';
    protected $name        = 'moora:consolidate';
    protected $description = 'Patch V6: normalisasi rka_aggregate/item_based dan backfill audit engine.';
    protected $usage       = 'moora:consolidate [limit]';

    public function run(array $params)
    {
        $limit = isset($params[0]) ? (int) $params[0] : 500;
        $limit = max(1, min(500, $limit));

        try {
            CLI::write('Running MOORA Patch V6 consolidation...', 'green');

            $service = new MooraConsolidationService();
            $summary = $service->consolidateAll($limit, [
                'engine_log_role' => 'cli_v6_consolidation',
            ]);

            CLI::write('DONE V6', 'green');
            CLI::write('Processed: ' . (int) ($summary['processed'] ?? 0) . ' | Failed: ' . (int) ($summary['failed'] ?? 0), 'yellow');

            foreach (($summary['items'] ?? []) as $item) {
                $line = ($item['success'] ?? false) ? '- OK  ' : '- ERR ';
                $line .= ($item['nomor_usulan'] ?? '-') . ' | ' . ($item['jenis_usulan'] ?? '-') . ' | ' . ($item['mode_hitung'] ?? ($item['error'] ?? '-'));
                CLI::write($line, !empty($item['success']) ? 'green' : 'red');
            }
        } catch (Throwable $e) {
            CLI::error('Konsolidasi MOORA V6 gagal: ' . $e->getMessage());
        }
    }
}
