<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$idAlternatif = $alternatif['id_alternatif'] ?? $alternatif['id'] ?? null;

$formAction = $action ?? site_url('administrator/alternatif/update/' . $idAlternatif);

$sumberData = $alternatif['sumber_data'] ?? 'Master Data Alat / Material';

if (is_array($sumberData)) {
    $sumberData = implode(', ', $sumberData);
}
?>

<div class="container-fluid">

    <!-- HEADER HALAMAN -->
    <div class="page-header mb-4">
        <div>
            <h5 class="fw-bold mb-1">
                <?= esc($title ?? 'Edit Alternatif') ?>
            </h5>
            <small class="text-muted">
                Perumda Tirta Musi Palembang
            </small>
        </div>

        <div>
            <a href="<?= site_url('administrator/alternatif') ?>" class="btn btn-light border shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- INFORMASI HALAMAN -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
        <div class="d-flex align-items-start gap-3">
            <div class="fs-4">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>
                <strong>Informasi Edit Alternatif</strong>
                <p class="mb-0 mt-1">
                    Halaman ini digunakan untuk mengubah data alternatif pengadaan operasional.
                    Data alternatif merupakan data gabungan yang berasal dari master data alat atau master data material
                    dan digunakan sebagai dasar dalam proses perhitungan metode MOORA.
                </p>
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGE ERROR -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- FLASH MESSAGE SUCCESS -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- CARD FORM -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="fw-bold mb-1">
                        Form Edit Alternatif
                    </h6>
                    <small class="text-muted">
                        Lengkapi dan sesuaikan data alternatif pengadaan.
                    </small>
                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    <i class="bi bi-diagram-3 me-1"></i> Data Alternatif
                </span>
            </div>
        </div>

        <div class="card-body p-4">

            <form action="<?= esc((string) $formAction) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">

                    <!-- KODE ALTERNATIF -->
                    <div class="col-md-6 mb-3">
                        <label for="kode_alternatif" class="form-label fw-semibold">
                            Kode Alternatif <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="kode_alternatif"
                               name="kode_alternatif"
                               class="form-control <?= session('errors.kode_alternatif') ? 'is-invalid' : '' ?>"
                               value="<?= esc((string) old('kode_alternatif', $alternatif['kode_alternatif'] ?? '')) ?>"
                               placeholder="Contoh: A001"
                               required>

                        <?php if (session('errors.kode_alternatif')) : ?>
                            <div class="invalid-feedback">
                                <?= esc((string) session('errors.kode_alternatif')) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- NAMA ALTERNATIF -->
                    <div class="col-md-6 mb-3">
                        <label for="nama_alternatif" class="form-label fw-semibold">
                            Nama Alternatif <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               id="nama_alternatif"
                               name="nama_alternatif"
                               class="form-control <?= session('errors.nama_alternatif') ? 'is-invalid' : '' ?>"
                               value="<?= esc((string) old('nama_alternatif', $alternatif['nama_alternatif'] ?? '')) ?>"
                               placeholder="Contoh: Pompa Air Sentrifugal"
                               required>

                        <?php if (session('errors.nama_alternatif')) : ?>
                            <div class="invalid-feedback">
                                <?= esc((string) session('errors.nama_alternatif')) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- KATEGORI BARANG -->
                    <div class="col-md-6 mb-3">
                        <label for="kategori_barang" class="form-label fw-semibold">
                            Kategori Barang
                        </label>

                        <input type="text"
                               id="kategori_barang"
                               name="kategori_barang"
                               class="form-control"
                               value="<?= esc((string) old('kategori_barang', $alternatif['kategori_barang'] ?? '')) ?>"
                               placeholder="Contoh: Peralatan Mekanikal / Jaringan Pipa">
                    </div>

                    <!-- SATUAN -->
                    <div class="col-md-6 mb-3">
                        <label for="satuan" class="form-label fw-semibold">
                            Satuan
                        </label>

                        <input type="text"
                               id="satuan"
                               name="satuan"
                               class="form-control"
                               value="<?= esc((string) old('satuan', $alternatif['satuan'] ?? '')) ?>"
                               placeholder="Contoh: unit, batang, meter, pcs">
                    </div>

                    <!-- ESTIMASI HARGA -->
                    <div class="col-md-6 mb-3">
                        <label for="estimasi_harga" class="form-label fw-semibold">
                            Estimasi Harga
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   id="estimasi_harga"
                                   name="estimasi_harga"
                                   class="form-control"
                                   value="<?= esc((string) old('estimasi_harga', $alternatif['estimasi_harga'] ?? 0)) ?>"
                                   placeholder="Contoh: 2500000"
                                   min="0">
                        </div>

                        <small class="text-muted">
                            Masukkan angka tanpa titik atau koma.
                        </small>
                    </div>

                    <!-- SUMBER DATA -->
                    <div class="col-md-6 mb-3">
                        <label for="sumber_data" class="form-label fw-semibold">
                            Sumber Data
                        </label>

                        <input type="text"
                               id="sumber_data"
                               class="form-control bg-light"
                               value="<?= esc((string) $sumberData) ?>"
                               readonly>

                        <small class="text-muted">
                            Informasi ini menunjukkan asal data alternatif.
                        </small>
                    </div>

                    <!-- SPESIFIKASI -->
                    <div class="col-md-12 mb-3">
                        <label for="spesifikasi" class="form-label fw-semibold">
                            Spesifikasi
                        </label>

                        <textarea id="spesifikasi"
                                  name="spesifikasi"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Masukkan spesifikasi barang"><?= esc((string) old('spesifikasi', $alternatif['spesifikasi'] ?? '')) ?></textarea>
                    </div>

                    <!-- KETERANGAN -->
                    <div class="col-md-12 mb-4">
                        <label for="keterangan" class="form-label fw-semibold">
                            Keterangan
                        </label>

                        <textarea id="keterangan"
                                  name="keterangan"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Masukkan keterangan tambahan alternatif"><?= esc((string) old('keterangan', $alternatif['keterangan'] ?? '')) ?></textarea>
                    </div>

                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-top pt-3">
                    <a href="<?= site_url('administrator/alternatif') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?= $this->endSection() ?>