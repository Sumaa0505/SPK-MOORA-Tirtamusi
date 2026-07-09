<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    /**
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    protected $helpers = ['url', 'form'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    protected function currentUserId(): ?int
    {
        $id = session()->get('id_user') ?? session()->get('id');
        return $id ? (int) $id : null;
    }

    protected function logAktivitas($userId, $aktivitas, $modul, $keterangan = null): void
    {
        try {
            (new LogAktivitasModel())->insert([
                'id_user'    => $userId ?: $this->currentUserId(),
                'aktivitas'  => $aktivitas,
                'modul'      => $modul,
                'keterangan' => $keterangan,
                'ip_address' => is_cli() ? '127.0.0.1' : $this->request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal menyimpan log aktivitas: ' . $e->getMessage());
        }
    }

    protected function createNotification(?int $userId, ?string $role, string $judul, string $pesan, ?string $link = null, string $tipe = 'info', ?int $idUsulan = null): void
    {
        try {
            (new NotificationModel())->insert([
                'id_user_penerima' => $userId,
                'role_penerima'    => $role,
                'id_usulan'        => $idUsulan,
                'judul'            => $judul,
                'pesan'            => $pesan,
                'link'             => $link,
                'tipe'             => $tipe,
                'is_read'          => 0,
                'created_by'       => $this->currentUserId(),
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Gagal membuat notifikasi: ' . $e->getMessage());
        }
    }
}
