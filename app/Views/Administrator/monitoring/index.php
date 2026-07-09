<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Monitoring Usulan</h2>
            <p class="text-muted mb-0">
                Pantau seluruh proses usulan pengadaan dari Sub Unit, Gudang, Sistem MOORA, hingga Direktur.
            </p>
        </div>

        <span class="badge bg-primary px-3 py-2">
            <i class="bi bi-clipboard-data me-1"></i> Monitoring Administrator
        </span>
    </div>

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Usulan</p>
                    <h3 class="fw-bold mb-0"><?= number_format($totalUsulan ?? 0, 0, ',', '.') ?></h3>
                    <small class="text-muted">Seluruh data usulan</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Menunggu</p>
                    <h3 class="fw-bold mb-0"><?= number_format($totalMenunggu ?? 0, 0, ',', '.') ?></h3>
                    <small class="text-muted">Belum diproses lanjut</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Diproses</p>
                    <h3 class="fw-bold mb-0"><?= number_format($totalDiproses ?? 0, 0, ',', '.') ?></h3>
                    <small class="text-muted">Verifikasi atau MOORA</small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Disetujui / Ditolak</p>
                    <h3 class="fw-bold mb-0">
                        <?= number_format($totalDisetujui ?? 0, 0, ',', '.') ?> /
                        <?= number_format($totalDitolak ?? 0, 0, ',', '.') ?>
                    </h3>
                    <small class="text-muted">Keputusan akhir</small>
                </div>
            </div>
        </div>

    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Tabel Monitoring Usulan</h5>
                    <p class="text-muted mb-0">Daftar usulan pengadaan peralatan operasional.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Nomor Usulan</th>
                            <th>Tanggal</th>
                            <th>Unit Pengusul</th>
                            <th>Pengusul</th>
                            <th>Status Proses</th>
                            <th>Status Validasi</th>
                            <th>Progress</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($usulan)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($usulan as $row) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>

                                    <td class="fw-semibold">
                                        <?= esc($row['nomor_usulan'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= esc($row['tanggal_usulan'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= esc($row['unit_pengusul'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= esc($row['nama_lengkap'] ?? '-') ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-<?= esc($row['badge_status'] ?? 'secondary') ?>">
                                            <?= esc($row['status'] ?? 'Menunggu') ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-<?= esc($row['badge_status_validasi'] ?? 'secondary') ?>">
                                            <?= esc($row['status_validasi'] ?? 'Menunggu') ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-<?= esc($row['badge_progress'] ?? 'secondary') ?>">
                                            <?= esc($row['progress'] ?? 'Menunggu') ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= site_url('administrator/monitoring/detail/' . $row['id']) ?>"
                                           class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada data usulan.
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