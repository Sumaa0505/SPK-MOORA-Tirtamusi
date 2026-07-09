<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotifikasiController extends BaseController
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
        helper(['url', 'form']);
    }

    public function index()
    {
        $userId = $this->currentUserId();
        $role   = (string) (session()->get('role') ?? '');

        $notifications = $this->notificationModel
            ->visibleFor($userId, $role)
            ->orderBy('created_at', 'DESC')
            ->findAll(100);

        $unreadCount = $this->notificationModel->countUnreadFor($userId, $role);

        return view('Notifikasi/index', [
            'title'         => 'Notifikasi',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
        ]);
    }

    public function baca(int $id)
    {
        $userId = $this->currentUserId();
        $role   = (string) (session()->get('role') ?? '');

        $notification = $this->notificationModel
            ->visibleFor($userId, $role)
            ->where('notifikasi.id', $id)
            ->first();

        if (!$notification) {
            return redirect()->to(site_url('notifikasi'))->with('error', 'Notifikasi tidak ditemukan atau bukan untuk akun ini.');
        }

        $this->notificationModel->markRead($id);

        $link = trim((string) ($notification['link'] ?? ''));
        if ($link !== '') {
            return redirect()->to(site_url($link));
        }

        return redirect()->to(site_url('notifikasi'))->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function bacaSemua()
    {
        $userId = $this->currentUserId();
        $role   = (string) (session()->get('role') ?? '');

        $this->notificationModel->markAllReadFor($userId, $role);

        return redirect()->to(site_url('notifikasi'))->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
