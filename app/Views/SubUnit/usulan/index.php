<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Usulan Saya</h2>
            <p class="text-muted mb-0">
                Daftar usulan pengadaan peralatan operasional yang diajukan oleh sub unit.
            </p>
        </div>

        <a href="<?= site_url('sub-unit/usulan/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Buat Usulan
        </a>
    </div>

    <!-- Flashdata -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Nomor Usulan</th>
                            <th>Tanggal</th>
                            <th>Unit Pengusul</th>
                            <th>Status</th>
                            <th>Status Validasi</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($usulanList)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($usulanList as $row) : ?>
                                <?php
                                $status = $row['status'] ?? 'draft';
                                $isBanding = ($status === 'banding_gudang');

                                $badgeStatus = $isBanding ? 'danger' : match ($status) {
                                    'draft'        => 'secondary',
                                    'diajukan'     => 'primary',
                                    'diverifikasi' => 'info',
                                    'disetujui'    => 'success',
                                    'ditolak'      => 'danger',
                                    'direvisi'     => 'warning',
                                    default        => 'secondary',
                                };

                                $statusLabel = $isBanding ? 'Gudang Mengajukan Banding' : ucwords(str_replace('_', ' ', $status));

                                $validasi = $row['status_validasi'] ?? 'menunggu';
                                $badgeValidasi = match ($validasi) {
                                    'disetujui' => 'success',
                                    'ditolak'   => 'danger',
                                    'menunggu'  => 'secondary',
                                    default     => 'secondary',
                                };
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="fw-semibold"><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                                    <td class="text-center"><?= esc($row['tanggal_usulan'] ?? '-') ?></td>
                                    <td><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $badgeStatus ?>"><?= esc($statusLabel) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $badgeValidasi ?>"><?= esc(ucwords(str_replace('_', ' ', $validasi))) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('sub-unit/usulan/detail/' . $row['id']) ?>" class="btn btn-sm btn-info text-white">Detail</a>

                                        <?php if ($status === 'draft') : ?>
                                            <form action="<?= site_url('sub-unit/usulan/ajukan/' . $row['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Ajukan usulan ini ke Seksi Gudang?')">
                                                    Ajukan
                                                </button>
                                            </form>
                                        <?php elseif ($isBanding) : ?>
                                            <a href="<?= site_url('sub-unit/usulan/edit/' . $row['id']) ?>" class="btn btn-sm btn-warning">Revisi</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada usulan pengadaan.
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