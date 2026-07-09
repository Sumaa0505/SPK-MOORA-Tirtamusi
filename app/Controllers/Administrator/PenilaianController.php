<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;

/**
 * PATCH 9 FINAL STABILIZATION LOCK
 *
 * Controller lama Penilaian Admin sengaja dikunci agar Admin tidak terlihat
 * memproses MOORA operasional. Sesuai rancangan final, pemrosesan MOORA aktif
 * dilakukan oleh Gudang; Admin hanya monitoring, audit, setting, dan training.
 */
class PenilaianController extends BaseController
{
    public function index()
    {
        return redirect()
            ->to(site_url('administrator/kalkulasi-moora'))
            ->with('warning', 'Patch 9: halaman Penilaian Admin dikunci. Proses MOORA operasional dilakukan oleh Gudang melalui menu Engine MOORA.');
    }

    public function create($idUsulan = null)
    {
        return $this->index();
    }

    public function store()
    {
        return $this->index();
    }

    public function edit($id = null)
    {
        return $this->index();
    }

    public function update($id = null)
    {
        return $this->index();
    }
}
