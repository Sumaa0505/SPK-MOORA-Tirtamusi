<?php

namespace App\Controllers\Direktur;

use App\Controllers\BaseController;
use App\Services\MooraResultQueryService;

class HasilController extends BaseController
{
    protected MooraResultQueryService $mooraQuery;

    public function __construct()
    {
        $this->mooraQuery = new MooraResultQueryService();
    }

    public function index()
    {
        return view('Direktur/hasil/index', [
            'title' => 'Hasil Prioritas MOORA',
            'hasil' => $this->mooraQuery->getForRole('direktur', null, 200),
        ]);
    }

    public function detail($idUsulan)
    {
        return redirect()->to(site_url('direktur/validasi/detail/' . (int) $idUsulan));
    }
}
