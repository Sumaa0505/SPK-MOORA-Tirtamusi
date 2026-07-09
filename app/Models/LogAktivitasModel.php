<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAktivitasModel extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user',
        'aktivitas',
        'modul',
        'keterangan',
        'ip_address',
        'created_at',
    ];

public function simpanLog($aktivitas, $keterangan = null, $modul = 'Gudang')
{
    // Ambil IP, jika dari CLI default 127.0.0.1
    $ip = is_cli() ? '127.0.0.1' : service('request')->getIPAddress();

    return $this->insert([
        'id_user'     => session()->get('id_user') ?? session()->get('id') ?? 0,
        'aktivitas'   => $aktivitas,
        'modul'       => $modul,
        'keterangan'  => $keterangan,
        'ip_address'  => $ip,
        'created_at'  => date('Y-m-d H:i:s'),
    ]);
}}