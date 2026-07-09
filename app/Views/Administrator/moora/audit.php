<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$summary = $summary ?? [];
$engineRows = $engineRows ?? [];
$lastResult = $lastResult ?? null;
$issues = $issues ?? [];
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Audit Engine MOORA V6</h2>
            <p class="mb-0 text-muted">Monitoring konsistensi mode hasil: RKA harus rka_aggregate, Pesan Cepat harus item_based.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('administrator/kalkulasi-moora') ?>" class="btn btn-light btn-sm">← Monitoring MOORA</a>
            <a href="<?= site_url('administrator/dashboard') ?>" class="btn btn-outline-secondary btn-sm">Dashboard</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">RKA Agregat</small><h3 class="fw-bold mb-0"><?= (int)($summary['rka_aggregate'] ?? 0) ?></h3></div></div></div>
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">RKA Masih Item</small><h3 class="fw-bold mb-0 text-warning"><?= (int)($summary['rka_masih_item'] ?? 0) ?></h3></div></div></div>
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Pesan Cepat Item</small><h3 class="fw-bold mb-0"><?= (int)($summary['pesan_cepat_item'] ?? 0) ?></h3></div></div></div>
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Pesan Cepat Salah</small><h3 class="fw-bold mb-0 text-danger"><?= (int)($summary['pesan_cepat_lainnya'] ?? 0) ?></h3></div></div></div>
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Belum Hasil</small><h3 class="fw-bold mb-0"><?= (int)($summary['belum_ada_hasil'] ?? 0) ?></h3></div></div></div>
        <div class="col-xl-2 col-md-4 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Log Engine</small><h3 class="fw-bold mb-0"><?= count($engineRows) ?></h3></div></div></div>
    </div>

    <?php if (!empty($lastResult) && is_array($lastResult)) : ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>Hasil Konsolidasi V6 Terakhir</strong></div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Usulan</th><th>Jenis</th><th>Status</th><th>Mode Baru</th><th>Detail</th><th>Hasil</th><th>Keterangan</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach (($lastResult['items'] ?? []) as $item) : ?>
                            <tr>
                                <td><?= esc($item['nomor_usulan'] ?? '-') ?></td>
                                <td><?= esc($item['jenis_usulan'] ?? '-') ?></td>
                                <td><?= esc($item['status'] ?? '-') ?></td>
                                <td><span class="badge bg-primary"><?= esc($item['mode_hitung'] ?? '-') ?></span></td>
                                <td><?= (int)($item['jumlah_detail'] ?? 0) ?></td>
                                <td><?= (int)($item['jumlah_hasil'] ?? 0) ?></td>
                                <td><?= !empty($item['success']) ? '<span class="text-success">OK</span>' : '<span class="text-danger">' . esc($item['error'] ?? 'Gagal') . '</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Recalculate Historis V6</strong><br>
                <small class="text-muted">Maintenance non-destruktif: menghitung ulang hasil MOORA lama tanpa mengubah status usulan dan tanpa mengirim notifikasi Manajer.</small>
            </div>
            <form method="post" action="<?= site_url('administrator/kalkulasi-moora/recalculate-v6') ?>" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Jalankan konsolidasi historis V6? Pastikan database sudah dibackup.')">
                <?= csrf_field() ?>
                <input type="number" name="limit" value="100" min="1" max="500" class="form-control form-control-sm" style="width: 100px">
                <button type="submit" class="btn btn-warning btn-sm fw-bold">Jalankan</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Temuan Audit Konsistensi</strong><br>
                <small class="text-muted">Target V6: RKA = 1 hasil <code>rka_aggregate</code>, Pesan Cepat = <code>item_based</code>, semua versi punya log engine.</small>
            </div>
            <form method="post" action="<?= site_url('administrator/moora-audit/consolidate') ?>" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Jalankan konsolidasi audit V6? Backup database terlebih dahulu.')">
                <?= csrf_field() ?>
                <input type="number" name="limit" value="500" min="1" max="500" class="form-control form-control-sm" style="width: 100px">
                <button type="submit" class="btn btn-danger btn-sm fw-bold">Kunci Ulang V6</button>
            </form>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Usulan</th><th>Jenis</th><th>Status</th><th>Mode</th><th>Detail</th><th>Hasil</th><th>Log</th><th>Issue</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($issues)) : ?>
                        <?php foreach ($issues as $issue) : ?>
                            <tr>
                                <td><strong><?= esc($issue['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted">Versi: <?= esc($issue['versi_terakhir'] ?? '-') ?></small></td>
                                <td><?= esc($issue['jenis_usulan'] ?? '-') ?></td>
                                <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_',' ', $issue['status'] ?? '-'))) ?></span></td>
                                <td><code><?= esc($issue['mode_terbaru'] ?? '-') ?></code></td>
                                <td><?= (int)($issue['jumlah_detail'] ?? 0) ?></td>
                                <td><?= (int)($issue['jumlah_hasil_terbaru'] ?? 0) ?></td>
                                <td><?= (int)($issue['jumlah_log'] ?? 0) ?></td>
                                <td><span class="badge bg-warning text-dark"><?= esc($issue['kode_issue'] ?? '-') ?></span><br><small><?= esc($issue['pesan'] ?? '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="8" class="text-center text-success py-4">Tidak ada temuan kritis pada hasil terbaru.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>Riwayat moora_engine_log</strong></div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Usulan</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Mode</th>
                        <th>Detail</th>
                        <th>Hasil</th>
                        <th>Diproses Oleh</th>
                        <th>Versi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($engineRows)) : ?>
                        <?php foreach ($engineRows as $row) : ?>
                            <tr>
                                <td><?= !empty($row['created_at']) ? date('d/m/Y H:i', strtotime($row['created_at'])) : '-' ?></td>
                                <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong></td>
                                <td><?= esc($row['jenis_usulan'] ?? '-') ?></td>
                                <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_',' ', $row['status'] ?? '-'))) ?></span></td>
                                <td><span class="badge bg-primary"><?= esc($row['mode_hitung'] ?? '-') ?></span></td>
                                <td><?= (int)($row['jumlah_detail'] ?? 0) ?></td>
                                <td><?= (int)($row['jumlah_hasil'] ?? 0) ?></td>
                                <td><?= esc($row['processed_name'] ?? '-') ?><br><small class="text-muted"><?= esc($row['processed_role'] ?? '-') ?></small></td>
                                <td><small><?= esc($row['versi_hitung'] ?? '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada log engine MOORA.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
