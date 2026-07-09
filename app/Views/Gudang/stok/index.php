<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$totalBarang = count($barang ?? []);
$totalAman = 0;
$totalMinimum = 0;
$totalHabis = 0;

foreach (($barang ?? []) as $row) {
    $stok = (int) ($row['stok'] ?? 0);
    $min  = (int) ($row['stok_minimum'] ?? 0);

    if ($stok <= 0) {
        $totalHabis++;
    } elseif ($min > 0 && $stok <= $min) {
        $totalMinimum++;
    } else {
        $totalAman++;
    }
}
?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <div>
            <h2 class="fw-bold mb-1">Data Stok Gudang</h2>
            <p class="mb-0">
                Pemantauan stok alat, material, dan aset gudang berdasarkan batas minimum barang.
            </p>
        </div>

        <a href="<?= site_url('gudang/stok/opname') ?>" class="btn btn-light">
            <i class="bi bi-clipboard-data me-1"></i> Stock Opname
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="tm-kpi-card tm-kpi-blue">
                <p>Total Barang</p>
                <h3><?= esc($totalBarang) ?></h3>
                <small>Seluruh barang gudang</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="tm-kpi-card tm-kpi-green">
                <p>Stok Aman</p>
                <h3><?= esc($totalAman) ?></h3>
                <small>Stok di atas minimum</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="tm-kpi-card tm-kpi-yellow">
                <p>Stok Minimum</p>
                <h3><?= esc($totalMinimum) ?></h3>
                <small>Perlu perhatian gudang</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="tm-kpi-card tm-kpi-red">
                <p>Stok Habis</p>
                <h3><?= esc($totalHabis) ?></h3>
                <small>Barang tidak tersedia</small>
            </div>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= site_url('gudang/stok') ?>" method="get" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cari Barang</label>
                    <input type="text"
                           name="keyword"
                           class="form-control"
                           placeholder="Cari kode, nama, atau kategori barang..."
                           value="<?= esc($keyword ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aman" <?= (($status ?? '') === 'aman') ? 'selected' : '' ?>>Aman</option>
                        <option value="minimum" <?= (($status ?? '') === 'minimum') ? 'selected' : '' ?>>Minimum</option>
                        <option value="habis" <?= (($status ?? '') === 'habis') ? 'selected' : '' ?>>Habis</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>

                    <a href="<?= site_url('gudang/stok') ?>" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php if ($totalMinimum > 0 || $totalHabis > 0) : ?>
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Terdapat <?= esc($totalMinimum) ?> barang mencapai stok minimum dan
            <?= esc($totalHabis) ?> barang stok habis.
        </div>
    <?php endif; ?>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Stok Barang</h5>
                    <p class="text-muted mb-0">
                        Setiap barang memiliki stok saat ini, batas minimum, dan status ketersediaan.
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table tm-table align-middle">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Minimum</th>
                            <th>Status</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($barang)) : ?>
                            <?php $no = 1; ?>
                            <?php foreach ($barang as $row) : ?>
                                <?php
                                $stok = (int) ($row['stok'] ?? 0);
                                $min  = (int) ($row['stok_minimum'] ?? 0);

                                if ($stok <= 0) {
                                    $badge = 'danger';
                                    $statusStok = 'Habis';
                                } elseif ($min > 0 && $stok <= $min) {
                                    $badge = 'warning text-dark';
                                    $statusStok = 'Minimum';
                                } else {
                                    $badge = 'success';
                                    $statusStok = 'Aman';
                                }
                                ?>

                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-center fw-semibold">
                                        <?= esc($row['kode_alternatif'] ?? '-') ?>
                                    </td>
                                    <td class="fw-semibold">
                                        <?= esc($row['nama_alternatif'] ?? '-') ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc(ucwords($row['jenis_barang'] ?? 'alat')) ?>
                                    </td>
                                    <td>
                                        <?= esc($row['kategori_barang'] ?? '-') ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($row['satuan'] ?? '-') ?>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <?= esc($stok) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= esc($min) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= esc($statusStok) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('gudang/stok/detail/' . $row['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Data stok barang tidak ditemukan.
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