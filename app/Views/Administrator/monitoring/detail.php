<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Detail Usulan</h2>
            <p class="text-muted mb-0">
                Informasi lengkap usulan pengadaan dan detail item yang diajukan.
            </p>
        </div>

        <a href="<?= site_url('administrator/monitoring') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Informasi Usulan</h5>
                            <p class="text-muted mb-0">Data utama usulan pengadaan.</p>
                        </div>

                        <span class="badge bg-<?= esc($usulan['badge_progress'] ?? 'secondary') ?> px-3 py-2">
                            <?= esc($usulan['progress'] ?? 'Menunggu') ?>
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <tr>
                                <th width="230">Nomor Usulan</th>
                                <td class="fw-semibold"><?= esc($usulan['nomor_usulan'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Usulan</th>
                                <td><?= esc($usulan['tanggal_usulan'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Unit Pengusul</th>
                                <td><?= esc($usulan['unit_pengusul'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Nama Pengusul</th>
                                <td><?= esc($usulan['nama_lengkap'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Status Proses</th>
                                <td>
                                    <span class="badge bg-<?= esc($usulan['badge_status'] ?? 'secondary') ?>">
                                        <?= esc($usulan['status'] ?? 'Menunggu') ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Status Validasi</th>
                                <td>
                                    <span class="badge bg-<?= esc($usulan['badge_status_validasi'] ?? 'secondary') ?>">
                                        <?= esc($usulan['status_validasi'] ?? 'Menunggu') ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold mb-1">Detail Item Usulan</h5>
                    <p class="text-muted">Daftar peralatan/alternatif yang diajukan pada usulan ini.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="60">No</th>
                                    <th>Kode</th>
                                    <th>Nama Alternatif</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Estimasi Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($detail)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($detail as $row) : ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-center fw-semibold"><?= esc($row['kode_alternatif'] ?? '-') ?></td>
                                            <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                            <td><?= esc($row['kategori_barang'] ?? '-') ?></td>
                                            <td class="text-center"><?= esc($row['satuan'] ?? '-') ?></td>
                                            <td class="text-end">
                                                Rp <?= number_format((float) ($row['estimasi_harga'] ?? 0), 0, ',', '.') ?>
                                            </td>
                                        </tr>

                                        <?php if (!empty($row['spesifikasi'])) : ?>
                                            <tr>
                                                <td></td>
                                                <td colspan="5">
                                                    <strong>Spesifikasi:</strong>
                                                    <?= esc($row['spesifikasi']) ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Belum ada detail item pada usulan ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Catatan Proses</h5>

                    <div class="mb-3">
                        <strong>Catatan Pengusul</strong>
                        <p class="text-muted mb-0">
                            <?= esc($usulan['catatan_pengusul'] ?? '-') ?>
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Catatan Gudang</strong>
                        <p class="text-muted mb-0">
                            <?= esc($usulan['catatan_gudang'] ?? '-') ?>
                        </p>
                    </div>

                    <div>
                        <strong>Catatan Direktur</strong>
                        <p class="text-muted mb-0">
                            <?= esc($usulan['catatan_direktur'] ?? '-') ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Alur Status</h5>

                    <div class="mb-3">
                        <strong>1. Sub Unit</strong>
                        <p class="text-muted mb-0">Mengajukan kebutuhan pengadaan.</p>
                    </div>

                    <div class="mb-3">
                        <strong>2. Gudang</strong>
                        <p class="text-muted mb-0">Memeriksa ketersediaan dan memverifikasi usulan.</p>
                    </div>

                    <div class="mb-3">
                        <strong>3. Sistem MOORA</strong>
                        <p class="text-muted mb-0">Mengolah data dan menghasilkan prioritas.</p>
                    </div>

                    <div>
                        <strong>4. Direktur</strong>
                        <p class="text-muted mb-0">Memvalidasi hasil pengadaan.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>