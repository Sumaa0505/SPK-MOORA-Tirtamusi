<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$mode = $mode ?? 'create';
$perbaikan = $perbaikan ?? [];
?>

<div class="container-fluid">

    <div class="page-header mb-4">
        <div>
            <h5 class="fw-bold mb-1"><?= esc($title ?? 'Form Perbaikan Alat') ?></h5>
            <small class="text-muted">Pendataan alat yang diperbaiki pada unit Perumda Tirta Musi Palembang</small>
        </div>

        <a href="<?= site_url('administrator/master-data/perbaikan-alat') ?>" class="btn btn-light border shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h6 class="fw-bold mb-1">Form Data Perbaikan Alat</h6>
            <small class="text-muted">
                Isi data alat, unit pemakai, kerusakan, tindakan perbaikan, dan status perbaikan.
            </small>
        </div>

        <div class="card-body p-4">

            <form action="<?= esc($action) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="id_alternatif" class="form-label fw-semibold">
                            Pilih Alat <span class="text-danger">*</span>
                        </label>

                        <select name="id_alternatif" id="id_alternatif" class="form-select" required>
                            <option value="">-- Pilih Alat Operasional --</option>
                            <?php foreach ($alat as $item) : ?>
                                <option value="<?= esc($item['id']) ?>"
                                    <?= old('id_alternatif', $perbaikan['id_alternatif'] ?? '') == $item['id'] ? 'selected' : '' ?>>
                                    <?= esc($item['kode_alternatif']) ?> - <?= esc($item['nama_alternatif']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_pemakai" class="form-label fw-semibold">
                            Unit Pemakai <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="unit_pemakai"
                               id="unit_pemakai"
                               class="form-control"
                               value="<?= esc(old('unit_pemakai', $perbaikan['unit_pemakai'] ?? '')) ?>"
                               placeholder="Contoh: Unit Produksi / Unit Distribusi / Unit Pelayanan"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lokasi_unit" class="form-label fw-semibold">
                            Lokasi Unit
                        </label>

                        <input type="text"
                               name="lokasi_unit"
                               id="lokasi_unit"
                               class="form-control"
                               value="<?= esc(old('lokasi_unit', $perbaikan['lokasi_unit'] ?? '')) ?>"
                               placeholder="Contoh: IPA Rambutan, Gudang Utama, Area Distribusi Ilir Barat">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="penanggung_jawab" class="form-label fw-semibold">
                            Penanggung Jawab
                        </label>

                        <input type="text"
                               name="penanggung_jawab"
                               id="penanggung_jawab"
                               class="form-control"
                               value="<?= esc(old('penanggung_jawab', $perbaikan['penanggung_jawab'] ?? '')) ?>"
                               placeholder="Nama petugas atau kepala unit">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tanggal_perbaikan" class="form-label fw-semibold">
                            Tanggal Mulai Perbaikan <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="tanggal_perbaikan"
                               id="tanggal_perbaikan"
                               class="form-control"
                               value="<?= esc(old('tanggal_perbaikan', $perbaikan['tanggal_perbaikan'] ?? date('Y-m-d'))) ?>"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tanggal_target" class="form-label fw-semibold">
                            Target Selesai
                        </label>

                        <input type="date"
                               name="tanggal_target"
                               id="tanggal_target"
                               class="form-control"
                               value="<?= esc(old('tanggal_target', $perbaikan['tanggal_target'] ?? '')) ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="tanggal_selesai" class="form-label fw-semibold">
                            Tanggal Selesai
                        </label>

                        <input type="date"
                               name="tanggal_selesai"
                               id="tanggal_selesai"
                               class="form-control"
                               value="<?= esc(old('tanggal_selesai', $perbaikan['tanggal_selesai'] ?? '')) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prioritas" class="form-label fw-semibold">
                            Prioritas Perbaikan
                        </label>

                        <select name="prioritas" id="prioritas" class="form-select">
                            <?php $prioritas = old('prioritas', $perbaikan['prioritas'] ?? 'sedang'); ?>
                            <option value="rendah" <?= $prioritas === 'rendah' ? 'selected' : '' ?>>Rendah</option>
                            <option value="sedang" <?= $prioritas === 'sedang' ? 'selected' : '' ?>>Sedang</option>
                            <option value="tinggi" <?= $prioritas === 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
                            <option value="darurat" <?= $prioritas === 'darurat' ? 'selected' : '' ?>>Darurat</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status_perbaikan" class="form-label fw-semibold">
                            Status Perbaikan <span class="text-danger">*</span>
                        </label>

                        <?php $status = old('status_perbaikan', $perbaikan['status_perbaikan'] ?? 'diajukan'); ?>

                        <select name="status_perbaikan" id="status_perbaikan" class="form-select" required>
                            <option value="diajukan" <?= $status === 'diajukan' ? 'selected' : '' ?>>Diajukan</option>
                            <option value="diproses" <?= $status === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                            <option value="selesai" <?= $status === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="biaya_perbaikan" class="form-label fw-semibold">
                            Biaya Perbaikan
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number"
                                   name="biaya_perbaikan"
                                   id="biaya_perbaikan"
                                   class="form-control"
                                   value="<?= esc(old('biaya_perbaikan', $perbaikan['biaya_perbaikan'] ?? 0)) ?>"
                                   min="0"
                                   placeholder="Contoh: 1500000">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="kerusakan" class="form-label fw-semibold">
                            Kerusakan <span class="text-danger">*</span>
                        </label>

                        <textarea name="kerusakan"
                                  id="kerusakan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Jelaskan kerusakan alat"
                                  required><?= esc(old('kerusakan', $perbaikan['kerusakan'] ?? '')) ?></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="tindakan_perbaikan" class="form-label fw-semibold">
                            Tindakan Perbaikan
                        </label>

                        <textarea name="tindakan_perbaikan"
                                  id="tindakan_perbaikan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Jelaskan tindakan perbaikan yang dilakukan"><?= esc(old('tindakan_perbaikan', $perbaikan['tindakan_perbaikan'] ?? '')) ?></textarea>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="catatan" class="form-label fw-semibold">
                            Catatan
                        </label>

                        <textarea name="catatan"
                                  id="catatan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Catatan tambahan"><?= esc(old('catatan', $perbaikan['catatan'] ?? '')) ?></textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-top pt-3">
                    <a href="<?= site_url('administrator/master-data/perbaikan-alat') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>
                        <?= $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Data' ?>
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?= $this->endSection() ?>