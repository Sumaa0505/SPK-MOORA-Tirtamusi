<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$mode = $mode ?? '';
$kriteria = $kriteria ?? [];
$rows = $rows ?? [];
$scenarios = $scenarios ?? [];
$summary = $summary ?? [];
$baseline = $scenarios['baseline'] ?? [];
$urgensi = $scenarios['urgensi_plus_15'] ?? [];
$biaya = $scenarios['biaya_plus_15'] ?? [];
$dampak = $scenarios['dampak_plus_15'] ?? [];
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Training MOORA Admin</h2>
            <p class="mb-0 text-muted">Simulator sensitivitas bobot untuk evaluasi ranking. Tidak mengubah data produksi dan tidak memproses usulan aktif.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('administrator/kalkulasi-moora') ?>" class="btn btn-outline-primary btn-sm">Monitoring MOORA</a>
            <a href="<?= site_url('administrator/setting') ?>" class="btn btn-light btn-sm">Setting Bobot</a>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm">
        <strong>Mode final:</strong> RKA dihitung sebagai satu dokumen agregat, sedangkan Pesan Cepat dihitung per item barang. Halaman ini hanya membaca hasil aktif dari view latest/global ranking.
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Dataset Aktif</small><h3 class="fw-bold mb-0"><?= number_format((int)($summary['total_rows'] ?? 0), 0, ',', '.') ?></h3><span class="badge bg-primary">moora_selesai</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">RKA Agregat</small><h3 class="fw-bold mb-0"><?= number_format((int)($summary['rka_count'] ?? 0), 0, ',', '.') ?></h3><span class="badge bg-success">dokumen</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Pesan Cepat</small><h3 class="fw-bold mb-0"><?= number_format((int)($summary['item_count'] ?? 0), 0, ',', '.') ?></h3><span class="badge bg-info text-dark">item</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Total Bobot</small><h3 class="fw-bold mb-0"><?= number_format((float)($summary['total_bobot'] ?? 0), 2) ?></h3><?= !empty($summary['bobot_valid']) ? '<span class="badge bg-success">VALID</span>' : '<span class="badge bg-danger">CEK BOBOT</span>' ?></div></div></div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" action="<?= site_url('administrator/training-moora') ?>" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filter Mode</label>
                    <select name="mode" class="form-select">
                        <option value="" <?= $mode === '' ? 'selected' : '' ?>>Semua Mode</option>
                        <option value="rka_aggregate" <?= $mode === 'rka_aggregate' ? 'selected' : '' ?>>RKA - Agregasi Dokumen</option>
                        <option value="item_based" <?= $mode === 'item_based' ? 'selected' : '' ?>>Pesan Cepat - Per Item</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-fill" type="submit">Terapkan</button>
                    <a href="<?= site_url('administrator/training-moora') ?>" class="btn btn-outline-secondary flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><strong>Bobot Aktif C1-C5</strong></div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Kode</th><th>Kriteria</th><th>Jenis</th><th class="text-end">Bobot</th></tr></thead>
                        <tbody>
                        <?php foreach ($kriteria as $k) : ?>
                            <tr>
                                <td><span class="badge bg-dark"><?= esc($k['kode_kriteria'] ?? '-') ?></span></td>
                                <td><?= esc($k['nama_kriteria'] ?? '-') ?></td>
                                <td><span class="badge <?= ($k['jenis'] ?? '') === 'cost' ? 'bg-danger' : 'bg-success' ?>"><?= esc($k['jenis'] ?? '-') ?></span></td>
                                <td class="text-end fw-bold"><?= number_format((float)($k['bobot'] ?? 0), 4) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white"><strong>Indikator Sensitivitas</strong></div>
                <div class="card-body">
                    <p class="mb-2 text-muted">Simulator membaca rincian normalisasi dari hasil MOORA aktif, lalu menghitung ulang ranking secara virtual.</p>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>C1 Urgensi dinaikkan 15%</span><strong><?= number_format((int)($summary['changed_when_urgency_boosted'] ?? 0), 0, ',', '.') ?> ranking berubah</strong></div>
                    <div class="d-flex justify-content-between border-bottom py-2"><span>Dataset baseline</span><strong><?= number_format(count($baseline), 0, ',', '.') ?> baris</strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Keterangan</span><strong class="text-success">Non-destruktif</strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white"><strong>Ranking Global Baseline</strong></div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Rank</th><th>Usulan</th><th>Mode</th><th>Objek</th><th class="text-end">Yi Aktif</th></tr></thead>
                <tbody>
                <?php if (!empty($baseline)) : ?>
                    <?php foreach (array_slice($baseline, 0, 25) as $row) : ?>
                        <?php $isRka = ($row['mode_hitung'] ?? '') === 'rka_aggregate'; ?>
                        <tr>
                            <td><span class="badge bg-warning text-dark">#<?= esc($row['ranking_simulasi'] ?? $row['ranking'] ?? '-') ?></span></td>
                            <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['unit_pengusul'] ?? '-') ?></small></td>
                            <td><?= $isRka ? '<span class="badge bg-success">RKA</span>' : '<span class="badge bg-primary">Pesan Cepat</span>' ?></td>
                            <td><?= esc($isRka ? ('Dokumen RKA - ' . ($row['unit_pengusul'] ?? '-')) : ($row['nama_alternatif'] ?? '-')) ?></td>
                            <td class="text-end fw-bold"><?= number_format((float)($row['nilai_simulasi'] ?? 0), 6, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada hasil aktif. Proses MOORA dahulu dari role Gudang.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>Perbandingan Skenario Top 10</strong></div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light"><tr><th>Skenario</th><th>Top Ranking</th><th>Nilai Simulasi</th><th>Perubahan dari Rank Awal</th></tr></thead>
                <tbody>
                <?php
                $labels = [
                    'urgensi_plus_15' => 'C1 Urgensi +15%',
                    'biaya_plus_15' => 'C2 Biaya +15%',
                    'dampak_plus_15' => 'C5 Dampak +15%',
                ];
                ?>
                <?php foreach ($labels as $key => $label) : ?>
                    <?php foreach (array_slice($scenarios[$key] ?? [], 0, 10) as $idx => $row) : ?>
                        <tr>
                            <td><?= $idx === 0 ? '<strong>' . esc($label) . '</strong>' : '' ?></td>
                            <td>#<?= esc($row['ranking_simulasi'] ?? '-') ?> - <?= esc($row['nomor_usulan'] ?? '-') ?><br><small class="text-muted"><?= esc($row['nama_alternatif'] ?? '-') ?></small></td>
                            <td class="fw-bold"><?= number_format((float)($row['nilai_simulasi'] ?? 0), 6, ',', '.') ?></td>
                            <td><?= (int)($row['perubahan_ranking'] ?? 0) === 0 ? '<span class="badge bg-secondary">Tetap</span>' : '<span class="badge bg-info text-dark">' . ((int)($row['perubahan_ranking'] ?? 0) > 0 ? '+' : '') . (int)($row['perubahan_ranking'] ?? 0) . '</span>' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
