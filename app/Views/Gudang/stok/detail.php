<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$stok = (int) ($barang['stok'] ?? 0);
$min  = (int) ($barang['stok_minimum'] ?? 0);

if ($stok <= 0) {
    $badge = 'danger';
    $status = 'Habis';
    $keterangan = 'Barang tidak tersedia di gudang.';
} elseif ($min > 0 && $stok <= $min) {
    $badge = 'warning text-dark';
    $status = 'Minimum';
    $keterangan = 'Stok barang sudah mencapai batas minimum.';
} else {
    $badge = 'success';
    $status = 'Aman';
    $keterangan = 'Stok barang masih berada di atas batas minimum.';
}
?>

<div class="container-fluid">

    <div class="tm-page-header mb-4">
        <div>
            <h2 class="fw-bold mb-1">Detail Stok Barang</h2>
            <p class="mb-0">
                Pengaturan stok, batas minimum, dan informasi detail barang gudang.
            </p>
        </div>

        <a href="<?= site_url('gudang/stok') ?>" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali
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

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <?= esc($barang['nama_alternatif'] ?? '-') ?>
                            </h5>
                            <p class="text-muted mb-0">
                                <?= esc($barang['kode_alternatif'] ?? '-') ?> ·
                                <?= esc(ucwords($barang['jenis_barang'] ?? 'Alat')) ?>
                            </p>
                        </div>

                        <span class="badge bg-<?= $badge ?> px-3 py-2">
                            <?= esc($status) ?>
                        </span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="tm-info-box">
                                <p>Stok Saat Ini</p>
                                <h3><?= esc($stok) ?></h3>
                                <small><?= esc($barang['satuan'] ?? '-') ?></small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="tm-info-box">
                                <p>Batas Minimum</p>
                                <h3><?= esc($min) ?></h3>
                                <small><?= esc($barang['satuan'] ?? '-') ?></small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="tm-info-box">
                                <p>Status Stok</p>
                                <h3 class="fs-5"><?= esc($status) ?></h3>
                                <small><?= esc($keterangan) ?></small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <form action="<?= site_url('gudang/stok/detail/update/' . $barang['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <h5 class="fw-bold mb-3">Pengaturan Stok Barang</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stok Saat Ini</label>
                                <input type="number"
                                       name="stok"
                                       class="form-control"
                                       min="0"
                                       value="<?= esc($stok) ?>"
                                       required>
                                <small class="text-muted">
                                    Jumlah stok fisik barang yang tersedia di gudang.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Batas Stok Minimum</label>
                                <input type="number"
                                       name="stok_minimum"
                                       class="form-control"
                                       min="0"
                                       value="<?= esc($min) ?>"
                                       required>
                                <small class="text-muted">
                                    Batas minimum sebagai indikator peringatan stok.
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= site_url('gudang/stok') ?>" class="btn btn-secondary">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="card tm-card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Barang</h5>

                    <table class="table tm-table-detail mb-0">
                        <tr>
                            <th>Kode</th>
                            <td><?= esc($barang['kode_alternatif'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td><?= esc($barang['nama_alternatif'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td><?= esc(ucwords($barang['jenis_barang'] ?? 'Alat')) ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= esc($barang['kategori_barang'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Satuan</th>
                            <td><?= esc($barang['satuan'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>
                                <?= esc(ucwords(str_replace('_', ' ', $barang['kondisi_barang'] ?? 'Baik'))) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Estimasi</th>
                            <td>
                                Rp <?= number_format((float) ($barang['estimasi_harga'] ?? 0), 0, ',', '.') ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card tm-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Catatan Gudang</h5>

                    <p class="text-muted mb-3">
                        Batas stok minimum digunakan untuk memberikan peringatan otomatis ketika jumlah barang mulai menipis.
                    </p>

                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Barang dengan status minimum sebaiknya menjadi perhatian pada proses usulan pengadaan berikutnya.
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>