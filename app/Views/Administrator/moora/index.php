<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$usulan = $usulan ?? [];
$kriteria = $kriteria ?? [];
$cekBobot = $cekBobot ?? ['valid' => false, 'total_bobot' => 0];
$filter = $filter ?? ['status' => '', 'tanggal_awal' => '', 'tanggal_akhir' => ''];
$setting = $setting ?? [];
$auditSummary = $auditSummary ?? [];
?>

<div class="container-fluid">
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Training & Monitoring MOORA</h2>
            <p class="mb-0 text-muted">V6 Bugfix Audit: Admin memonitor, mengelola bobot, dan menjalankan maintenance historis. Engine operasional tetap berada di Gudang.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?= site_url('administrator/moora-audit') ?>" class="btn btn-outline-primary btn-sm">Audit Engine V6</a>
            <a href="<?= site_url('administrator/dashboard') ?>" class="btn btn-light btn-sm">← Dashboard Admin</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')) : ?>
        <div class="alert alert-warning border-0 shadow-sm"><?= esc(session()->getFlashdata('warning')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="alert alert-info border-0 shadow-sm">
        <strong>Prinsip V6:</strong> proses MOORA resmi tetap dilakukan Gudang. Tombol konsolidasi di Admin hanya maintenance non-destruktif untuk data historis agar hasil lama mengikuti mode final: <code>rka_aggregate</code> untuk RKA dan <code>item_based</code> untuk Pesan Cepat.
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Total Dataset</small><h3 class="fw-bold mb-0"><?= number_format((int) ($totalUsulan ?? 0), 0, ',', '.') ?></h3></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Usulan Memiliki Detail</small><h3 class="fw-bold mb-0"><?= number_format((int) ($siapHitung ?? 0), 0, ',', '.') ?></h3></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Sudah Dihitung</small><h3 class="fw-bold mb-0"><?= number_format((int) ($sudahHitung ?? 0), 0, ',', '.') ?></h3></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Total Bobot Aktif</small><h3 class="fw-bold mb-0"><?= number_format((float) ($cekBobot['total_bobot'] ?? 0), 2) ?></h3><?= !empty($cekBobot['valid']) ? '<span class="badge bg-success">VALID</span>' : '<span class="badge bg-danger">TIDAK VALID</span>' ?></div></div></div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">RKA Sudah Agregat</small><h4 class="fw-bold mb-0"><?= (int)($auditSummary['rka_aggregate'] ?? 0) ?></h4><span class="badge bg-success">rka_aggregate</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">RKA Perlu Konsolidasi</small><h4 class="fw-bold mb-0 text-warning"><?= (int)($auditSummary['rka_masih_item'] ?? 0) ?></h4><span class="badge bg-warning text-dark">hasil lama</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Pesan Cepat Valid</small><h4 class="fw-bold mb-0"><?= (int)($auditSummary['pesan_cepat_item'] ?? 0) ?></h4><span class="badge bg-primary">item_based</span></div></div></div>
        <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">Belum Ada Hasil</small><h4 class="fw-bold mb-0"><?= (int)($auditSummary['belum_ada_hasil'] ?? 0) ?></h4><span class="badge bg-secondary">menunggu engine</span></div></div></div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" action="<?= site_url('administrator/kalkulasi-moora') ?>" class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-bold">Status Usulan</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status Non-Draft</option>
                        <?php foreach (['diajukan','diverifikasi','moora_selesai','menunggu_direktur_bidang','menunggu_direktur_utama','menunggu_direktur_umum','disposisi_pengadaan','diproses_pengadaan','menunggu_penerimaan','selesai','banding_gudang','dikembalikan','ditolak'] as $st) : ?>
                            <option value="<?= esc($st) ?>" <?= ($filter['status'] ?? '') === $st ? 'selected' : '' ?>><?= esc(ucwords(str_replace('_', ' ', $st))) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6"><label class="form-label fw-bold">Tanggal Awal</label><input type="date" name="tanggal_awal" value="<?= esc($filter['tanggal_awal'] ?? '') ?>" class="form-control"></div>
                <div class="col-lg-3 col-md-6"><label class="form-label fw-bold">Tanggal Akhir</label><input type="date" name="tanggal_akhir" value="<?= esc($filter['tanggal_akhir'] ?? '') ?>" class="form-control"></div>
                <div class="col-lg-3 col-md-6 d-flex gap-2"><button type="submit" class="btn btn-primary flex-fill">Terapkan</button><a href="<?= site_url('administrator/kalkulasi-moora') ?>" class="btn btn-outline-secondary flex-fill">Reset</a></div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Konsolidasi Historis V5</strong><br>
                <small class="text-muted">Menghitung ulang hasil lama tanpa mengubah status usulan, untuk sinkronisasi data hasil_moora dan moora_engine_log.</small>
            </div>
            <form method="post" action="<?= site_url('administrator/kalkulasi-moora/recalculate-v6') ?>" class="d-flex gap-2 align-items-center" onsubmit="return confirm('Jalankan konsolidasi historis V6? Backup database terlebih dahulu sebelum menjalankan.')">
                <?= csrf_field() ?>
                <input type="number" name="limit" value="100" min="1" max="500" class="form-control form-control-sm" style="width: 100px">
                <button type="submit" class="btn btn-warning btn-sm fw-bold">Recalculate V5</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Patch 11 - Ranking Global RKA</strong><br>
                <small class="text-muted">Menghitung ulang normalisasi MOORA antar dokumen RKA aktif berstatus moora_selesai. Non-destruktif dan tidak mengubah workflow.</small>
            </div>
            <form method="post" action="<?= site_url('administrator/kalkulasi-moora/global-rka') ?>" onsubmit="return confirm('Jalankan ranking global RKA antar dokumen aktif? Proses ini akan menambah versi hasil baru tanpa menghapus histori lama.')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-success btn-sm fw-bold">Hitung Global RKA</button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div><strong>Monitoring Dataset MOORA</strong><br><small class="text-muted">Aksi proses aktif berada pada role Gudang.</small></div>
            <a href="<?= site_url('administrator/setting') ?>" class="btn btn-outline-primary btn-sm">Atur Bobot & Setting</a>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th><th>Nomor Usulan</th><th>Unit / Pengusul</th><th>Jenis</th><th>Status</th><th>Item</th><th>Penilaian</th><th>Terakhir Hitung</th><th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usulan)) : ?>
                        <?php foreach ($usulan as $i => $row) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><strong><?= esc($row['nomor_usulan'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['tanggal_usulan'] ?? '-') ?></small></td>
                                <td><?= esc($row['unit_pengusul'] ?? '-') ?><br><small class="text-muted"><?= esc($row['nama_pengusul'] ?? '-') ?></small></td>
                                <td><span class="badge bg-primary"><?= esc($row['jenis_usulan'] ?? '-') ?></span></td>
                                <td><span class="badge bg-secondary"><?= esc(ucwords(str_replace('_', ' ', $row['status'] ?? '-'))) ?></span></td>
                                <td><span class="badge bg-light text-dark"><?= (int) ($row['jumlah_item'] ?? 0) ?> item</span></td>
                                <td><span class="badge bg-light text-dark"><?= (int) ($row['jumlah_nilai'] ?? 0) ?> nilai</span></td>
                                <td><?= !empty($row['terakhir_dihitung']) ? date('d/m/Y H:i', strtotime($row['terakhir_dihitung'])) : '<span class="text-muted">Belum</span>' ?></td>
                                <td class="text-center">
                                    <?php if (!empty($row['terakhir_dihitung'])) : ?>
                                        <a href="<?= site_url('administrator/kalkulasi-moora/hasil/' . $row['id']) ?>" class="btn btn-sm btn-outline-primary">Lihat Hasil</a>
                                    <?php else: ?>
                                        <span class="text-muted small">Menunggu Gudang</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada dataset sesuai filter.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
