<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <h2 class="fw-bold mb-1">Review Usulan - Manajer Umum</h2>
        <p class="mb-0 text-muted">Usulan yang sudah selesai MOORA dapat direkomendasikan ke Direktur atau dikembalikan untuk revisi.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Usulan</th>
                        <th>Unit Pengusul</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Status Validasi</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usulan) && is_array($usulan)) : $no = 1; foreach ($usulan as $u) : ?>
                        <?php $status = ucwords(str_replace('_', ' ', $u['status'] ?? '-')); ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <strong><?= esc($u['nomor_usulan'] ?? '-') ?></strong><br>
                                <small class="text-muted"><?= esc($u['nama_pengusul'] ?? '-') ?></small>
                            </td>
                            <td><?= esc($u['unit_pengusul'] ?? '-') ?></td>
                            <td><?= isset($u['tanggal_usulan']) ? date('d-m-Y', strtotime($u['tanggal_usulan'])) : '-' ?></td>
                            <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= esc($status) ?></span></td>
                            <td><?= esc(ucwords(str_replace('_', ' ', $u['status_validasi'] ?? '-'))) ?></td>
                            <td class="text-end">
                                <a href="<?= site_url('manajer-umum/usulan/detail/' . $u['id']) ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i> Detail Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada usulan yang perlu direview.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
