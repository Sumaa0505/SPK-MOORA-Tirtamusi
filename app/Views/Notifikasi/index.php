<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1">Notifikasi</h2>
            <p class="text-muted mb-0">Pemberitahuan antar-role sesuai alur workflow SPK MOORA.</p>
        </div>
        <form action="<?= site_url('notifikasi/baca-semua') ?>" method="post">
            <?= csrf_field() ?>
            <button class="btn btn-outline-primary" <?= empty($unreadCount) ? 'disabled' : '' ?>>
                <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
        <?php if (session()->getFlashdata($key)): ?>
            <div class="alert alert-<?= $type ?>"><?= esc(session()->getFlashdata($key)) ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:58px;" class="text-center">Status</th>
                            <th>Judul</th>
                            <th>Pesan</th>
                            <th>Tipe</th>
                            <th>Waktu</th>
                            <th class="text-center" style="width:120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $row): ?>
                            <?php
                            $isRead = (int) ($row['is_read'] ?? 0) === 1;
                            $tipe = $row['tipe'] ?? 'info';
                            $badge = match ($tipe) {
                                'success' => 'bg-success',
                                'warning' => 'bg-warning text-dark',
                                'danger' => 'bg-danger',
                                'approval' => 'bg-primary',
                                'pengadaan' => 'bg-info text-dark',
                                'moora' => 'bg-dark',
                                default => 'bg-secondary',
                            };
                            ?>
                            <tr class="<?= $isRead ? '' : 'table-primary' ?>">
                                <td class="text-center">
                                    <?= $isRead
                                        ? '<i class="bi bi-envelope-open text-muted"></i>'
                                        : '<i class="bi bi-envelope-fill text-primary"></i>' ?>
                                </td>
                                <td>
                                    <strong><?= esc($row['judul'] ?? '-') ?></strong>
                                    <?php if (!$isRead): ?><span class="badge bg-danger ms-1">Baru</span><?php endif; ?>
                                </td>
                                <td><?= esc($row['pesan'] ?? '-') ?></td>
                                <td><span class="badge <?= $badge ?>"><?= esc(ucwords(str_replace('_', ' ', $tipe))) ?></span></td>
                                <td><small class="text-muted"><?= !empty($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : '-' ?></small></td>
                                <td class="text-center">
                                    <form action="<?= site_url('notifikasi/baca/' . $row['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <?= !empty($row['link']) ? 'Buka' : 'Baca' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada notifikasi.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
