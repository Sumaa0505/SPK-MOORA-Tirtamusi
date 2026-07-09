<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Services\MooraAuditService;
use App\Services\MooraConsolidationService;
use App\Services\MooraService;
use Throwable;

class MooraAuditController extends BaseController
{
    public function index()
    {
        $moora = new MooraService();
        $audit = new MooraAuditService();

        return view('Administrator/moora/audit', [
            'title'      => 'Audit Engine MOORA V6',
            'summary'    => $moora->auditModeSummary(),
            'engineRows' => $moora->auditEngineRows(150),
            'issues'     => $audit->detectIssues(200),
            'lastResult' => session()->getFlashdata('v6_recalculate_result')
                ?? session()->getFlashdata('v5_recalculate_result'),
        ]);
    }

    public function consolidate()
    {
        $limit = (int) ($this->request->getPost('limit') ?? 500);
        $limit = max(1, min(500, $limit));

        try {
            $service = new MooraConsolidationService();
            $summary = $service->consolidateAll($limit, [
                'engine_log_role' => 'administrator_v6_audit',
            ]);

            session()->setFlashdata('v6_recalculate_result', $summary);

            return redirect()->to(site_url('administrator/moora-audit'))
                ->with('success', 'Patch V6 audit consolidation selesai. Berhasil: ' . (int) ($summary['processed'] ?? 0) . ', gagal: ' . (int) ($summary['failed'] ?? 0) . '.');
        } catch (Throwable $e) {
            return redirect()->to(site_url('administrator/moora-audit'))
                ->with('error', 'Patch V6 audit consolidation gagal: ' . $e->getMessage());
        }
    }
}
