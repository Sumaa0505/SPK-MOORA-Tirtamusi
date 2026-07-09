<?php

namespace App\Commands;

use App\Services\MooraAutoFixService;
use App\Services\MooraService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

class MooraAutoFixFullSystem extends BaseCommand
{
    protected $group       = 'MOORA';
    protected $name        = 'moora:auto-fix-full-system';
    protected $description = 'Auto repair detail_usulan, penilaian, dan recalculate MOORA aktif secara non-destruktif.';

    public function run(array $params)
    {
        $limit = isset($params[0]) ? (int) $params[0] : 200;
        $limit = max(1, min(1000, $limit));

        $autoFix = new MooraAutoFixService();
        $moora = new MooraService();

        CLI::write('PATCH AUTO FIX FULL SYSTEM - mulai repair data flow...', 'yellow');

        $repair = $autoFix->repairUsulanDataFlow($limit);
        CLI::write('Usulan dicek: ' . $repair['checked'], 'green');
        CLI::write('Detail fallback dibuat: ' . $repair['detail_created'], 'green');
        CLI::write('Penilaian minimum dibuat: ' . $repair['penilaian_inserted'], 'green');
        CLI::write('Gagal repair data: ' . $repair['failed'], $repair['failed'] > 0 ? 'red' : 'green');

        CLI::write('Menjalankan konsolidasi MOORA aktif...', 'yellow');
        try {
            $konsolidasi = $moora->konsolidasiHistoris($limit, [
                'engine_log_role' => 'cli_auto_fix_full_system',
            ]);
            CLI::write('MOORA processed: ' . ($konsolidasi['processed'] ?? 0), 'green');
            CLI::write('MOORA failed: ' . ($konsolidasi['failed'] ?? 0), ((int) ($konsolidasi['failed'] ?? 0)) > 0 ? 'red' : 'green');
        } catch (Throwable $e) {
            CLI::error('Konsolidasi MOORA gagal: ' . $e->getMessage());
        }

        CLI::write('AUTO FIX selesai. Histori hasil_moora tidak dihapus.', 'green');
    }
}
