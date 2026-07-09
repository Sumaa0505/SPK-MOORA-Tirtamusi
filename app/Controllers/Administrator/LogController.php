<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\LogAktivitasModel;

class LogController extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new LogAktivitasModel();
    }

   public function index()
{
    $log = $this->logModel
        ->select('log_aktivitas.*, users.nama_lengkap')
        ->join('users', 'users.id = log_aktivitas.id_user', 'left')
        ->orderBy('log_aktivitas.id', 'DESC')
        ->findAll();

    return view('Administrator/Log/index', [
        'title' => 'Log Aktivitas',
        'log'   => $log,
    ]);
}
}