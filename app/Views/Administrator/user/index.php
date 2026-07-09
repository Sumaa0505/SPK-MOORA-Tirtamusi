<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Manajemen User</h2>
            <p class="text-muted mb-0">Kelola akun pengguna sistem berdasarkan role.</p>
        </div>

        <a href="<?= site_url('administrator/user/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah User
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">

                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($users)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($users as $u) : ?>

                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td class="text-start fw-semibold">
                                        <?= esc($u['nama_lengkap']) ?>
                                    </td>

                                    <td><?= esc($u['username']) ?></td>

                                    <td>
                                        <?php
                                        $role = $u['role'];
                                        $badge = 'secondary';

                                        if ($role == 'administrator') $badge = 'primary';
                                        elseif ($role == 'sub_unit') $badge = 'info';
                                        elseif ($role == 'gudang') $badge = 'warning';
                                        elseif ($role == 'manajer_umum') $badge = 'dark';
                                        elseif ($role == 'direktur') $badge = 'success';
                                        elseif ($role == 'pengadaan') $badge = 'danger';
                                        ?>

                                        <span class="badge bg-<?= $badge ?>">
                                            <?= ucfirst(str_replace('_', ' ', $role)) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($u['is_active']) : ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>

                                        <a href="<?= site_url('administrator/user/edit/' . $u['id']) ?>"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= site_url('administrator/user/toggle/' . $u['id']) ?>"
                                           class="btn btn-sm <?= $u['is_active'] ? 'btn-danger' : 'btn-success' ?>"
                                           onclick="return confirm('Ubah status user ini?')">
                                            <i class="bi <?= $u['is_active'] ? 'bi-x-circle' : 'bi-check-circle' ?>"></i>
                                        </a>

                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-muted py-4">
                                    Belum ada data user.
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