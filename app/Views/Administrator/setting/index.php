<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$settings = $settings ?? [];
$kriteria = $kriteria ?? [];
$totalBobot = (float) ($totalBobot ?? 0);
$tableReady = $tableReady ?? false;

$getSetting = static function ($key, $default = '') use ($settings) {
    return $settings[$key] ?? $default;
};
?>

<style>
.setting-hero {
    background: linear-gradient(135deg, #0b2a55, #144a8a);
    color: #fff;
    border-radius: 18px;
    padding: 22px;
    margin-bottom: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.setting-hero h2 { margin: 0; font-weight: 900; }
.setting-hero p { margin: 4px 0 0; opacity: .9; font-size: 13px; }
.setting-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
    border: 1px solid rgba(148, 163, 184, .16);
    margin-bottom: 18px;
}
.setting-card-header {
    padding: 16px 18px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.setting-card-body { padding: 18px; }
.setting-small { font-size: 12px; color: #64748b; }
.setting-table thead th {
    background: #0f2f5f !important;
    color: #fff !important;
    border-color: rgba(255,255,255,.12) !important;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .04em;
}
.setting-total-box {
    padding: 12px 14px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}
body.theme-dark .setting-card { background: #1e293b; border-color: rgba(255,255,255,.10); color: #f8fafc; }
body.theme-dark .setting-card-header { border-bottom-color: rgba(255,255,255,.12); }
body.theme-dark .setting-small { color: #cbd5e1; }
body.theme-dark .setting-total-box { background: #0f172a; border-color: rgba(255,255,255,.12); }
</style>

<div class="container-fluid">

    <div class="setting-hero">
        <div>
            <h2>Setting Sistem</h2>
            <p>Konfigurasi umum, parameter dataset, dan bobot MOORA untuk Administrator.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('administrator/dashboard') ?>" class="btn btn-light fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger"><i class="bi bi-x-circle me-1"></i><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (!$tableReady) : ?>
        <div class="alert alert-warning">
            <strong>Tabel setting_sistem belum tersedia.</strong> Jalankan SQL migrasi dulu agar setting dapat disimpan.
        </div>
    <?php endif; ?>

    <div class="setting-card">
        <div class="setting-card-header">
            <div>
                <strong>Konfigurasi Umum & Dataset MOORA</strong><br>
                <span class="setting-small">Admin hanya mengatur parameter. Nilai hasil MOORA tetap dihitung otomatis oleh sistem.</span>
            </div>
            <form action="<?= site_url('administrator/setting/reset-default') ?>" method="post" onsubmit="return confirm('Kembalikan setting ke default?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-danger btn-sm" <?= !$tableReady ? 'disabled' : '' ?>>
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                </button>
            </form>
        </div>

        <div class="setting-card-body">
            <form action="<?= site_url('administrator/setting/update-sistem') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-lg-6">
                        <label class="form-label fw-bold">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= esc(old('nama_perusahaan', $getSetting('nama_perusahaan', 'Perumda Tirta Musi Palembang'))) ?>" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-bold">Nama Aplikasi</label>
                        <input type="text" name="nama_aplikasi" class="form-control" value="<?= esc(old('nama_aplikasi', $getSetting('nama_aplikasi', 'SPK MOORA Pengadaan Barang'))) ?>" required>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold">Mode Perhitungan</label>
                        <select name="moora_mode" class="form-select" required>
                            <option value="per_usulan" <?= old('moora_mode', $getSetting('moora_mode', 'per_usulan')) === 'per_usulan' ? 'selected' : '' ?>>Per Usulan</option>
                            <option value="per_periode" <?= old('moora_mode', $getSetting('moora_mode', 'per_usulan')) === 'per_periode' ? 'selected' : '' ?>>Per Periode / Batch</option>
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold">Mode Cost</label>
                        <select name="moora_cost_mode" class="form-select" required>
                            <option value="standard_moora" <?= old('moora_cost_mode', $getSetting('moora_cost_mode', 'standard_moora')) === 'standard_moora' ? 'selected' : '' ?>>Standar MOORA: Benefit - Cost</option>
                            <option value="inverse_before_weight" <?= old('moora_cost_mode', $getSetting('moora_cost_mode', 'standard_moora')) === 'inverse_before_weight' ? 'selected' : '' ?>>Cost Dibalik 1/nilai Sebelum Bobot</option>
                        </select>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-bold">Auto Recalculate</label>
                        <select name="moora_auto_recalculate" class="form-select" required>
                            <option value="1" <?= old('moora_auto_recalculate', $getSetting('moora_auto_recalculate', '1')) === '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= old('moora_auto_recalculate', $getSetting('moora_auto_recalculate', '1')) === '0' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Status Dataset Default</label>
                        <input type="text" name="moora_status_dataset" class="form-control" value="<?= esc(old('moora_status_dataset', $getSetting('moora_status_dataset', 'diajukan,diverifikasi,disetujui'))) ?>" required>
                        <div class="setting-small mt-1">Pisahkan dengan koma. Contoh: diajukan,diverifikasi,disetujui</div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" <?= !$tableReady ? 'disabled' : '' ?>>
                            <i class="bi bi-save me-1"></i> Simpan Setting Sistem
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="setting-card">
        <div class="setting-card-header">
            <div>
                <strong>Setting Bobot & Jenis Kriteria MOORA</strong><br>
                <span class="setting-small">Total bobot kriteria aktif wajib tepat 1.00 agar kalkulasi dapat berjalan.</span>
            </div>
            <div class="setting-total-box">
                <span class="setting-small d-block">Total Bobot Aktif</span>
                <strong class="fs-4"><?= number_format($totalBobot, 2) ?></strong>
                <?= abs($totalBobot - 1.0) < 0.00001
                    ? '<span class="badge bg-success ms-2">VALID</span>'
                    : '<span class="badge bg-danger ms-2">TIDAK VALID</span>' ?>
            </div>
        </div>

        <div class="setting-card-body p-0">
            <form action="<?= site_url('administrator/setting/update-bobot') ?>" method="post" onsubmit="return confirm('Simpan perubahan bobot dan jenis kriteria?')">
                <?= csrf_field() ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle setting-table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis</th>
                                <th>Bobot</th>
                                <th>Skala</th>
                                <th>Status Aktif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($kriteria)) : ?>
                                <?php foreach ($kriteria as $row) : ?>
                                    <tr>
                                        <td><strong><?= esc($row['kode_kriteria'] ?? '-') ?></strong></td>
                                        <td><?= esc($row['nama_kriteria'] ?? '-') ?></td>
                                        <td style="min-width: 160px;">
                                            <select name="jenis[<?= esc($row['id']) ?>]" class="form-select form-select-sm">
                                                <option value="benefit" <?= ($row['jenis'] ?? '') === 'benefit' ? 'selected' : '' ?>>Benefit</option>
                                                <option value="cost" <?= ($row['jenis'] ?? '') === 'cost' ? 'selected' : '' ?>>Cost</option>
                                            </select>
                                        </td>
                                        <td style="min-width: 150px;">
                                            <input type="number" name="bobot[<?= esc($row['id']) ?>]" class="form-control form-control-sm" value="<?= esc($row['bobot'] ?? 0) ?>" min="0" max="1" step="0.01" required>
                                        </td>
                                        <td><?= esc($row['skala_min'] ?? 1) ?> - <?= esc($row['skala_max'] ?? 10) ?></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active[<?= esc($row['id']) ?>]" value="1" <?= ((int) ($row['is_active'] ?? 1) === 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label">Aktif</label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data kriteria.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan Bobot MOORA
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
