<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1"><?= esc($title ?? 'Form Kriteria') ?></h3>
        <p class="text-muted mb-0">Isi data kriteria yang digunakan dalam perhitungan MOORA.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="<?= esc($action) ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Kode Kriteria</label>
                    <input type="text"
                           name="kode_kriteria"
                           class="form-control"
                           value="<?= old('kode_kriteria', $kriteria['kode_kriteria'] ?? '') ?>"
                           placeholder="Contoh: C1"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Kriteria</label>
                    <input type="text"
                           name="nama_kriteria"
                           class="form-control"
                           value="<?= old('nama_kriteria', $kriteria['nama_kriteria'] ?? '') ?>"
                           placeholder="Contoh: Tingkat Urgensi"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kriteria</label>
                    <select name="jenis" class="form-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="benefit" <?= old('jenis', $kriteria['jenis'] ?? '') === 'benefit' ? 'selected' : '' ?>>
                            Benefit
                        </option>
                        <option value="cost" <?= old('jenis', $kriteria['jenis'] ?? '') === 'cost' ? 'selected' : '' ?>>
                            Cost
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bobot</label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           max="1"
                           name="bobot"
                           class="form-control"
                           value="<?= old('bobot', $kriteria['bobot'] ?? '') ?>"
                           placeholder="Contoh: 0.25"
                           required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>

                    <a href="<?= site_url('administrator/kriteria') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<?= $this->endSection() ?>