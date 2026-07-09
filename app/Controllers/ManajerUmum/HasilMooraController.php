<?php

namespace App\Controllers\ManajerUmum;

use App\Controllers\BaseController;
use App\Services\MooraResultQueryService;
use App\Services\MooraService;

class HasilMooraController extends BaseController
{
    protected MooraResultQueryService $mooraQuery;
    protected MooraService $mooraService;

    public function __construct()
    {
        $this->mooraQuery   = new MooraResultQueryService();
        $this->mooraService = new MooraService();
    }

    public function index()
    {
        return view('manajer_umum/hasil_moora', [
            'title'        => 'Hasil MOORA - Manajer Umum',
            'ranking'      => $this->mooraQuery->getForRole('manajer_umum', null, 200),
            'auditSummary' => method_exists($this->mooraService, 'auditModeSummary')
                ? $this->mooraService->auditModeSummary()
                : $this->mooraQuery->auditModeSummary(),
        ]);
    }
}
