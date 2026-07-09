<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <h2 class="fw-bold mb-3">Usulan Masuk Gudang</h2>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (!empty($usulanMasuk)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nomor Usulan</th>
                                <th>Tanggal Usulan</th>
                                <th>Unit Pengusul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($usulanMasuk as $usulan) : ?>
                                <?php
                                    $status = $usulan['status'] ?? 'diajukan';
                                    $statusLabel = ($status === 'banding_gudang') ? 'Gudang Mengajukan Banding' : ucwords(str_replace('_',' ', $status));
                                    $badgeStatus = ($status === 'banding_gudang') ? 'danger' : 'primary';
                                ?>
                                <tr class="text-center">
                                    <td><?= $i++ ?></td>
                                    <td><?= esc($usulan['nomor_usulan']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($usulan['tanggal_usulan'])) ?></td>
                                    <td><?= esc($usulan['unit_pengusul']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $badgeStatus ?>"><?= esc($statusLabel) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('gudang/usulan-masuk/detail/' . $usulan['id']) ?>" class="btn btn-info btn-sm text-white">Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p class="text-muted text-center py-3">Tidak ada usulan masuk saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>