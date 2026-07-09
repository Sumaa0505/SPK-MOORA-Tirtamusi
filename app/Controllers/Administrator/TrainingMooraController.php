<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\KriteriaModel;
use App\Models\LogAktivitasModel;
use App\Services\MooraResultQueryService;
use Throwable;

class TrainingMooraController extends BaseController
{
    protected MooraResultQueryService $mooraQuery;
    protected KriteriaModel $kriteriaModel;
    protected LogAktivitasModel $logModel;

    public function __construct()
    {
        $this->mooraQuery = new MooraResultQueryService();
        $this->kriteriaModel = new KriteriaModel();
        $this->logModel = new LogAktivitasModel();
    }

    public function index()
    {
        $mode = (string) ($this->request->getGet('mode') ?: '');
        if (!in_array($mode, ['', MooraResultQueryService::MODE_RKA_AGGREGATE, MooraResultQueryService::MODE_ITEM_BASED], true)) {
            $mode = '';
        }

        $kriteria = $this->kriteriaModel->where('is_active', 1)
            ->orderBy('kode_kriteria', 'ASC')
            ->findAll();

        $rows = $this->mooraQuery->getGlobalRanking($mode ?: null, 200);
        $scenarios = $this->buildSensitivityScenarios($rows, $kriteria);
        $summary = $this->buildSummary($rows, $kriteria, $scenarios);

        return view('Administrator/moora/training', [
            'title'     => 'Training MOORA',
            'mode'      => $mode,
            'kriteria'  => $kriteria,
            'rows'      => $rows,
            'scenarios' => $scenarios,
            'summary'   => $summary,
        ]);
    }

    /**
     * Simulator sensitivitas bobot tanpa mengubah hasil produksi.
     * Rincian normalisasi dibaca dari hasil MOORA aktif.
     *
     * @param array<int,array<string,mixed>> $rows
     * @param array<int,array<string,mixed>> $kriteria
     * @return array<string,array<int,array<string,mixed>>>
     */
    protected function buildSensitivityScenarios(array $rows, array $kriteria): array
    {
        $weights = [];
        foreach ($kriteria as $k) {
            $weights[(string) ($k['kode_kriteria'] ?? '')] = (float) ($k['bobot'] ?? 0);
        }

        $scenarioWeights = [
            'baseline' => $this->normalizeWeights($weights),
            'urgensi_plus_15' => $this->boostWeight($weights, 'C1', 0.15),
            'biaya_plus_15' => $this->boostWeight($weights, 'C2', 0.15),
            'dampak_plus_15' => $this->boostWeight($weights, 'C5', 0.15),
        ];

        $result = [];
        foreach ($scenarioWeights as $name => $w) {
            $simulated = [];
            foreach ($rows as $row) {
                $simulated[] = $this->simulateRow($row, $w);
            }

            usort($simulated, static fn ($a, $b) => $b['nilai_simulasi'] <=> $a['nilai_simulasi']);
            foreach ($simulated as $idx => &$item) {
                $item['ranking_simulasi'] = $idx + 1;
                $item['perubahan_ranking'] = ((int) ($item['ranking_awal'] ?? 0)) - ($idx + 1);
            }
            unset($item);

            $result[$name] = $simulated;
        }

        return $result;
    }

    /** @param array<string,float> $weights */
    protected function boostWeight(array $weights, string $code, float $boost): array
    {
        $weights[$code] = max(0.0, (float) ($weights[$code] ?? 0) + $boost);
        return $this->normalizeWeights($weights);
    }

    /** @param array<string,float> $weights */
    protected function normalizeWeights(array $weights): array
    {
        $sum = array_sum($weights);
        if ($sum <= 0) {
            return $weights;
        }

        foreach ($weights as $key => $value) {
            $weights[$key] = $value / $sum;
        }
        return $weights;
    }

    /**
     * @param array<string,mixed> $row
     * @param array<string,float> $weights
     * @return array<string,mixed>
     */
    protected function simulateRow(array $row, array $weights): array
    {
        $detail = [];
        if (!empty($row['rincian_json'])) {
            $decoded = json_decode((string) $row['rincian_json'], true);
            $detail = is_array($decoded) ? ($decoded['kriteria'] ?? []) : [];
        }

        $benefit = 0.0;
        $cost = 0.0;
        foreach ($detail as $r) {
            $kode = (string) ($r['kode_kriteria'] ?? '');
            $jenis = strtolower((string) ($r['jenis'] ?? 'benefit'));
            $normalisasi = (float) ($r['normalisasi'] ?? 0);
            $bobot = (float) ($weights[$kode] ?? $r['bobot'] ?? 0);
            $terbobot = $normalisasi * $bobot;
            if ($jenis === 'cost') {
                $cost += $terbobot;
            } else {
                $benefit += $terbobot;
            }
        }

        $row['nilai_simulasi'] = $benefit - $cost;
        $row['benefit_simulasi'] = $benefit;
        $row['cost_simulasi'] = $cost;
        $row['ranking_awal'] = (int) ($row['ranking'] ?? 0);
        return $row;
    }

    /**
     * @param array<int,array<string,mixed>> $rows
     * @param array<int,array<string,mixed>> $kriteria
     * @param array<string,array<int,array<string,mixed>>> $scenarios
     */
    protected function buildSummary(array $rows, array $kriteria, array $scenarios): array
    {
        $totalBobot = 0.0;
        foreach ($kriteria as $k) {
            $totalBobot += (float) ($k['bobot'] ?? 0);
        }

        $modeCount = [MooraResultQueryService::MODE_RKA_AGGREGATE => 0, MooraResultQueryService::MODE_ITEM_BASED => 0];
        foreach ($rows as $row) {
            $mode = (string) ($row['mode_hitung'] ?? '');
            if (isset($modeCount[$mode])) {
                $modeCount[$mode]++;
            }
        }

        $changed = 0;
        foreach (($scenarios['urgensi_plus_15'] ?? []) as $item) {
            if ((int) ($item['perubahan_ranking'] ?? 0) !== 0) {
                $changed++;
            }
        }

        return [
            'total_rows' => count($rows),
            'total_bobot' => $totalBobot,
            'bobot_valid' => abs($totalBobot - 1.0) < 0.00001,
            'rka_count' => $modeCount[MooraResultQueryService::MODE_RKA_AGGREGATE],
            'item_count' => $modeCount[MooraResultQueryService::MODE_ITEM_BASED],
            'changed_when_urgency_boosted' => $changed,
        ];
    }
}
