<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_user_penerima',
        'role_penerima',
        'id_usulan',
        'judul',
        'pesan',
        'link',
        'tipe',
        'is_read',
        'read_at',
        'created_by',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function createForRole(string $role, string $judul, string $pesan, ?string $link = null, string $tipe = 'info', ?int $idUsulan = null, ?int $createdBy = null): bool
    {
        return (bool) $this->insert([
            'role_penerima' => $role,
            'id_usulan'     => $idUsulan,
            'judul'         => $judul,
            'pesan'         => $pesan,
            'link'          => $link,
            'tipe'          => $tipe,
            'is_read'       => 0,
            'created_by'    => $createdBy,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    public function createForUser(int $idUser, string $judul, string $pesan, ?string $link = null, string $tipe = 'info', ?int $idUsulan = null, ?int $createdBy = null): bool
    {
        return (bool) $this->insert([
            'id_user_penerima' => $idUser,
            'id_usulan'        => $idUsulan,
            'judul'            => $judul,
            'pesan'            => $pesan,
            'link'             => $link,
            'tipe'             => $tipe,
            'is_read'          => 0,
            'created_by'       => $createdBy,
            'created_at'       => date('Y-m-d H:i:s'),
        ]);
    }

    public function visibleFor(?int $idUser, ?string $role): self
    {
        $builder = $this->groupStart();

        if ($idUser !== null && $idUser > 0) {
            $builder->where('id_user_penerima', $idUser);
        } else {
            $builder->where('id_user_penerima', -1);
        }

        if (!empty($role)) {
            $builder->orWhere('role_penerima', $role);
        }

        return $builder->groupEnd();
    }

    public function countUnreadFor(?int $idUser, ?string $role): int
    {
        return (int) $this->visibleFor($idUser, $role)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function markRead(int $id): bool
    {
        return (bool) $this->update($id, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function markAllReadFor(?int $idUser, ?string $role): void
    {
        $ids = array_column(
            $this->visibleFor($idUser, $role)
                ->where('is_read', 0)
                ->findAll(500),
            'id'
        );

        if (!empty($ids)) {
            $this->whereIn('id', array_map('intval', $ids))
                ->set([
                    'is_read' => 1,
                    'read_at' => date('Y-m-d H:i:s'),
                ])
                ->update();
        }
    }
}
