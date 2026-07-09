<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1">Hasil Prioritas MOORA</h2>
            <p class="text-muted mb-0">
                Rekap hasil perangkingan prioritas pengadaan berdasarkan nilai Yi.
            </p>
        </div>
        <a href="<?= site_url('direktur/dashboard') ?>" class="btn btn-outline-secondary">
            Kembali Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm tm-dir-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle tm-dir-table mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ranking</th>
                            <th>Barang</th>
                            <th>Nomor Usulan</th>
                            <th>Unit</th>
                            <th>Jumlah</th>
                            <th>Nilai Yi</th>
                            <th>Total Estimasi</th>
                            <th>Status Validasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hasil)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($hasil as $row) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <span class="badge bg-primary">#<?= esc($row['ranking']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($row['nama_alternatif'] ?? '-') ?></strong><br>
                                        <small class="text-muted"><?= esc($row['kode_alternatif'] ?? '-') ?></small>
                                    </td>
                                    <td><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                                    <td><?= esc($row['unit_pengusul'] ?? '-') ?></td>
                                    <td><?= esc($row['jumlah'] ?? 0) ?> <?= esc($row['satuan'] ?? '') ?></td>
                                    <td><?= number_format((float) ($row['nilai_yi'] ?? 0), 6, ',', '.') ?></td>
                                    <td>Rp <?= number_format((float) ($row['total_estimasi'] ?? 0), 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= esc(ucwords(str_replace('_', ' ', $row['status_validasi'] ?? 'menunggu'))) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('direktur/validasi/detail/' . $row['id_usulan']) ?>"
                                           class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Belum ada hasil MOORA.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>