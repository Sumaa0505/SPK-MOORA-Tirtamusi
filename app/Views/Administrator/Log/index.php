<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Log Aktivitas</h2>
        <p class="text-muted mb-0">Riwayat aktivitas pengguna sistem.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">

                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama User</th>
                            <th>Modul</th>
                            <th>Aktivitas</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($log)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($log as $l) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td class="fw-semibold">
                                        <?= esc($l['nama_lengkap'] ?? '-') ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= esc($l['modul'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="text-start">
                                        <?= esc($l['aktivitas'] ?? '-') ?>
                                    </td>
                                    <td><?= esc($l['ip_address'] ?? '-') ?></td>
                                    <td><?= esc($l['created_at'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-muted py-4">
                                    Belum ada aktivitas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>