<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Dashboard Gudang</h2>
            <p class="mb-0 text-muted">Ringkasan kegiatan dan status usulan masuk SPK MOORA.</p>
        </div>
    </div>

    <div class="row g-4">

        <!-- Usulan Masuk -->
        <div class="col-md-4">
            <div class="card tm-card border-0 shadow-sm text-center p-3">
                <h5 class="fw-bold mb-2">Usulan Masuk</h5>
                <p class="display-5 mb-2"><?= esc((int)$usulanMasukCount) ?></p>
                <p class="text-muted">Usulan yang menunggu verifikasi</p>
                <a href="<?= site_url('gudang/usulan-masuk') ?>" class="btn btn-primary btn-sm mt-2">Lihat Detail</a>
            </div>
        </div>

        <!-- 5 Usulan Terbaru -->
        <div class="col-md-8">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">5 Usulan Terbaru</h5>
                    <?php if (!empty($usulanTerbaru)): ?>
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Usulan</th>
                                    <th>Unit Pengusul</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; foreach ($usulanTerbaru as $usulan): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= esc($usulan['nomor_usulan'] ?? '-') ?></td>
                                    <td><?= esc($usulan['unit_pengusul'] ?? '-') ?></td>
                                    <td><?= !empty($usulan['tanggal_usulan']) ? date('d/m/Y', strtotime($usulan['tanggal_usulan'])) : '-' ?></td>
                                    <td>
                                        <?php 
                                            $statusText = $usulan['status_tampilan'] ?? $usulan['status'] ?? '-';
                                        ?>
                                        <span class="badge bg-info"><?= esc(ucwords(str_replace('_', ' ', $statusText))) ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('gudang/usulan-masuk/detail/' . ($usulan['id'] ?? 0)) ?>" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted py-3">Belum ada usulan terbaru.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 5 Ranking MOORA Terbaru -->
        <div class="col-12">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">5 Ranking MOORA Terbaru</h5>
                    <?php if (!empty($rankingMoora)): ?>
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ranking</th>
                                    <th>Nomor Usulan</th>
                                    <th>Nama Barang</th>
                                    <th>Kode Alternatif</th>
                                    <th>Nilai Yi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rankingMoora as $i => $row): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= esc($row['nomor_usulan'] ?? '-') ?></td>
                                        <td><?= esc($row['nama_alternatif'] ?? '-') ?></td>
                                        <td><?= esc($row['kode_alternatif'] ?? '-') ?></td>
                                        <td><?= isset($row['nilai_yi']) ? number_format((float)$row['nilai_yi'], 4) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted py-3">Belum ada hasil MOORA yang dihitung.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>