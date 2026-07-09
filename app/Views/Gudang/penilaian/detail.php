<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$usulan = $usulan ?? [];
$detailUsulan = $detailUsulan ?? [];
$kriteria = $kriteria ?? [];
$generated = $generated ?? [];
$hasilPreview = $hasil_preview ?? [];
$modeLabel = $mode_label ?? '-';
?>

<div class="container-fluid">

<?php $excelRka = $usulan['file_rka_excel_path'] ?? $usulan['file_rka_path'] ?? null; ?>
<?php if (!empty($excelRka) || !empty($usulan['file_rka_dokumen_path'])) : ?>
    <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>Dokumen RKA:</strong>
            <?php if (!empty($excelRka)) : ?>
                <a href="<?= site_url('dokumen-rka/' . basename($excelRka)) ?>" target="_blank" class="ms-2">Excel Import</a>
            <?php endif; ?>
            <?php if (!empty($usulan['file_rka_dokumen_path'])) : ?>
                <a href="<?= site_url('dokumen-rka/' . basename($usulan['file_rka_dokumen_path'])) ?>" target="_blank" class="ms-2">Dokumen Resmi</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
    <div class="tm-page-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1">Proses MOORA Gudang</h2>
            <p class="mb-0 text-muted">Preview nilai otomatis sebelum hasil dikunci ke workflow.</p>
        </div>
        <a href="<?= site_url('gudang/penilaian') ?>" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card tm-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Usulan</h5>
                    <div class="row g-2">
                        <div class="col-md-6"><small class="text-muted">Nomor</small><br><strong><?= esc($usulan['nomor_usulan'] ?? '-') ?></strong></div>
                        <div class="col-md-6"><small class="text-muted">Unit</small><br><strong><?= esc($usulan['unit_pengusul'] ?? '-') ?></strong></div>
                        <div class="col-md-6"><small class="text-muted">Jenis</small><br><span class="badge bg-primary"><?= esc($usulan['jenis_usulan'] ?? '-') ?></span></div>
                        <div class="col-md-6"><small class="text-muted">Mode Engine</small><br><span class="badge bg-success"><?= esc($modeLabel) ?></span></div>
                        <div class="col-md-6"><small class="text-muted">Status</small><br><span class="badge bg-warning text-dark"><?= esc(ucwords(str_replace('_',' ', $usulan['status'] ?? '-'))) ?></span></div>
                        <div class="col-md-6"><small class="text-muted">Tanggal</small><br><strong><?= !empty($usulan['tanggal_usulan']) ? date('d/m/Y', strtotime($usulan['tanggal_usulan'])) : '-' ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Aturan V4</h5>
                    <p class="mb-2 small text-muted">RKA menghasilkan satu nilai Yi agregat dari seluruh barang. Pesan Cepat menghasilkan ranking per item barang.</p>
                    <form action="<?= site_url('gudang/penilaian/submit/' . ($usulan['id'] ?? 0)) ?>" method="post" onsubmit="return confirm('Proses MOORA final untuk usulan ini? Nilai C1-C5 otomatis akan disimpan dan status berubah menjadi moora_selesai.')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success w-100 fw-bold">
                            <i class="bi bi-cpu me-1"></i> Generate Nilai & Proses MOORA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm mb-3">
        <div class="card-body table-responsive">
            <h5 class="fw-bold mb-3">Nilai C1-C5 Otomatis per Barang</h5>
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <?php foreach($kriteria as $k): ?>
                            <th class="text-center"><?= esc($k['kode_kriteria'] ?? '') ?><br><small><?= esc($k['nama_kriteria'] ?? '') ?></small></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($detailUsulan as $i => $d): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <strong><?= esc($d['nama_alternatif'] ?? '-') ?></strong><br>
                            <small class="text-muted"><?= esc($d['kode_alternatif'] ?? '-') ?> | Stok: <?= esc($d['stok'] ?? 0) ?> | Min: <?= esc($d['stok_minimum'] ?? 0) ?> | <?= esc($d['kondisi_barang'] ?? '-') ?></small>
                        </td>
                        <td><?= esc($d['jumlah'] ?? 0) ?> <?= esc($d['satuan'] ?? '') ?></td>
                        <?php foreach($kriteria as $k): ?>
                            <td class="text-center fw-bold"><?= number_format((float)($generated[$d['id']][$k['id']] ?? 0), 2) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card tm-card border-0 shadow-sm">
        <div class="card-body table-responsive">
            <h5 class="fw-bold mb-3">Preview Hasil MOORA</h5>
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Ranking</th>
                        <th>Keputusan / Alternatif</th>
                        <th>Benefit</th>
                        <th>Cost</th>
                        <th>Nilai Yi</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($hasilPreview)): ?>
                        <?php foreach($hasilPreview as $row): ?>
                            <tr>
                                <td><span class="badge bg-primary">#<?= esc($row['ranking'] ?? '-') ?></span></td>
                                <td><strong><?= esc($row['nama_alternatif'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['kode_alternatif'] ?? '-') ?></small></td>
                                <td><?= number_format((float)($row['benefit'] ?? 0), 6) ?></td>
                                <td><?= number_format((float)($row['cost'] ?? 0), 6) ?></td>
                                <td class="fw-bold"><?= number_format((float)($row['nilai_yi'] ?? 0), 6) ?></td>
                                <td><?= esc($modeLabel) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Preview belum tersedia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
